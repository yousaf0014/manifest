<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use App\Topic;
use Auth;

class TopicsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(array('auth','admin'));
    }
    
     public function index(Request $request)
    {
        $topicObj = New Topic;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $topicObj = $topicObj->where('title','like', '%'.$keyword.'%');
        }        
        $topics = $topicObj->paginate(20);
        return view('Admin.Topics.index',compact('topics','keyword'));
    }

    public function create(){        
        return View('Admin.Topics.create');
    }

    public function store(Request $request){
        $rules = array(
            'title' => 'nullable|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $topicObj = New Topic;
            $topicObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('topics/'); 
        }
    }

    public function show(Topic $topic){ 
        return View('Admin.Topics.show',compact('topic'));   
    }

    public function edit(Topic $topic){
        return View('Admin.Topics.edit',compact('topic'));
    }

    public function update(Request $request,Topic $topic){    
        $rules = array(
            'title' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();                    
            unset($data['_method']);unset($data['_token']); 
            foreach($data as $field=>$value){ 
                $topic->$field = $value;                
            }
            $topic->save();
            flash('Successfully updated Content!','success');
            return redirect('topics/');
        }
    }

    public function delete(Topic $topic){
        $topic->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('topics/');
    }
}