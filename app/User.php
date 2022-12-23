<?php
namespace App;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use App\Notifications\ValidEmail;
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','type','pic','is_paid','trial_period','login_type','token_for_business','device_token','registration_id','app_name','app_version','device_uid','verify_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function topics(){
        return $this->belongsToMany('\App\Topic','user_topics');
    }

    public function meditationComment(){
        return $this->belongsToMany('\App\Meditation','user_comments')->withPivot('comments')->withPivot('id');
    }
    
    public function meditationRate(){
        return $this->belongsToMany('\App\Meditation','user_ratings')->withPivot('rate')->withPivot('id');
    }

    public function userPreference(){
        return $this->belongsToMany('\App\Meditation','user_preferences')->withPivot('id');
    }

    public function userFollow(){ // ['user_id' => 'user_id'], ['user_id' => 'guide_id']
        return $this->belongsToMany('\App\User', 'guide_followers','user_id','guide_id')->withPivot('id')->withPivot('guide_id');
    }

    public function guideFollower(){ // ['user_id' => 'user_id'], ['user_id' => 'guide_id']
        return $this->belongsToMany('\App\User', 'guide_followers','guide_id','user_id')->withPivot('id')->withPivot('guide_id');
    }

    public function logs()
    {
        return $this->hasMany('App\AppuseLog');
    }

    public function reminder()
    {
        return $this->hasMany('App\UserReminder');
    }

    public function sendApiEmailVerificationNotification()
    {
        $response = $this->notify(new ValidEmail($this)); // my notification
    }
}