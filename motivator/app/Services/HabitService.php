<?php

namespace App\Services;

use App\Models\Habits\Habit;
use App\Models\HabitLog;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class HabitService{
    public function getHeatmapData(Habit $habit): array{
        $logs = $habit->logs()->whereBetween('date', [now()->startOfMonth()->subMonths(2), 
                                                    now()->endOfMonth()
                                                    ])->pluck('date')->toArray();
        return $logs;
    }

    public function getCurrentStreak(Habit $habit): int{
        $logs = $habit->logs()
            ->orderBy('date', 'desc')
            ->pluc('date')
            ->map(fn($date) => Carbon::parse($date))
            ->toArray();

            $currentDate = now()->startOfDay();
            $streak = 0;

            foreach ($logs as $logDate){
                if($currentDate->isSameDay($logDate)){
                    $streak++;
                    $currentDate = $currentDate->subDay();
                }else{
                    break;
                }
            }
            return $streak;
    }
    
    public function getAchivments(Habit $habit): array{
        $achivments = [];
        $currentStreak = $this->getCurrentStreak($habit);
        $completionRate = $this->getCompletionRate($habit);

        if($currentStreak >= 7){
            $achivments[] = [
                'name' => 'Огонь!',
                'description' => '7 дней подряд',
                'icon' => 'fire'
            ];
        }
        if ($currentStreak >= 30) {
            $achievements[] = [
                'name' => 'Марафон',
                'description' => '30 дней без пропусков',
                'icon' => 'rocket'
            ];
        }

        if ($completionRate >= 0.8) {
            $achievements[] = [
                'name' => 'Стабильность',
                'description' => '80% выполнения за месяц',
                'icon' => 'chart-line'
            ];
        }
        return $achivments;
    }

    public function getCompletionRate(Habit $habit):float{
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        $totalDays = $startOfMonth->diffInDays($endOfMonth) + 1;
        $completedDays = $habit->logs()
                    ->whereBetween('date', [$startOfMonth, $endOfMonth])
                    ->count();

        return $totalDays > 0 ? $completedDays / $totalDays : 0;
    }
}


?>