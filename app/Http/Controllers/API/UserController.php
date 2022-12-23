<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Auth\Events\Verified;
use Validator;
use App\User;
use App\UserSession;
use Illuminate\Validation\Rule;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
class UserController extends BaseController 
{
    use VerifiesEmails;
    

    public function delete(User $user){
        $user->delete();
        return response()->json(['success' => array('message'=>'scuccessfully deleted')], 200);
    }
    public function edit(User $user){
        return response()->json(['success' => $this->__userfields($user)], 200); 
    }

    public function update(Request $request,User $user){    
        $rules = array(
            'first_name' => ['required'],
            'last_name' => ['required'],
            'email' => ['required'],
            'password' => ['nullable','string', 'min:8']
        );
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {            
            return $this->sendError('Validation Error.', $validator->errors());
        } else {
            $data = $request->all();                    
            unset($data['_method']);unset($data['_token']);
            unset($data['mobile_key']);
            foreach($data as $field=>$value){ 
                if($field  != 'password'){
                    $user->$field = $value;
                }else if(!empty($value)){               
                    $user->$field = bcrypt($data['password']);
                }
            }
            $user->type = 'user';
            $user->save();
            return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
        }
    } 

    public function users(){
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
        $success['users'] = $users;
        return $this->sendResponse($success, 200);
    }
    

    /** 
     * login api 
     * 
     * @return \Illuminate\Http\Response 
     */ 

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_key'=> ["required" , "max:44","min:44"],
            'email' => 'required',
            'password' => 'required',            
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->mobile_key;
        $const = "yYgIvy8fK127X9GpSlepkuJmy7c7f7rB7p7Tn08lGzo0";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('mobile_key'=>'The selected mobile key is invalid.'));
        }
        
        if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            $user = Auth::user(); 
            $success = $this->__userfields($user);
            $success['token'] =  $user->createToken('MyApp')-> accessToken;
            return $this->sendResponse($success, 'User login successfully.');
        }
        else{ 
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    /** 
     * Register api for social
     * 
     * @return \Illuminate\Http\Response 
     */
    public function socailRegister(Request $request)
    {
        $input = $request->all();
        $user1 = User::where('token_for_business',$input['token_for_business'])->where('login_type',$input['login_type'])->first();
        if(empty($user1)){
            $validator = Validator::make($request->all(), [
                'name' => "required",
                'login_type'=> ["required",Rule::in(['google', 'facebook']),],
                'token_for_business' => ['required','unique:users'],
                'profile_pic' => 'required',
                'mobile_key'=> ["required" , "max:44","min:44"]
            ]);

            if($validator->fails()){
                return $this->sendError('Validation Error.', $validator->errors());
            }    
        }
        
        $str = $request->mobile_key;
        $const = "yYgIvy8fK127X9GpSlepkuJmy7c7f7rB7p7Tn08lGzo0";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('mobile_key'=>'The selected mobile key is invalid.'));
        }
        if(empty($user1)){
            $user['type'] = 'user';
            $user['pic'] = $input['profile_pic'];
            $user['token_for_business'] = $input['token_for_business'];
            $user['login_type'] = $input['login_type'];
            $name = explode(' ', $input['name']);
            $user['first_name'] =  $name[0];
            unset($name[0]);
            $user['last_name'] =  implode(' ',$name);
            $user1 = User::create($user);           
        }
        $success = $this->__userfields($user);
        $success['token'] =  $user1->createToken('MyApp')->accessToken;
        return $this->sendResponse($success, 200);
    }

    
    function __userfields($user){
        $userArr['id'] = $user->id;
        $userArr['first_name'] = $user->first_name; 
        $userArr['last_name'] = $user->last_name; 
        $userArr['email'] = $user->email; 
        $userArr['pic'] = $user->pic; 
        $userArr['is_paid'] = $user->is_paid;
        $userArr['trial_period'] = $user->trial_period;
        return $userArr;
    }

    /** 
     * Register user device
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function storeDevice(Request $request){
        $validator = Validator::make($request->all(), [
            'device_token'=> ["required" ],
            'registration_id' => 'required',
            'app_name' => 'required',
            'app_version' => 'required',
            'device_uid' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user();
        $user->device_token = $request->device_token;
        $user->registration_id = $request->registration_id;
        $user->app_name = $request->app_name;
        $user->app_version = $request->app_version;
        $user->device_uid = $request->device_uid;
        $user->save();
        return response()->json(['success' => array('message'=>'scuccessfully saved')], 200);
    }
    
    /** 
     * Register api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_key'=> ["required" , "max:44","min:44"],
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users|string',
            'password' => 'required',
            'c_password' => 'required|same:password',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $str = $request->mobile_key;
        $const = "yYgIvy8fK127X9GpSlepkuJmy7c7f7rB7p7Tn08lGzo0";
        if(strcmp($str, $const) !== 0 ){
            return $this->sendError('Validation Error.', array('mobile_key'=>'The selected mobile key is invalid.'));
        }

        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $input['type'] = 'user';
        $input['verify_code'] = Str::random(8);
        $user = User::create($input);
        $user->sendApiEmailVerificationNotification();
        $success = $this->__userfields($user);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return $this->sendResponse($success, 200);
    }


    /** 
     * Update Email api 
     * 
     * @return \Illuminate\Http\Response 
    **/ 
    public function registerEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users|string'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user->email = $request->email;
        $user->verify_code = Str::random(8);
        $user->sendApiEmailVerificationNotification();
        $success = $this->__userfields($user);
        $success['message'] = 'Please confirm yourself by clicking on verify user button sent to you on your email';
        return $this->sendResponse($success, 200);
    }
    

    /** 
     * pic api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function profile_pic() 
    { 
        $validator = Validator::make($request->all(), [
            'pic' => 'required|image|size:2000',
        ]);
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = Auth::user(); 
        $file = $request->file('pic');
        $fileName = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        $path = $file->getRealPath();
        //$file->getSize();
        //$file->getMimeType();
        //Move Uploaded File
        $destinationPath = 'uploads/';
        $file->store($destinationPath, ['disk' => 'public']);
        $user->pic = $file->hashName();
        $user->save();
        return response()->json(['success' => $user], 200); 
    }

    /** 
     * details api 
     * 
     * @return \Illuminate\Http\Response 
     */ 
    public function details() 
    { 
        $user = Auth::user(); 
        return response()->json(['success' => $user], 200); 
    }


    public function verify(Request $request) {
        $user = Auth::user();
        if($user->verify_code == $request->code){
            $date = date("Y-m-d g:i:s");
            $user->email_verified_at = $date;
            $user->save();
            return response()->json(['success' => array('message'=>'Email verified!')], 200);    
        }
        return $this->sendError('Invalid.', ['error'=>'Invalid Code']);       
    }
}