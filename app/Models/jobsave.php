<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class jobsave extends Model
{
    protected $table = 'jobsave';
    
    use HasFactory;
    protected $fillable = ['user_id', 'application_id'];

    public function application()
    {
        return $this->belongsTo(JobApplication::class, 'application_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
// Define the relationship with the User model
    
}
