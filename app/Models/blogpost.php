<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class blogpost extends Model
{
    protected $fillable=['title','tags','summary','slug','description','photo','quote','post_cat_id','post_tag_id','added_by','status','is_featured'];


    public function cat_info(){
        return $this->hasOne('App\Models\PostCategory','id','post_cat_id');
    }
    public function tag_info(){
        return $this->hasOne('App\Models\PostTag','id','post_tag_id');
    }

    public function author_info(){
        return $this->hasOne('App\Models\User','id','added_by');
    }
    public static function getAllPost(){
        return blogpost::with(['cat_info','author_info'])->orderBy('id','DESC')->paginate(10);
    }
    // public function get_comments(){
    //     return $this->hasMany('App\Models\PostComment','post_id','id');
    // }
    public static function getPostBySlug($slug){
        return blogpost::with(['tag_info','author_info'])->where('slug',$slug)->where('status','active')->first();
    }

    public function comments(){
        return $this->hasMany(BlogPostComments::class)->whereNull('parent_id')->where('status','active')->with('user_info')->orderBy('id','DESC');
    }
    public function allComments(){
        return $this->hasMany(BlogPostComments::class)->where('status','active');
    }


    // public static function getProductByCat($slug){
    //     // dd($slug);
    //     return Category::with('products')->where('slug',$slug)->first();
    //     // return Product::where('cat_id',$id)->where('child_cat_id',null)->paginate(10);
    // }

    // public static function getBlogByCategory($id){
    //     return Post::where('post_cat_id',$id)->paginate(8);
    // }
    public static function getBlogByTag($slug){
        // dd($slug);
        return blogpost::where('tags',$slug)->paginate(8);
    }

    public static function countActivePost(){
        $data=blogpost::where('status','active')->count();
        if($data){
            return $data;
        }
        return 0;
    }
}
