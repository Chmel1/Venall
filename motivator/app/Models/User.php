<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'balance' => 0,
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

    /*
     * ==========================================
     * СВЯЗИ (RELATIONS) — всё, что создаёт пользователь
     * ==========================================
     */

    // 🔹 Посты (блог)
    public function posts(): HasMany
    {
        return $this->hasMany(\App\Models\Blog\Post::class);
    }

    // 🔹 Комментарии (блог)
    public function comments(): HasMany
    {
        return $this->hasMany(\App\Models\Blog\Comment::class);
    }

    // Лайки(блог)
    public function likes():HasMany{
        return $this->hasMany(\App\Models\Blog\Like::class);
    }

    // 🔹 Задачи (motivator)
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    // 🔹 Награды (motivator)
    public function rewards(): HasMany
    {
        return $this->hasMany(Reward::class);
    }
}