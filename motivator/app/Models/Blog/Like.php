<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\User;
use App\Models\Blog\Post;

class Like extends Model
{
    protected $fillable = ['user_id', 'post_id'];

    public function posts(): BelongsTo{
        return $this->belongsto( Post::class);
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
