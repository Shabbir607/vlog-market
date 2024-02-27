<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{

    use HasFactory; // Use the HasFactory trait
    protected $table = 'marketplaces';
    protected $fillable = ['name','country_id'];


    public function country()
    {
        return $this->belongsTo(Country::class,"country_id");
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getMarketplacesByCountry($countryCode)
    {
        return $this->whereHas('country', function ($query) use ($countryCode) {
            $query->where('code', $countryCode);
        })->get();
    }

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class,"name");
    }
}
