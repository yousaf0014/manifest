<?php
namespace App\Http\Controllers\Guide;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Http;
use App\User;
use App\Meditation;
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class AffirmationsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(array('auth','guide'));
    }
    
     public function index(Request $request)
    {
        $mdObj = new Meditation;        
        $mdObj = $mdObj->where('type','affirmation');
        $mdObj = $mdObj->where('user_id',Auth::user()->id);

        $status = $keyword = '';
        if(!empty($request->status)){
            $status = $request->status;
            $mdObj = $mdObj->where('status',$status);
        }else{
            $mdObj = $mdObj->where('status','pending');
        }
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $mdObj = $mdObj->where('title','like', '%'.$keyword.'%');
        }        
        $affirmations = $mdObj->paginate(20);
        return view('Guide.Affirmation.index',compact('affirmations','keyword','status'));
    }

    public function create(){        
        return View('Guide.Affirmation.create');
    }

    public function store(Request $request){
        $rules = array(
            'title' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:500']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $mdObj = new Meditation;
            
            $data['type'] = 'affirmation';
            $data['user_id'] = Auth::user()->id;
            $data['status'] = 'pending';
            $mdObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('affirmations/'); 
        }
    }

    public function show(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='affirmation'){
            return back();
        } 
        return View('Guide.Affirmation.show',compact('md'));   
    }

    public function edit(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        }
        return View('Guide.Affirmation.edit',compact('md'));
    }

    public function update(Request $request,Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        } 
        $rules = array(
            'title' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:500']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } 
        $data = $request->all();                    
        unset($data['_method']);unset($data['_token']);  unset($data['password_confirmation']); 
        
        $data['type'] = 'affirmation';
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 'pending';
        foreach($data as $field=>$value){ 
            $md->$field = $value;
        }
        $md->save();
        flash('Successfully updated Content!','success');
        return redirect('affirmations/');
    
    }

    public function delete(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='affirmation'){
            return back();
        }
        $md->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('affirmations/');
    }

}