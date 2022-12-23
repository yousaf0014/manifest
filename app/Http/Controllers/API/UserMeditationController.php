<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use App\User;
use App\UserSession;
use App\Category;
use App\Meditation;
use App\Topic;
use App\AppuseLog;
use App\UserReminder;
use Illuminate\Http\File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;


class UserMeditationController extends BaseController 
{

    public function getUserMeditationPreferences(){
        $user = Auth::user();
        $userMeditaion = $user->logs()->orderBy('id','Desc')->select(DB::raw('distinct(meditation_id) as meditation_id'))->limit(5)->get();
        $userPreferences = array(); $index = 0;
        foreach($userMeditaion as $md){
            $mdData = Meditation::where('id',$md->meditation_id)->first();
            $userPreferences['History'][$index]['title'] = $mdData->title;
            $userPreferences['History'][$index]['pic'] = $mdData->pic;
            $userPreferences['History'][$index++]['id'] = $md->meditation_id;
        }
        $meditations = $user->userPreference()->get();
        $index = 0;
        foreach($meditations as $md){
            $userPreferences['Bookmarks'][$md->type][$index]['id'] = $md->id;
            $userPreferences['Bookmarks'][$md->type][$index]['pic'] = $md->pic;
            $userPreferences['Bookmarks'][$md->type][$index++]['title'] =$md->title;            
        }
        return response()->json(['success' => array('user_preferences'=>$userPreferences)], 200);
    }

    public function deleteReminder(Request $request,$reminderID){
        $user = Auth::user();
        $reminders = $user->reminder()->where('id',$reminderID)->delete();
        
        return response()->json(['success' => array('message'=>'Scuccessfully deleted')], 200);   
    }
    public function updateReminders(Request $request,$reminderID){
        $validator = Validator::make($request->all(),[
            'reminder'=> "required",
            'time' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user();
        $reminder = $user->reminder()->where('id',$reminderID)->first();
        $reminder->notifications  = $request->reminder;
        $reminder->time = DMY2YMD($request->time);
        $reminder->save();
        return response()->json(['success' => array('message'=>'Scuccessfully Updated')], 200);   
    }
    public function getReminders(Request $request){
        $user = Auth::user();
        $reminders = $user->reminder()->where('active',1)->get();
        $reminderArr = array(); $index=0;
        foreach($reminders as $reminder){
            $reminderArr[$index]['id'] = $reminder->id;
            $reminderArr[$index]['time'] = YMD2DMY($reminder->time,'/');
            $reminderArr[$index++]['reminder'] = $reminder->notifications;
        }
        return response()->json(['success' => array('reminders'=>$reminderArr)], 200);   
    }

    public function saveReminders(Request $request){
        $validator = Validator::make($request->all(),[
            'reminder'=> "required",
            'time' => 'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user();
        $userReminder = New UserReminder;
        $userReminder->notifications = $request->reminder;
        $userReminder->time = DMY2YMD($request->time);
        $user->reminder()->save($userReminder);
        return response()->json(['success' => array('message'=>'Scuccessfully Saved')], 200);   
    }
    
    public function milestones(Request $request){
        $user = Auth::user();
        $cup = false; 
        $outArr = array();
        $index = 0;
        $meditationSessionCount = $user->logs()->groupBy('meditation_id')->select(DB::raw('count(meditation_id) as session_count, meditation_id,sum(listen_time) as listen_time,sum(stay_time) as stay_time'))->get();
        foreach($meditationSessionCount as $mdSC){
            $meditation = Meditation::where('id',$mdSC->meditation_id)->first();
            $outArr['Sessions'][$index]['id'] = $mdSC->meditation_id;
            $outArr['Sessions'][$index]['title'] = $meditation->title;
            $outArr['Sessions'][$index]['pic'] = $meditation->pic;
            $outArr['Sessions'][$index]['session_count'] = $mdSC->session_count;
            $outArr['Sessions'][$index]['listen_time'] = $mdSC->listen_time/60;
            $outArr['Sessions'][$index++]['stay_time'] = $mdSC->listen_time/60;
        }


        $key = 'datesArr_'.date('Y-m-d');
        if (!Cache::has($key)) {
            $daysArr = array(2=>date('Y-m-d',strtotime("-2 days")),10=>date('Y-m-d',strtotime("-10 days")),20=>date('Y-m-d',strtotime('-20 days')),50=>date('Y-m-d',strtotime('-50 days')),100=>date('Y-m-d',strtotime('-100 days')),150=>date('Y-m-d',strtotime('-150 days')),200=>date('Y-m-d',strtotime('-200 days')),250=>date('Y-m-d',strtotime('-250 days')),300=>date('Y-m-d',strtotime('-300 days')),350=>date('Y-m-d',strtotime('-350 days')),400=>date('Y-m-d',strtotime('-400 days')),450=>date('Y-m-d',strtotime('-450 days')),500=>date('Y-m-d',strtotime('-500 days')),550=>date('Y-m-d',strtotime('-550 days')),600=>date('Y-m-d',strtotime('-600 days')),650=>date('Y-m-d',strtotime('-650 days')),700=>date('Y-m-d',strtotime('-700 days')),750=>date('Y-m-d',strtotime('-750 days')),800=>date('Y-m-d',strtotime('-800 days')),850=>date('Y-m-d',strtotime('-850 days')),900=>date('Y-m-d',strtotime('-900 days')),950=>date('Y-m-d',strtotime('-950 days')),1000=>date('Y-m-d',strtotime('-1000 days')));
            Cache::put($key, $daysArr, 86400);
        }else{
            $daysArr = Cache::get($key);
        }

        
        $toDate = date('Y-m-d');
        $criteriaMeet = array();
        foreach($daysArr as $count=>$date){
            $consectiveDaysCount = $user->logs()->whereBetween('use_date',["$date","$toDate"])->groupBy('category_id')->select(DB::raw('count(distinct(use_date)) as day_count, category_id'))->get();
            
            if(empty($consectiveDaysCount[0]->day_count)){
                break;
            }
            foreach($consectiveDaysCount as $data){
                if($data->day_count == $count){
                    $cup = true;
                    $criteriaMeet[$data->category_id] = $count;
                }
            }
        }
        $index = 0;
        foreach($criteriaMeet as $catID=>$days){
            $cateogry = Category::where('id',$catID)->first();
            $outArr['days'][$index]['id'] = $catID;
            $outArr['days'][$index]['category'] = $cateogry->title;
            $outArr['days'][$index++]['days'] = $days;
        }
        $outArr['cup'] = $cup;
        return response()->json(['success' => array('milestones'=>$outArr)], 200);

    }
    public function useLog(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'meditation_id'=> "required",
            'stay_time' => 'required',
            'listen_time' => 'required',
            'use_date' => 'required',
            'completed' =>'required'
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user1 = Auth::user();
        $logObj = new AppuseLog;


        $logObj->meditation_id = $request->meditation_id;
        /*$listenSec = 0;
        if(!empty($request->listen_time_start)){
            $l1 = strtotime(DMY2YMD($request->listen_time_start));
            $l2 = strtotime(DMY2YMD($request->listen_time_end));
            $listenSec = $l2 - $l1;
        }*/
        $logObj->listen_time = $request->listen_time;
        /*$st1 = strtotime(DMY2YMD($request->stay_time_start));
        $st2 = strtotime(DMY2YMD($request->stay_time_end));
        $stayTime = $st2 - $st1; */
        $logObj->stay_time = $request->stay_time;
        $logObj->completed = $request->completed;
        
        $meditationObj = Meditation::where('id',$request->meditation_id)->first(); 
        $logObj->category_id = $meditationObj->category_id;
        $logObj->use_date = DMY2YMDNoTime($request->use_date);

        $user1->logs()->save($logObj);
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }

    public function deleteFollowing(Request $request,$pivotID){
        $user = Auth::user();
        $user->userFollow()->wherePivot('id','=',$pivotID)->detach();
        return response()->json(['success' => array('message'=>'scuccessfully detached')], 200);
    }

    public function unfollow(Request $request,User $user){
        $user1 = Auth::user();
        $user1->userFollow()->detach($user->id);
        return response()->json(['success' => array('message'=>'scuccessfully detached')], 200);   
    }

    public function viewFollowing(Request $request)
    {
        $user = Auth::user();
        $followings = $user->userFollow()->get();
        $followList = array(); $index = 0;
        foreach($followings as $usr){
            $followList[$index]['pivot_id'] = $usr->pivot->id;
            $followList[$index]['user_id'] = $usr->id;
            $followList[$index++]['name'] = $usr->first_name.' '.$usr->last_name;
        }
        return response()->json(['success' => array('followings'=>$followList)], 200);
    }

    public function userFollow(Request $request,User $user)
    {
        $user1 = Auth::user();
        $user1->userFollow()->attach($user->id);
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }

    public function viewRating(Request $request,Meditation $meditation)
    {
       /* $user = Auth::user();
        $comments = $meditation->rateUser()->get();
        return response()->json(['success' => array('comments'=>$commentArr)], 200); */
    }

    public function storeRating(Request $request,Meditation $meditation)
    {
        $user = Auth::user();
        $rate = $request->rate;
        $meditation->rateUser()->detach($user->id);
        $meditation->rateUser()->attach($user->id, ['rate' => $rate]);
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }

    public function deletepreference(Request $request,Meditation $meditation){
        $user = Auth::user();
        $meditation->preferenceUser()->detach($user->id);
        return response()->json(['success' => array('message'=>'scuccessfully detach')], 200);
    }

    public function viewPreference(Request $request)
    {
        $user = Auth::user();
        $meditations = $user->userPreference()->get();
        $userPreferences = array(); $index = 0;
        foreach($meditations as $md){
            $userPreferences[$md->type][$index]['id'] = $md->id;
            $userPreferences[$md->type][$index++]['title'] =$md->title;            
        }
        return response()->json(['success' => array('user_preferences'=>$userPreferences)], 200);
    }

    public function storePreference(Request $request,Meditation $meditation)
    {
        $user = Auth::user();
        $comment = $request->comment;
        $meditation->preferenceUser()->detach($user->id);
        $meditation->preferenceUser()->attach($user->id);
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }

    public function deleteComment(Request $request,Meditation $meditation,$pivotID){
        $meditation->commentUser()->wherePivot('id','=',$pivotID)->detach();
        return response()->json(['success' => array('message'=>'scuccessfully deleted')], 200);
    }

    public function deleteCommentbYid(Request $request,Meditation $meditation,$pivotID){
        $comment = $request->comment;
        $meditation->commentUser()->where('comment',$comment)->detach($user->id);;
    }

    public function viewComments(Request $request,Meditation $meditation)
    {
        $user = Auth::user();
        $commentsObj = $meditation->commentUser();
        $commentsObj = $commentsObj->orderBy('pivot_id','Desc');
        $comments = $commentsObj->paginate(10);
        $commentArr = array(); $index = 0;
        foreach($comments as $coment){
            $commentArr[$index]['meditation_id'] = $meditation->id; 
            $commentArr[$index]['comment'] = $coment->pivot->comments;
            $commentArr[$index]['comment_id'] = $coment->pivot->id;
            $commentArr[$index]['user_id'] = $coment->id;
            $commentArr[$index++]['name'] = $coment->first_name.' '.$coment->last_name;
        }
        return response()->json(['success' => array('comments'=>$commentArr)], 200);
    }

    public function updateComments(Request $request,Meditation $meditation,$pivot)
    {
        $user = Auth::user();
        $comment = $request->comment;
        $comments = $meditation->commentUser()->wherePivot('id',$pivot)->updateExistingPivot($user->id, ['comments' => $comment]);
        //return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
        return $this->viewComments($request,$meditation);
    }

    public function storeComments(Request $request,Meditation $meditation)
    {
        $user = Auth::user();
        $comment = $request->comment;
        $comments = $meditation->commentUser()->attach($user->id, ['comments' => $comment]);
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }
    public function storeTopics(Request $request)
    {
        $user = Auth::user();
        $topics = $request->topics;
        foreach($topics as $topic){
            $currentTopic = Topic::where('id',$topic)->first();
            //$user->userCategory()->associate($currentCat);
            $user->topics()->save($currentTopic);

            //$user->account()->dissociate();
        }
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
        //return $this->viewComments($request,$meditation);
    }

    public function viewTopics(Request $request){
        $user = Auth::user();
        $topics = $user->topics()->where('active',1)->get();
        $outTopics = array();
        $index = 0;
        foreach($topics as $top){
            $outTopics['user_topics'][$index]['title'] = $top->title;
            $outTopics['user_topics'][$index++]['id'] = $top->id;
        }
        return response()->json(['success' =>$outTopics], 200);
    }

    public function topicsList(Request $request){
        $topics = Topic::get();
        $outTopics = array();

        $user = Auth::user();
        $utopics = $user->topics()->where('active',1)->get();
        $userTopics = array();
        foreach($utopics as $top){
            $userTopics[$top->id] = $top->id;
        }

        $index = 0;
        foreach($topics as $top){
            $outTopics['topics'][$index]['title'] = $top->title;
            $outTopics['topics'][$index]['id'] = $top->id;
            if(!empty($userTopics[$top->id])){
                $outTopics['topics'][$index++]['selected'] = 'yes';
            }else{
                $outTopics['topics'][$index++]['selected'] = 'no';
            }
        }
        return response()->json(['success' =>$outTopics], 200);
    }

    public function listCategories(Request $request,Topic $topic){
        $categories = $topic->categories()->orderBy('id')->get();
        $outCategories = array();
        $selected = 0;$index1 = 0;
        foreach($categories as $cat){
            $selected = $selected===0 ? $cat->id:$selected;
            $outCategories['categories'][$index1]['title'] = $cat->title;
            $outCategories['categories'][$index1]['id'] = $cat->id;

            $meditations = $this->__getMeditationForUser('meditation',true,$cat->id);
            $meditationsArr = array();
            $index = 0;
            foreach($meditations as $med){
                $outCategories['categories'][$index1]['meditations'][$index]['title'] = $med->title;
                $outCategories['categories'][$index1]['meditations'][$index]['id'] = $med->id;
                $outCategories['categories'][$index1]['meditations'][$index]['description'] = $med->description;
                $outCategories['categories'][$index1]['meditations'][$index]['path'] = $med->path;
                $outCategories['categories'][$index1]['meditations'][$index]['pic'] = $med->pic;
                $outCategories['categories'][$index1]['meditations'][$index++]['view'] = $med->view;
            }
            $meditations = $this->__getMeditationForUser('affirmation',true,$cat->id);
            $meditationsArr = array();
            $index = 0;
            foreach($meditations as $med){
                $outCategories['categories'][$index1]['affirmations'][$index]['title'] = $med->title;
                $outCategories['categories'][$index1]['affirmations'][$index]['id'] = $med->id;
                $outCategories['categories'][$index1]['affirmations'][$index]['description'] = $med->description;
                $outCategories['categories'][$index1]['affirmations'][$index]['path'] = $med->path;
                $outCategories['categories'][$index1]['affirmations'][$index]['pic'] = $med->pic;
                $outCategories['categories'][$index1]['affirmations'][$index++]['view'] = $med->view;
            }
            $index1++;

        }
        $outCategories['selected_category_id'] = $selected;
        

        return response()->json(['success' =>$outCategories], 200);
    }
    

    function __getMeditationForUser($meditation=false,$topicID = null,$categoryID = null){
        $access = false;
        $user = Auth::user();
        if($user->is_paid == 1){
            $access = true;
        }else if($user->trial_period == 1){
            $access = true;
        }else{
            $access = 'free';
        }

        if($meditation ==  false){
            return $access;
        }
        $medicationObj = new Meditation;
        $medicationObj = $medicationObj->where('posted',1);
        $medicationObj = $medicationObj->where('status','approved');
        $medicationObj = $medicationObj->where('type',$meditation);
        $meditations;
        if($access == 'free'){
            $medicationObj = $medicationObj->where('is_free',1);
        }
        $cateIds = array();
        if(empty($categoryID)){
            $topic = Topic::where('id',$topicID)->first();
            $categories = $topic->category()->get();
            $cateIds = array();
            foreach($categories as $cat){
                $cateIds[$cat->id] = $cat->id;
            }
        }else{
            $cateIds[$categoryID] = $categoryID; 
        }
        $medicationObj = $medicationObj->wherein('category_id',$cateIds);
        return $meditations = $medicationObj->get();
    }


    public function meditations(Request $request,$topic){
        $meditations = $this->__getMeditationForUser('meditation',$topic);
        $meditationsArr = array();
        foreach($meditations as $med){
            $meditationsArr['meditations'][$med->id]['title'] = $med->title;
            $meditationsArr['meditations'][$med->id]['description'] = $med->description;
            $meditationsArr['meditations'][$med->id]['path'] = $med->path;
            $meditationsArr['meditations'][$med->id]['pic'] = $med->pic;
            $meditationsArr['meditations'][$med->id]['view'] = $med->view;

        }
        return response()->json(['success' =>$meditationsArr], 200);
    }

    public function categoryMeditations($categoryID){
        $meditations = $this->__getMeditationForUser('meditation',true,$categoryID);
        $meditationsArr = array();
        foreach($meditations as $med){
            $meditationsArr['meditations'][$med->id]['title'] = $med->title;
            $meditationsArr['meditations'][$med->id]['description'] = $med->description;
            $meditationsArr['meditations'][$med->id]['path'] = $med->path;
            $meditationsArr['meditations'][$med->id]['pic'] = $med->pic;
            $meditationsArr['meditations'][$med->id]['view'] = $med->view;

        }
        return response()->json(['success' =>$meditationsArr], 200);
    }

    public function meditationDetails(Meditation $meditation){
        $user = Auth::user();
        $fav = $user->userPreference()->where('meditation_id',$meditation->id)->first();
        $meditationsArr['meditation']['title'] = $meditation->title;
        $meditationsArr['meditation']['description'] = $meditation->description;
        $meditationsArr['meditation']['path'] = $meditation->path;
        $meditationsArr['meditation']['pic'] = $meditation->pic;
        $meditationsArr['meditation']['view'] = $meditation->view;
        $meditationsArr['meditation']['bookmark'] = !empty($fav->id) ? true:false;
        $meditation->view = $meditation->view*1 + 1;
        $meditation->save();

        $commentsObj = $meditation->commentUser();
        $commentsObj = $commentsObj->orderBy('pivot_id','Desc');
        $commentsData = $commentsObj->paginate(10);

        $comments = array(); $index = 0;
        foreach($commentsData as $comment){
            $comments[$index]['comment'] = $comment->pivot->comments;
            $comments[$index]['comment_id'] = $comment->pivot->id;
            $comments[$index]['name'] = $comment->first_name.' '.$comment->last_name;
            $comments[$index++]['user_id'] = $comment->id;
        }
        $meditationsArr['meditation']['comments'] = $comments;

        
        $rateing = $meditation->meditationRating()->where('user_id',$user->id)->first();        
        $meditationUser = User::where('id',$meditation->user_id)->first();
        $userFollow = $user->userFollow()->where('guide_id',$meditation->user_id)->first();
        $meditationsArr['meditation']['user']['user_following'] = empty($userFollow->id) ? 0:1;
        $meditationsArr['meditation']['user']['ratings'] = !empty($rateing->rate)? $rateing->rate:0;
        $meditationsArr['meditation']['guide']['user_id'] = $meditation->user_id;
        $meditationsArr['meditation']['guide']['name'] = $meditationUser->first_name.' '.$meditationUser->last_name;
        return response()->json(['success' =>$meditationsArr], 200);
    }

    public function affirmations(Request $request,$topic){
        $affirmations = $this->__getMeditationForUser('affirmation',$topic);
        $affirmationsArr = array();
        foreach($affirmations as $med){
            $affirmationsArr['affirmations'][$med->id]['title'] = $med->title;
            $affirmationsArr['affirmations'][$med->id]['description'] = $med->description;
            $affirmationsArr['affirmations'][$med->id]['path'] = $med->path;
            $affirmationsArr['affirmations'][$med->id]['pic'] = $med->pic;
            $affirmationsArr['affirmations'][$med->id]['view'] = $med->view;

        }
        return response()->json(['success' =>$affirmationsArr], 200);
    }

    public function affirmationsDetails(Meditation $meditation){
        $meditationArr['affirmation'][$meditation->id]['title'] = $meditation->title;
        $meditationArr['affirmation'][$meditation->id]['description'] = $meditation->description;
        $meditationArr['affirmation'][$meditation->id]['view'] = $meditation->view;
        $meditation->view = $meditation->view*1 + 1;
        $meditation->save();

        $user = Auth::user();
        $meditationUser = User::where('id',$meditation->user_id)->first();
        $userFollow = $user->userFollow()->where('guide_id',$meditation->user_id)->first();
        $meditationArr['meditation']['user']['user_following'] = empty($userFollow->id) ? 'no':'yes';
        $meditationArr['meditation']['user']['user_id'] = $meditation->user_id;
        $meditationArr['meditation']['user']['name'] = $meditationUser->first_name.' '.$meditationUser->last_name;
        return response()->json(['success' =>$meditationArr], 200);
    }
}