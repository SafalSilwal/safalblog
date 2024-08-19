<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id'];

    protected static function booted()
    {
        static::creating(function(Post $post) {
            if(auth()->check()){
                $post->user_id = Auth::id();
            }

        });
    }

    public function user(): BelongsTo
    {
        return $this-> belongsTo(User::class);
    }

    
}

