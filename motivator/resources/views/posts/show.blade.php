@extends('layouts.app')

@section('content')
<style>
    .post-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .like-btn {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        cursor: pointer;
        font-size: 1.4rem;
    }
    .like-count {
        color: var(--text-muted) !important;
        font-size: 0.9rem;
        min-width: 1.5em;
        text-align: center;
    }
    .comment-item {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 0.5rem !important;
        padding: 1rem !important;
        margin-bottom: 0.75rem !important;
    }
    .comment-author {
        font-weight: 600;
        color: var(--text-color) !important;
    }
    .comment-date {
        color: var(--text-muted) !important;
        font-size: 0.85rem;
    }
    .guest-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.85);
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
    .guest-prompt {
        background-color: rgba(96, 165, 250, 0.1);
        border-left: 3px solid var(--primary);
        padding: 0.75rem;
        margin-top: 1.5rem;
    }
</style>

<div class="container py-4">
    <!-- Кнопки действий -->
    <div class="post-actions mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
            &larr; Назад к постам
        </a>
        
        @auth
            @if(Auth::id() === $post->user_id)
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Вы уверены, что хотите удалить пост?')">
                        Удалить
                    </button>
                </form>
            @endif
        @endauth

        <!-- Лайк -->
        <div class="ms-auto d-flex align-items-center">
            @auth
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
            @else
                <button class="like-btn text-secondary" disabled>
                    <i class="far fa-heart text-secondary"></i>
                </button>
            @endauth
            <span class="like-count ms-1">{{ $post->likes()->count() }}</span>
        </div>
    </div>

    <!-- Карточка поста -->
    <div class="card mb-4 border-0">
        <div class="card-body">
            <h2 class="card-title text-white mb-3">{{ $post->title }}</h2>
            <p class="card-text text-white mb-3">{{ $post->content }}</p>
            <small class="text-muted">
                Автор: {{ $post->user->name ?? 'Аноним' }} •
                Создан: {{ $post->created_at->diffForHumans() }}
            </small>
        </div>
    </div>

    <!-- Форма комментария -->
    <div class="card mb-4 border-0">
        <div class="card-header bg-transparent border-0 pb-0">
            <h5 class="mb-3">Добавить комментарий</h5>
        </div>
        <div class="card-body">
            @auth
                <form action="{{ route('comments.store', $post) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <textarea class="form-control @error('text') is-invalid @enderror"
                                  name="text"
                                  rows="3"
                                  placeholder="Ваш комментарий...">{{ old('text') }}</textarea>
                        @error('text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Отправить</button>
                </form>
            @else
                <div class="guest-overlay"></div>
                <div class="text-center py-4">
                    <div class="text-muted mb-2">
                        <i class="bi bi-chat-square-text" style="font-size: 2rem; opacity: 0.5;"></i>
                    </div>
                    <p class="mb-0">Чтобы оставить комментарий, пожалуйста, <a href="{{ route('login') }}" class="text-primary">войдите</a> или <a href="{{ route('register') }}" class="text-primary">зарегистрируйтесь</a>.</p>
                </div>
            @endauth
        </div>
    </div>

    <!-- Список комментариев -->
    <h5 class="mb-3">Комментарии ({{ $comments->count() }})</h5>

    @if($comments->isEmpty())
        <div class="text-center py-4">
            <i class="bi bi-chat-left-text" style="font-size: 2rem; opacity: 0.5;"></i>
            <p class="mt-2 text-muted">Пока нет комментариев.</p>
            @guest
                <p class="text-muted small mt-2">Войдите, чтобы оставить первый комментарий</p>
            @endguest
        </div>
    @else
        <div>
            @foreach($comments as $comment)
                <div class="comment-item">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="comment-author">{{ $comment->user->name ?? 'Аноним' }}</span>
                        <small class="comment-date">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mb-0">{{ $comment->text }}</p>
                </div>
            @endforeach
        </div>
    @endif

    @guest
        <div class="guest-prompt mt-4">
            <div class="d-flex align-items-start">
                <i class="bi bi-info-circle fs-4 mt-1 me-2 text-primary"></i>
                <div>
                    <p class="mb-0"><strong>Вы можете просматривать все посты и комментарии без регистрации.</strong></p>
                    <p class="mb-0 small">Чтобы ставить лайки, комментировать и создавать свои посты — <a href="{{ route('login') }}" class="text-primary">войдите</a> или <a href="{{ route('register') }}" class="text-primary">зарегистрируйтесь</a>.</p>
                </div>
            </div>
        </div>
    @endguest
</div>
@endsection