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


class MeditationsController extends Controller
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
        $mdObj = $mdObj->where('type','meditation');
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
        $meditations = $mdObj->paginate(20);
        return view('Guide.Meditation.index',compact('meditations','keyword','status'));
    }

    public function create(){        
        return View('Guide.Meditation.create');
    }

    public function store(Request $request){
        ini_set('memory_limit','512M');
        $audioF = $request->file('audio');
        $mime =  $audioF->getMimeType();

        $rules = array(
            'pic' => 'required|image|max:2000',
            'title' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:500'],
            'audio' => ['required']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            /*echo '<pre>';
            ,'mimes:application/octet-stream,audio/ogg,audio/x-wav,audio/wav'
            print_r($validator->error);
            exit;*/
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }else if(!in_array($mime, array('application/octet-stream','audio/mpeg','audio/ogg','audio/x-wav','audio/wav'))){
            $error['audio'] = 'Please provide valid audio file';
            return Redirect::back()->withInput($request->all())->withErrors($error);
        } else {
            $data = $request->all();
            $mdObj = new Meditation;
            
            ///// Pic for maditation /////
            $file = $request->file('pic');
            $destinationPath = 'uploads/';
            $file->store($destinationPath, ['disk' => 'public']);
            $data['pic'] = $file->hashName();
                    
            ///// audio File for maditation /////
            $audioF = $request->file('audio');
            $destinationPath = 'audio/';
            $ext = $audioF->getClientOriginalExtension();
            $sname = explode('.',$audioF->hashName());
            $nmae = $sname[0];
            $audioF->storeAS($destinationPath,$nmae.'.'.$ext ,['disk' => 'private']);
            $data['path'] = $nmae.'.'.$ext;
            $data['type'] = 'meditation';
            $data['user_id'] = Auth::user()->id;
            $data['status'] = 'pending';
            $mdObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('meditations/'); 
        }
    }

    public function show(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        } 
        return View('Guide.Meditation.show',compact('md'));   
    }

    public function edit(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        }
        return View('Guide.Meditation.edit',compact('md'));
    }

    public function update(Request $request,Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        } 
        $rules = array(
            'pic' => 'nullable|image|max:2000',
            'title' => ['required', 'string', 'max:300'],
            'description' => ['required', 'string', 'max:500']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else if(!empty($request->file('audio'))){
            $audioF = $request->file('audio');
            $mime =  $audioF->getMimeType();
            if(!in_array($mime, array('application/octet-stream','audio/mpeg','audio/ogg','audio/x-wav','audio/wav'))){
                $error['audio'] = 'Please provide valid audio file';
                return Redirect::back()->withInput($request->all())->withErrors($error);
            }
        } 
        $data = $request->all();                    
        unset($data['_method']);unset($data['_token']);  unset($data['password_confirmation']); 
        
        ///// Pic for maditation /////
        if(!empty($request->file('pic'))){
            $file = $request->file('pic');
            $destinationPath = 'uploads/';
            $file->store($destinationPath, ['disk' => 'public']);
            $data['pic'] = $file->hashName();
        }else{
            unset($data['pic']);
        }   
        ///// audio File for maditation /////
        if(!empty($request->file('audio'))){
            Storage::delete('private/audio/'.$md->path);
            $audioF = $request->file('audio');
            $destinationPath = 'audio/';
            $ext = $audioF->getClientOriginalExtension();
            $sname = explode('.',$audioF->hashName());
            $nmae = $sname[0];
            $audioF->storeAS($destinationPath,$nmae.'.'.$ext ,['disk' => 'private']);
            $data['path'] = $nmae.'.'.$ext;                        
        }
        unset($data['audio']);
        $data['type'] = 'meditation';
        $data['user_id'] = Auth::user()->id;
        $data['status'] = 'pending';
        foreach($data as $field=>$value){ 
            $md->$field = $value;
        }
        $md->save();
        flash('Successfully updated Content!','success');
        return redirect('meditations/');
    
    }

    public function delete(Meditation $md){
        if($md->user_id != Auth::user()->id || $md->type !='meditation'){
            return back();
        }
        $md->delete();
        Storage::delete('private/audio/'.$md->path);
        flash('Successfully deleted the Content!','success');
        return redirect('meditations/');
    }

}