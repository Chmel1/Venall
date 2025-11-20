@extends('layouts.app')

@section('content')
<style>
    .post-card {
        transition: transform 0.2s, box-shadow 0.2s;
        height: 100%;
        position: relative;
    }
    .post-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }
    .btn-primary {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
        color: #0f172a !important;
    }
    .btn-primary:hover {
        background-color: #3b82f6 !important;
    }
    .post-meta {
        color: var(--text-muted) !important;
        font-size: 0.875rem;
    }

    .like-control {
        position: absolute;
        bottom: 1rem;
        right: 1rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .like-btn {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        cursor: pointer;
        font-size: 1.3rem;
    }
    .like-count {
        color: var(--text-muted) !important;
        font-size: 0.85rem;
        min-width: 1.2em;
        text-align: center;
    }
    .guest-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        z-index: 10;
    }
    .guest-message {
        text-align: center;
        color: white;
        padding: 1rem;
    }
    .guest-message a {
        color: var(--primary);
        text-decoration: underline;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📝 Все посты</h2>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-primary">
                + Новый пост
            </a>
        @endauth
    </div>

    @if ($posts->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-journal-text" style="font-size: 3rem; opacity: 0.5;"></i>
            <p class="mt-3">Список постов пока пуст</p>
        </div>
    @else
        <div class="row row-cols-1 g-4">
            @foreach($posts as $post)
                <div class="col">
                    <div class="post-card card border-0">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-primary">
                                    {{ $post->title }}
                                </a>
                            </h5>
                            <p class="post-meta mb-1">
                                <i class="bi bi-person me-1"></i> 
                                {{ $post->user->name ?? 'Аноним' }}
                            </p>
                            <p class="mb-2">{{ Str::limit($post->content, 120) }}</p>
                            <p class="post-meta mb-0">
                                <i class="bi bi-calendar me-1"></i> 
                                {{ $post->created_at->diffForHumans() }}
                            </p>

                            <!-- Контрол лайка — только для авторизованных -->
                            @auth
                                <div class="like-control">
                                    <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="like-btn" title="{{ $post->isLikedBy(auth()->user()) ? 'Убрать лайк' : 'Поставить лайк' }}">
                                            @if($post->isLikedBy(auth()->user()))
                                                <i class="fas fa-heart text-danger"></i>
                                            @else
                                                <i class="far fa-heart text-secondary"></i>
                                            @endif
                                        </button>
                                    </form>
                                    <span class="like-count">{{ $post->likes()->count() }}</span>
                                </div>
                            @else
                                <div class="like-control">
                                    <button class="like-btn text-secondary" disabled>
                                        <i class="far fa-heart text-secondary"></i>
                                    </button>
                                    <span class="like-count">{{ $post->likes()->count() }}</span>
                                </div>
                            @endauth
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif

    @unless(auth()->check())
        <div class="alert alert-info mt-4">
            <i class="bi bi-info-circle me-2"></i>
            Чтобы создавать посты, ставить лайки и комментировать — 
            <a href="{{ route('login') }}" class="text-primary">войдите</a> 
            или 
            <a href="{{ route('register') }}" class="text-primary">зарегистрируйтесь</a>.
        </div>
    @endunless
</div>
@endsection