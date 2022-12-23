<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserRating extends BaseModel
{
    protected $table = 'user_ratings';
    //
    protected $fillable = [
        'rate','user_id','meditation_id'
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
