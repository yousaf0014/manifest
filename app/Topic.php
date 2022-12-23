<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Topic extends BaseModel
{    
    protected $fillable = [
        'title', 'description'
    ];

    public function user(){
        return $this->belongsToMany('\App\User','user_topics');
    }

    public function categories(){
        return $this->belongsToMany('\App\Category','topic_categories');
    }

    
}