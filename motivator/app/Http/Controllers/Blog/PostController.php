<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Like;
use App\Models\Blog\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{

    //Показывает списоск всех постов
    public function index():View{

        $posts = Post::with('user')->latest()->paginate(10);

        return view('posts.index', compact('posts'));
    }

    //Метод с валидацией и созданием поста у пользователя
    public function store(Request $request): RedirectResponse{
        $validated = $request->validate([
            'title'=>'required|string|min:5|max:52',
            'content'=>'required|string|min:10',
        ]);

        /** @var User $user */
        $user = Auth::user();

        $post = $user->posts()->create($validated);

        return redirect()->route('posts.show', $post)->with('success', 'Пост успешно создан!');
    }
    //Метод для добавления страницы создания поста
    public function create():View{
        return view('posts.create');
    }
//Метод для просмотра поста
    public function show(Post $post){
        

        $comments = $post->comments()->with('user')->oldest()->get();

        return view('posts.show',compact('post', 'comments'));
    }
//Метод для удаления поста
    public function destroy(Post $post){
        if ($post->user_id !== Auth::id()){
            abort(403);
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удален');
    }
//Метод для лайков
    public function like(Post $post){
         $userId = Auth::id();

         //Проверка есть лайки к этому посту от пользователя
         $existingLike = Like::where('user_id', $userId)
                            ->where('post_id', $post->id)
                            ->first();
        if ($existingLike){
            $existingLike->delete();
        }else{
            Like::create([
                'user_id' =>$userId,
                'post_id' => $post->id,
            ]);
        }
        return back();
    }
}
