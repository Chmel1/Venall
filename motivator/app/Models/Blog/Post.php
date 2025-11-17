<?php

namespace App\Models\Blog;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

use App\Models\User;



class Post extends Model
{

    protected $fillable =['user_id','title','content'];

    public function comments(): HasMany{
        return $this->hasMany(Comment::class);
    }
    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
    public function likes():HasMany{
        return $this->hasMany(Like::class);
    }

    //Метод проверки лайкал ли пользователь пост
   public function isLikedBy($user){
    return $this->likes()->where('user_id',$user->id)->exists();
   }
   //Метод для счетчика лайков
   public function likeCount():int{
    return $this->likes()->count();
   }

    /** @use HasFactory<\Database\Factories\PostFactory> */
    use HasFactory;
}
