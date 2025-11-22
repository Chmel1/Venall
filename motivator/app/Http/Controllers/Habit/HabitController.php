<?php

namespace App\Http\Controllers\Habit;

use App\Models\Habits\Habit;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\HabitService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class HabitController extends Controller
{
    protected $habitService;

    public function __construct(HabitService $habitService)
    {
        $this->habitService = $habitService;
        $this->middleware('auth');
    }
    //Показать список всех привычек пользователя
    public function index()
    {
        $user = Auth::user();
        $habits = $user->habits()->with('logs')->get();

        //Добавление вычисляемых полей к каждой привычке
        $habits->each(function($habit){
            $habit->current_streak = $this->habitService->getCurrentStreak($habit);
            $habit->heatmap_data = $this->habitService->getHeatmapData($habit);
            $habit->today_completed = $habit->logs->contains('date', today()->format('Y-m-d'));
        });

        return view('habits.index',compact('habits'));
    }

    /**
     * Show the form for creating a new resource.
     * Показать форму создания новой привычки
     */
    public function create()
    {
        return view('habits.create');
    }

    /**
     * Store a newly created resource in storage.
     * Сохранить новую привычку
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' =>'required|string|max:255',
            'frequency_type' => ['required', Rule::in(['daily', 'weekly', 'custom'])],
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'nullable|in:mon,tue,wed,thu,fri,sat,sun',
            'interval_days' => 'required_if:frequency_type,custom|integer|min:1|max:30',
            'reward_points' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        $selectedDays = array_filter($validated['days_of_week'] ?? [], function($day) {
            return !empty($day);
        });

        $habitData = [
            'title' => $validated['title'],
            'frequency_type' => $validated['frequency_type'],
            'interval_days' => $validated['frequency_type'] === 'custom' ? $validated['interval_days'] : 1,
            'reward_points' => $validated['reward_points'],
            'is_active' => $validated['is_active'] ?? true
        ];
    
        // Добавляем days_of_week ТОЛЬКО для weekly
        if ($validated['frequency_type'] === 'weekly') {
            $habitData['days_of_week'] = $selectedDays ? json_encode(array_values($selectedDays)) : null;
        } else {
            $habitData['days_of_week'] = null;
        }
    
        $habit = Auth::user()->habits()->create($habitData);
    
        return redirect()->route('habits.index')
            ->with('success', 'Привычка успешно создана!');
    }

    /**
     * Display the specified resource.
     * 
     */
    public function show(Habit $habit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * Показать форму редактирования
     */
    public function edit(Habit $habit)
    {
        if($habit->user_id !== Auth::id()){
            abort(403);
        }

        return view('habits.edit', compact('habit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Habit $habit)
    {
        if($habit->user_id !== Auth::id()){
            abort(403);
        }

        $validated = $request->validate([
            'title' =>'required|string|max:255',
            'frequency_type' => ['required', Rule::in(['daily', 'weekly', 'custom'])],
            'days_of_week' => 'nullable|array',
            'days_of_week.*' => 'nullable|in:mon,tue,wed,thu,fri,sat,sun',
            'interval_days' => 'required_if:frequency_type,custom|integer|min:1|max:30',
            'reward_points' => 'required|integer|min:1|max:100',
            'is_active' => 'boolean'
        ]);

        $habit->update([
            'title' => $validated['title'],
            'frequency_type' => $validated['frequency_type'],
            'days_of_week' => isset($validated['days_of_week']) ? json_encode($validated['days_of_week']) : null,
            'interval_days' => $validated['frequency_type'] === 'custom' ? $validated['interval_days'] : 1,
            'reward_points' => $validated['reward_points'],
            'is_active' => $validated['is_active'] ?? $habit->is_active
        ]);

        return redirect()->route('habits.index')
            ->with('success','Привычка успешно обновлена!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        if($habit->user_id !== Auth::id()){
            abort(403);
        }

        $habit->delete();

        return redirect()->route('habits.index')
            ->with('success', 'Привычка успешно удалена!');
    }
}
