<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Laravel\Sanctum\HasApiTokens;

use MongoDB\Laravel\Auth\User as Authenticatable;
// use Jenssegers\Mongodb\Auth\User as Authenticatable;

// use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     protected $collection = 'users';
     protected $connection = 'mongodb';
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }


    public function getRole($role)
    {
        return $this->$role;
      
    }
    public function hasRole($role)
    {
        
        $allowedRoles= ['admin', 'user', 'author'];
        return in_array($role, $allowedRoles); 
    }
    public function hasadminRole($role)
    {
        
        $allowedRoles= ['admin'];
        return in_array($role, $allowedRoles); 
    }
    public function hasauthorRole($role)
    {
        
        $allowedRoles= ['admin','author'];
        return in_array($role, $allowedRoles); 
    }
}
   