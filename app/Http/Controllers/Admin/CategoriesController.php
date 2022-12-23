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
use App\Category;
use App\Topic;
use Auth;

class CategoriesController extends Controller
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
        $categoryObj = new Category;        
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $categoryObj = $categoryObj->where('title','like', '%'.$keyword.'%');
        }        
        $categories = $categoryObj->paginate(20);
        $categoryTopics = array();
        foreach($categories as $cat){
            $categoryTopics[$cat->id] = $cat->topics()->get();
        }
        return view('Admin.Categories.index',compact('categories','keyword','categoryTopics'));
    }

    public function create(){
        $topics = array();
        $topicObj = new Topic;
        $topicsData = $topicObj->get();
        foreach($topicsData as $top){
            $topics[$top->id] = $top->title;
        }
        return View('Admin.Categories.create',compact('topics'));
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
            $topics = $data['topics'];
            unset($data['topics']);
            $categoryObj = new Category;
            $cat = $categoryObj->create($data);
            foreach($topics as $top){
                $cTop = Topic::where('id',$top)->first();
                $cat->topics()->save($cTop);
            }
            flash('Successfully Saved.','success');
            return redirect('categories/'); 
        }
    }

    public function show(Category $category){
        $catTopic = $category->topics()->get();
        return View('Admin.Categories.show',compact('category','catTopic'));   
    }

    public function edit(Category $category){
        $catTopic = $category->topics()->get();
        $selectedArr = array();
        foreach($catTopic as $ct){
            $selectedArr[$ct->id] = $ct->id;
        }
        $topics = array();
        $topicObj = new Topic;
        $topicsData = $topicObj->get();
        foreach($topicsData as $top){
            $topics[$top->id] = $top->title;
        }
        return View('Admin.Categories.edit',compact('category','selectedArr','topics'));
    }

    public function update(Request $request,Category $category){
        $rules = array(
            'title' => 'required|string'
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $category->topics()->detach();
            $data = $request->all();
            $topics = $data['topics'];
          
            unset($data['topics']);unset($data['_method']);unset($data['_token']); 
            foreach($data as $field=>$value){ 
                $category->$field = $value;                
            }
            $category->save();
            foreach($topics as $top){
                $cTop = Topic::where('id',$top)->first();
                $category->topics()->save($cTop);
            }
            flash('Successfully updated Content!','success');
            return redirect('categories/');
        }
    }

    public function delete(Category $category){
        $category->topics()->detach();
        $category->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('categories/');
    }
}