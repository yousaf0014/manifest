<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Meditation extends BaseModel
{    
    protected $fillable = [
        'user_id', 'category_id','title','description','path','pic','type','status','schedule','view','posted','is_free'
    ];



    public function meditationRating(){
    	return $this->hasMany(UserRating::class);
    }

    public function commentUser(){
    	return $this->belongsToMany('\App\User','user_comments')->withPivot('comments')->withPivot('id');
    }

    public function preferenceUser(){
        return $this->belongsToMany('\App\User','user_preferences')->withPivot('id');
    }

    public function rateUser(){
    	return $this->belongsToMany('\App\User','user_ratings')->withPivot('rate')->withPivot('id');
    }

    public function category(){
        return $this->belongsTo('App\Category');
    }
}