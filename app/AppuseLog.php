<?php
namespace App;
use Illuminate\Database\Eloquent\Model;

class AppuseLog extends BaseModel
{    
	protected $table = 'appuse_logs';
    protected $fillable = [
        'user_id','type','meditation_id','listen_time','stay_time','completed','use_date','category_id'
    ];

    public function user()
    {
        return $this->belongsToMany('App\User');
    }
}