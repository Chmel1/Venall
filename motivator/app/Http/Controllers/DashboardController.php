<?php

namespace App\Http\Controllers;

use App\Models\Blog\Post;
use App\Models\Blog\Comment;
use App\Models\Task;
use App\Models\Reward;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;


class DashboardController extends Controller
{
   public function index(){
    //Данные из Motivator
    $balance = Auth::user()->balance ?? 0;
    $tasksCompletedToday = Auth::user()->tasks()
        ->whereDate('completed_at', now()->today())
        ->count();
    $rewards = Auth::user()->rewards()
        ->orderBy('cost')
        ->limit(value: 3)
        ->get();

    //Данные из блога
    $recentPosts = Post::with('user') //Последние 3 поста
        ->orderBy('created_at', 'desc')
        ->limit(3)
        ->get();
    $topLikedPosts = Post::withCount('likes')//Последние 3 самых залайконых поста
        ->orderBy('likes_count','desc')
        ->limit(3)
        ->get();
    $recentComments = Comment::with('user','post') //Последние 3 комментария
        ->orderBy('created_at','desc')
        ->limit(3)
        ->get();

    //Внешние данные
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

   public function getWeather(){
    return Cache::remember('weather', now()->addMinute(30),function(){
        $city = 'Moscow';
        $apiKey = config('services.openweather.key');
        if (!$apiKey) return null;

        try{
            $response =Http::withOptions(['verify' => false])->get("https://api.openweathermap.org/data/2.5/weather",[
                'q' => $city,
                'appid' => $apiKey,
                'units' => 'metric',
                'lang' => 'ru'
            ]);
            return $response->successful() ? $response->json() : null;
        }catch(\Exception $e){
            return null;
        }
    });
   }

   public function getExchangeRates(){
    return Cache::remember('exchange_rates', now()->addHour(1), function(){
        try{
            $response = Http::withOptions(['verify' => false])->get('https://api.exchangerate-api.com/v4/latest/USD');
            if(!$response->successful()) return null;

            $data = $response->json();
            return[
              'usd_rub' => $data['rates']['RUB'] ?? null,
              'eur_rub' => ($data['rates']['RUB'] ?? 0) / ($data['rates']['EUR'] ?? 1),  
            ];
        }catch(\Exception $e){
            return null;
        }
    });
   }
}