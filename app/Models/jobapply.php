<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class jobapply extends Model
{
    protected $fillable=['user_id','application_id','status','user_name'];

    public function applications(){
        return $this->hasMany(jobApplication::class);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
    public static function getAllApplications()
    {
        return JobApplication::all();
    }
    


}
