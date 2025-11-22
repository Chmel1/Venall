<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\Comment;
use App\Models\Task;
use App\Models\Reward;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{

    
    public function index()
    {
        // Данные из Motivator — только для авторизованных
        $balance = 0;
        $tasksCompletedToday = 0;
        $rewards = collect();
        
        if (Auth::check()) {
            $balance = Auth::user()->balance ?? 0;
            $tasksCompletedToday = Auth::user()->tasks()
                ->whereDate('completed_at', now()->today())
                ->count();
            $rewards = Auth::user()->rewards()
                ->orderBy('cost')
                ->limit(3)
                ->get();
        }

        // Данные из блога — доступны всем
        $recentPosts = Post::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
        
        $topLikedPosts = Post::withCount('likes')
            ->orderBy('likes_count', 'desc')
            ->limit(3)
            ->get();
        
        $recentComments = Comment::with('user', 'post')
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Внешние данные — доступны всем
        $weather = $this->getWeather();
        $exchangeRates = $this->getExchangeRates();

        return view('dashboard', compact(
            'balance',
            'tasksCompletedToday',
            'rewards',
            'recentPosts',
            'topLikedPosts',
            'recentComments',
            'weather',
            'exchangeRates'
        ));
    }

    private function getWeather()
    {
        return Cache::remember('weather', now()->addMinutes(30), function () {
            $city = 'Moscow';
            $apiKey = config('services.openweather.key');
            if (!$apiKey) return null;

            try {
                $response = Http::withOptions(['verify' => false])
                    ->get("https://api.openweathermap.org/data/2.5/weather", [
                        'q' => $city,
                        'appid' => $apiKey,
                        'units' => 'metric',
                        'lang' => 'ru'
                    ]);
                
                return $response->successful() ? $response->json() : null;
            } catch (\Exception $e) {
                \Log::error('Ошибка загрузки погоды: ' . $e->getMessage());
                return null;
            }
        });
    }

    private function getExchangeRates()
    {
        return Cache::remember('exchange_rates', now()->addHours(1), function () {
            try {
                $response = Http::withOptions(['verify' => false])
                    ->get('https://api.exchangerate-api.com/v4/latest/USD', [
                        'base' => 'USD',
                        'symbols' => 'RUB,EUR'
                    ]);
                
                if (!$response->successful()) return null;
                
                $data = $response->json();
                if (!isset($data['rates']['RUB'])) return null;
                
                return [
                    'usd_rub' => $data['rates']['RUB'],
                    'eur_rub' => $data['rates']['RUB'] / ($data['rates']['EUR'] ?? 1),
                ];
            } catch (\Exception $e) {
                \Log::error('Ошибка загрузки курсов: ' . $e->getMessage());
                return null;
            }
        });
    }
}
