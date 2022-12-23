<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserReminder extends BaseModel
{
	protected $table = 'user_reminders';    

    //
    protected $fillable = [
        'user_id','time','notifications'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
    public static function boot()
    {
        parent::boot();
    }
}