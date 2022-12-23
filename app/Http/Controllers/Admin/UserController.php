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
use Auth;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
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
        $userObj = new User;        
        $userObj = $userObj->where('type','guide');
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $userObj = $userObj->where(function($q) use ($keyword){
                $q->where('name','like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }        
        $users = $userObj->paginate(20);
        return view('Admin.Users.index',compact('users','keyword'));
    }

    public function appusers(Request $request)
    {
        $userObj = new User;        
        $userObj = $userObj->where('type','user');
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $userObj = $userObj->where(function($q) use ($keyword){
                $q->where('name','like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }        
        $users = $userObj->paginate(20);
        return view('Admin.Users.appusers',compact('users','keyword'));
    }


    public function create(){        
        return View('Admin.Users.create');
    }

    public function store(Request $request){
        ini_set('memory_limit','16M');
        $rules = array(
            'pic' => 'nullable|image|max:2000',
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();
            $userObj = new User;
            if(!empty($request->file('pic'))){
                $file = $request->file('pic');
                $fileName = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $path = $file->getRealPath();
                $destinationPath = 'uploads/';
                $file->store($destinationPath, ['disk' => 'public']);
                $data['pic'] = $file->hashName();
            }else{
                unset($data['pic']);
            }            
            $data['type'] = 'guide';
            $data['password'] = bcrypt($data['password']);
            $userObj->create($data);
            flash('Successfully Saved.','success');
            return redirect('users/'); 
        }
    }

    public function show(User $user){ 
        return View('Admin.Users.show',compact('user'));   
    }

    public function showAppUser(User $user){ 
        return View('Admin.Users.appusershow',compact('user'));   
    }

    public function edit(User $user){
        return View('Admin.Users.edit',compact('user'));
    }

    public function update(Request $request,User $user){    
        $rules = array(
            'pic' => 'nullable|image|max:2000',
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable','string', 'min:8', 'confirmed']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        } else {
            $data = $request->all();                    
            unset($data['_method']);unset($data['_token']);  unset($data['password_confirmation']); 
            
            if(!empty($request->file('pic'))){
                $file = $request->file('pic');
                $fileName = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $path = $file->getRealPath();
                $destinationPath = 'uploads/';
                $file->store($destinationPath, ['disk' => 'public']);                
                $data['pic'] = $file->hashName();
            }else{
                unset($data['pic']);
            }

            foreach($data as $field=>$value){ 
                if($field  != 'password'){
                    $user->$field = $value;
                }else if(!empty($value)){               
                    $user->$field = bcrypt($data['password']);
                }
            }
            $user->type = 'guide';
            $user->save();
            flash('Successfully updated Content!','success');
            return redirect('users/');
        }
    }

    public function delete(User $user){
        $user->delete();
        flash('Successfully deleted the Content!','success');
        return redirect('users/');
    }

    /** 
     * profile
     * 
     * @return \Illuminate\Http\Response 
     */
    public function profile() 
    {
        $user = Auth::user();
        return View('Admin.Profile.view',compact('user'));
    }

    /** 
     * Profile pic
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function updateprofile(Request $request) 
    { 
        $user = Auth::user();
        $rules = array(
            'pic' => 'nullable|image|max:2000',
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable','string', 'min:8', 'confirmed']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return Redirect::back()->withInput($request->all())->withErrors($validator);
        }
        $data = $request->all();                    
        unset($data['_method']);unset($data['_token']);  unset($data['password_confirmation']);  
        if($request->file('pic')){
            $file = $request->file('pic');
            $destinationPath = 'uploads/';
            $file->store($destinationPath, ['disk' => 'public']);                
            $data['pic'] = $file->hashName();
        }
        
        foreach($data as $field=>$value){ 
            if($field  != 'password'){
                $user->$field = $value;
            }else if(!empty($value)){               
                $user->$field = bcrypt($data['password']);
            }
        }
        $user->save();
        flash('Successfully deleted the Content!','success');
        return redirect('adminprofile/');
    }
}