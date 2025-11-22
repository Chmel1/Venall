<?php

namespace App\Models\Habits;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Habit extends Model
{
    protected $fillable = ['title', 'frequency_type', 
                            'days_of_week', 'interval_days', 
                            'reward_points', 'is_active', 'user_id'];
    protected $casts = [
        'days_of_week' => 'array',
        'is_active' => 'boolean'
    ];
    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }

    /** @use HasFactory<\Database\Factories\HabbitFactory> */
    use HasFactory;
}
