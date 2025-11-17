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

    /* Контейнер для лайка — справа внизу */
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
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>📝 Все посты</h2>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            + Новый пост
        </a>
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
                                <i class="bi bi-person me-1"></i> {{ $post->user->name }}
                            </p>
                            <p class="mb-2">{{ Str::limit($post->content, 120) }}</p>
                            <p class="post-meta mb-0">
                                <i class="bi bi-calendar me-1"></i> {{ $post->created_at->diffForHumans() }}
                            </p>

                            <!-- Контрол лайка — справа внизу -->
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $posts->links() }}
        </div>
    @endif
</div>
@endsection