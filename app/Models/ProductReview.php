<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=['user_id','product_id','rate','review','status'];

    public function user_info(){
        return $this->hasOne('App\Models\User','id','user_id');
    }

    public static function getAllReview(){
        return ProductReview::with('user_info')->paginate(10);
    }

   
    public static function getAllUserReview($productId){
        // Check if the user is authenticated
        if (auth()->check()) {
            // If authenticated, return the user's review for the specified product
            return ProductReview::where('user_id', auth()->user()->id)
                ->where('product_id', $productId)
                ->with('user_info')
                ->first();
        } else {
            // If not authenticated, you can handle this in your controller or view
            // For example, redirect the user to the login page when they click on an item
            // You can customize this part based on your application's requirements
            return null;
        }
    }
    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

}
