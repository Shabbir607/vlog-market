<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Import the HasFactory trait
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory; // Use the HasFactory trait

    protected $fillable = ['code', 'name'];

    public function country()
    {
        return $this->belongsTo(Country::class,"name");
    }
}
