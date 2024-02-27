<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogPostCategory extends Model
{
    use HasFactory;
    protected $fillable=['title','slug','status'];

    public function post(){
        return $this->hasMany('App\Models\BlogPostCategory','post_cat_id','id')->where('status','active');
    }

    public static function getBlogByCategory($slug){
        return BlogPostCategory::with('blogposts')->where('slug',$slug)->first();
    }
}
