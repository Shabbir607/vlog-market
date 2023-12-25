<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model

{
    use HasFactory;

    protected $fillable = [
    'name',
    'date_of_birth',
    'current_location',
    'gender',
    'nationality',
    'education',
    'career_level',
    'experience',
    'position',
    'salary_expectation',
    'commitment_level',
    'visa_status',
    'record_video',
    'drop_note',
    'cv_path',
    'post_cat_id',
];
   
}
