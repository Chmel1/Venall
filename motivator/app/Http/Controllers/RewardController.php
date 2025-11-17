<?php

namespace App\Http\Controllers;

use App\Models\Reward;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RewardController extends Controller
{
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        $rewards = $user->rewards()->latest()->get();
        $balance = $user->balance;

        return view('rewards.index', compact('rewards', 'balance'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cost' => 'required|integer|min:1|max:1000',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $user->rewards()->create([
            'title' => $request->title,
            'cost' => $request->cost,
        ]);

        return redirect()->route('rewards.index')->with('success', 'Награда добавлена!');
    }

    //Функция использования награды
    public function use(Reward $reward)
    {
        if ($reward->user_id !== Auth::id()) {
            abort(403);
        }

        /** @var User $user */
        $user = Auth::user();

        if ($user->balance < $reward->cost) {
            return back()->withErrors('Недостаточно баллов!');
        }

        $user->decrement('balance', $reward->cost);

        return back()->with('success', "🎉 {$reward->title} использовано! Потрачено {$reward->cost} баллов.");
    }

    public function edit(Reward $reward){
        if($reward->user_id !== Auth::id()){
            abort(403);
        }

        return view('rewards.edit',compact('reward'));
    }
    
    //Функция обнавления награды
    public function update(Request $request, Reward $reward){
        if($reward->user_id !== Auth::id()){
            abort(403);
        }

        $request->validate([
            'title'=>'required|string|max:255',
            'cost'=>'required|integer|min:1|max:1000',
        ]);

        $reward->update([
            'title'=>$request->title,
            'cost'=>$request->cost,
        ]);

        return redirect()->route('rewards.index')->with('success', 'Награда обнавлена!');
    }

    //Функция удаления награды
    public function destroy(Reward $reward){
        if($reward->user_id !== Auth::id()){
            abort(403);
        }

        $reward->delete();

        return redirect()->route('rewards.index')->with('success', 'Награда удалена.');
    }

    
}