<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Comment;
use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function store(Post $post,Request $request){
       

        $validated = $request->validate([
            'text'=>'required|string|min:2',
        ]);

        

        $post->comments()->create([
            'text'=> $validated['text'],
            'user_id' =>Auth::id(),
        ]);

        return back()->with('success', 'Коментарий добавлен!');
    }
}
