<?php

namespace App\Http\Controllers\Habit;

use App\Http\Controllers\Controller;
use App\Models\Habits\Habit;
use App\Models\HabitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class HabitLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Отметить выполнение привычки
     */
    public function store(Request $request, Habit $habit)
    {
        // Проверка, что привычка принадлежит текущему пользователю
        if ($habit->user_id !== Auth::id()) {
            abort(403, 'Эта привычка вам не принадлежит');
        }

        // Проверка, не отмечена ли привычка уже сегодня
        if ($habit->logs()->where('date', today()->format('Y-m-d'))->exists()) {
            return back()->with('warning', 'Вы уже отмечали выполнение этой привычки сегодня');
        }

        // Создание записи о выполнении
        $habitLog = $habit->logs()->create([
            'user_id' => Auth::id(),
            'date' => today()->format('Y-m-d')
        ]);

        // Начисление баллов за выполнение привычки
        $user = Auth::user();
        $user->increment('balance', $habit->reward_points);

        return back()->with('success', "Отлично! +{$habit->reward_points} 💎 за выполнение привычки!");
    }
}