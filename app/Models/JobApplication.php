<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobApplication extends Model

{
    use HasFactory;

    protected $fillable = [
    'name',
    'user_id',
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
    'status',
    'application_number',
    
];
public function user()
{
return $this->belongsTo(User::class);
}
public function application()
{
    return $this->belongsTo(JobApplication::class, 'application_number');
}
}
