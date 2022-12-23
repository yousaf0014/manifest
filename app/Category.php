<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class Category extends BaseModel
{    
    protected $fillable = [
        'title', 'description'
    ];

    public function topics(){
        return $this->belongsToMany('\App\Topic','topic_categories');
    }
}