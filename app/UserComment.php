<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserComment extends BaseModel
{
	protected $table = 'user_comments';    

    //
    protected $fillable = [
        'user_id','meditation_id','comments'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
    public function Meditation(){
        return $this->belongsTo(Meditation::class);
    }
    
    public static function boot()
    {
        parent::boot();
    }
}