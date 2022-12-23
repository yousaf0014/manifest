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
        $this->middleware(array('auth','guide'));
    }
    
    
    /** 
     * profile
     * 
     * @return \Illuminate\Http\Response 
     */
    public function profile() 
    {
        $user = Auth::user();
        return View('Guide.Profile.view',compact('user'));
    }



    public function followers(Request $request)
    {
        \DB::enableQueryLog(); // Enable query log
        $userObj = Auth::user();        
        $userID = Auth::user()->id;
        $userObj1 = $userObj->guideFollower();
        $keyword = '';
        if(isset($request->keyword)){
            $keyword = $request->keyword;
            $userObj1 = $userObj1->where(function($q) use ($keyword){
                $q->where('name','like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%');
            });
        }        
        $users = $userObj1->paginate(20);
        return view('Guide.Users.followers',compact('users','keyword'));
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
        return redirect('profile/');
    }
}