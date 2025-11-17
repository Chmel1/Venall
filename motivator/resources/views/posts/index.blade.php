@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('posts.create') }}" class="btn btn-primary">
            + Новый пост
        </a>

        
    </div>

    @if ($posts->isEmpty())
        <p class="text-muted">Список постов пока пуст</p>
    @else
        <div class="row row-cols-1 g-4">
            @foreach($posts as $post)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('posts.show', $post) }}" class="text-decoration-none text-primary">{{ $post->title }}</a>
                        </h5>
                        <p class="text-muted">Автор: {{ $post->user->name }}</p>
                        <p>{{ Str::limit($post->content, 100) }}</p>
                         <small class="text-muted">
                                Создан: {{ $post->created_at->format('d.m.Y H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach
           
        </div>
    @endif
@endsection