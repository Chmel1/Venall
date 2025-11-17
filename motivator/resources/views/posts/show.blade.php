@extends('layouts.app')

@section('content')
<style>
    .post-actions {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .like-btn {
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
        cursor: pointer;
    }
    .like-count {
        color: var(--text-muted) !important;
        font-size: 0.9rem;
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
</style>

<div class="container py-4">
    <!-- Кнопки действий -->
    <div class="post-actions mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
            &larr; Назад к постам
        </a>
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

        <!-- Лайк -->
        <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline ms-auto">
            @csrf
            <button type="submit" class="like-btn">
                @if($post->isLikedBy(auth()->user()))
                    <i class="fas fa-heart text-danger" style="font-size: 1.4rem;"></i>
                @else
                    <i class="far fa-heart text-secondary" style="font-size: 1.4rem;"></i>
                @endif
            </button>
        </form>
        <span class="like-count">{{ $post->likes()->count() }}</span>
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
        </div>
    </div>

    <!-- Список комментариев -->
    <h5 class="mb-3">Комментарии ({{ $comments->count() }})</h5>

    @if($comments->isEmpty())
        <p class="text-muted">Пока нет комментариев.</p>
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
</div>
@endsection