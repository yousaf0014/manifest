<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use App\Meditation;
use App\Category;
use App\User;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function readFile(Request $request,$file){
        return Storage::response('private/audio/'. $file);

    }

    public function notification(){
        $meditationObj = new Meditation;
        $notifications = $meditationObj->where('status','pending')->take(4)->get();
        return view('Home.notifications',compact('notifications'));
    }

    public function notificationCount(){
        $meditationObj = new Meditation;
        $count = $meditationObj->where('status','pending')->count();
        return $count;
    }

    public function list_notifications(Request $request){
        $mdObj = new Meditation;        
        //$mdObj = $mdObj->where('type','meditation');
        $status = $keyword = '';
        if(!empty($request->status)){
            $status = $request->status;
            $mdObj = $mdObj->where('status',$status);
        }else{
            $mdObj = $mdObj->where('status','pending');
        }
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $mdObj = $mdObj->where('title','like', '%'.$keyword.'%');
        }        
        $meditations = $mdObj->paginate(20);
        return view('Home.pendingApproval',compact('status','meditations','keyword'));   
    }
    public function list_meditations(Request $request){
        $mdObj = new Meditation;        
        $mdObj = $mdObj->where('type','meditation');
        $status  =  'approved';
        $keyword = '';
        if(!empty($request->status)){
            $status = $request->status;
            $mdObj = $mdObj->where('status',$status);
        }else{
            $mdObj = $mdObj->where('status','approved');
        }
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $mdObj = $mdObj->where('title','like', '%'.$keyword.'%');
        }        
        $meditations = $mdObj->paginate(20);
        return view('Home.meditations',compact('status','meditations','keyword'));   
    }
    public function list_affirmations(Request $request){
        $mdObj = new Meditation;        
        $mdObj = $mdObj->where('type','affirmation');
        $status  =  'approved';
        $keyword = '';
        if(!empty($request->status)){
            $status = $request->status;
            $mdObj = $mdObj->where('status',$status);
        }else{
            $mdObj = $mdObj->where('status','approved');
        }
        if(!empty($request->keyword)){
            $keyword = $request->keyword;
            $mdObj = $mdObj->where('title','like', '%'.$keyword.'%');
        }        
        $meditations = $mdObj->paginate(20);
        return view('Home.affirmations',compact('status','meditations','keyword'));   
    }

    public function meditation_approve(Request $request,Meditation $meditation){
        $meditation->status = 'approved';
        $meditation->category_id = $request->category_id;
        $meditation->is_free = $request->is_free == 1 ? 1:0;
        $meditation->schedule = !empty($request->schedule) ? date('Y-m-d H:i:s',strtotime($request->schedule)):date('Y-m-d H:i:s');
        $meditation->save();
        flash('Successfully approved.','success');
        return back(); 
    }

    public function meditation_decline(Request $request,Meditation $meditation){
        $meditation->status = 'not_approved';
        $meditation->save();
        flash('Successfully declined.','success');
        return back(); 
    }

    public function notification_detail(Request $request,$type,$id){
        $record;
        if($type=='meditation'){
            $record = Meditation::where('id',$id)->first();
        }
        $categorires = Category::get();
        $selected = Category::where('id',$record->category_id)->first();
        $user = User::where('id',$record->user_id)->first();
        return view('Home.notificationDetail',compact('record','categorires','selected','user'));      
    }
}