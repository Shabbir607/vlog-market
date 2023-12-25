<?php
 
namespace App\Models;
 
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
 
class role extends Model
{
    use HasFactory;
    protected $table = 'roles';
 
    /**
     * User's relation with Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany("App\User");
    }
}