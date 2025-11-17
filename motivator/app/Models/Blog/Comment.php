<?php

namespace App\Models\Blog;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Comment extends Model
{

    protected $fillable = ['user_id','post_id','text'];

    public function posts():BelongsTo{
        return $this->belongsTo(Post::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    /** @use HasFactory<\Database\Factories\CommentFactory> */
    use HasFactory;
}
