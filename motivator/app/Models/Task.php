<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;



class Task extends Model
{
    protected $fillable = ['user_id', 'title', 'points', 'completed_at'];

    public function casts(): array{
        return[
            'completed_at'=>'datetime',
        ];
    }

    public function user(): BelongsTo{
        return $this->belongsTo(User::class);
    }
}
