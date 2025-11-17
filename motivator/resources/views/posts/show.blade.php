@extends('layouts.app')

@section('content')
<div class="container">
    <div class="mb-4">
        <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">
            &larr; Назад к постам
        </a>
        @if(Auth::id() === $post->user_id)
        <form action="{{ route('posts.destroy',  $post) }}" method="post" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Вы уверенны, что хотите удалить пост?')">Удалить</button>
        </form>
        @endif
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h2 class="card-title">{{ $post->title }}</h2>
            <p class="card-text">{{ $post->content }}</p>
            <small class="text-muted">
                Автор: {{ $post->user->name ?? 'Аноним' }} •
                Создан: {{ $post->created_at->diffForHumans() }}
            </small>
            
        

            {{-- Форма лайка --}}
            <form action="{{ route('posts.like', $post) }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm p-0 border-0 bg-transparent">
                    @if($post->isLikedBy(auth()->user()))
                        <i class="fas fa-heart text-danger" style="font-size: 1.2rem;"></i>
                    @else
                        <i class="far fa-heart text-secondary" style="font-size: 1.2rem;"></i>
                    @endif
                </button>
            </form>
             <span class="text-muted me-2">
                {{ $post->likes()->count() }}
            </span>
        </div>
    </div>

    <!-- Форма комментария -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Добавить комментарий</h5>
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
    <h5>Комментарии ({{ $comments->count() }})</h5>

    @if($comments->isEmpty())
        <p class="text-muted">Пока нет комментариев.</p>
    @else
        <div class="list-group">
            @foreach($comments as $comment)
                <div class="list-group-item">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $comment->user->name ?? 'Аноним' }}</strong>
                        <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                    </div>
                    <p class="mt-2 mb-0">{{ $comment->text }}</p>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection