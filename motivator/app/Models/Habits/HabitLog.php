<?php

namespace App\Models\Habits;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
class HabitLog extends Model
{
    protected $fillable = ['habit_id', 'user_id', 'date'];

    protected $dates = ['date'];

    public function habit()
    {
        return $this->belongsTo(Habit::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
