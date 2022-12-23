<?php
namespace App;
use Illuminate\Database\Eloquent\Model;
class GuideFollower BaseModel 
{
    protected $fillable = [
        'guide_id','user_id'
    ];
}