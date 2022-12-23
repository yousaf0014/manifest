<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class UserCategory extends BaseModel
{
    protected $table = 'user_categories';
    //
    protected $fillable = [
        'user_id','category_id'
    ];

    public function user(){
    	return $this->belongsTo(User::class);
    }
    public function Category(){
    	return $this->belongsTo(Category::class);
    }

    public static function boot()
    {
        parent::boot();
    }
}
