<?php

namespace App\Http\Controllers\Blog;

use App\Http\Controllers\Controller;
use App\Models\Blog\Like;
use App\Models\Blog\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function __construct()
    {
        // Защищаем методы, требующие авторизации
        $this->middleware('auth')->only(['create', 'store', 'destroy', 'like']);
    }

    public function index(): View
    {
        // Все посты видны и гостям, и авторизованным
        $posts = Post::with('user')->latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|min:5|max:52',
            'content' => 'required|string|min:10',
        ]);

        $post = Auth::user()->posts()->create($validated);
        return redirect()->route('posts.show', $post)->with('success', 'Пост успешно создан!');
    }

    public function create(): View
    {
        return view('posts.create');
    }

    public function show(Post $post): View
    {
        $comments = $post->comments()->with('user')->oldest()->get();
        $canLike = Auth::check(); // Проверяем, авторизован ли пользователь
        return view('posts.show', compact('post', 'comments', 'canLike'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->user_id !== Auth::id()) {
            abort(403);
        }

        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Пост удален');
    }

    public function like(Post $post): RedirectResponse
    {
        $userId = Auth::id();

        // Проверка: ставил ли пользователь лайк ранее
        $existingLike = Like::where('user_id', $userId)
            ->where('post_id', $post->id)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
        } else {
            Like::create([
                'user_id' => $userId,
                'post_id' => $post->id,
            ]);
        }

        return back();
    }
}