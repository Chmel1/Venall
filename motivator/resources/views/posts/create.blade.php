@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4">Создать новый пост</h2>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="title" class="form-label">Заголовок</label>
            <input type="text" 
                   class="form-control @error('title') is-invalid @enderror" 
                   id="title" 
                   name="title" 
                   value="{{ old('title') }}" 
                   required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Содержание</label>
            <textarea class="form-control @error('content') is-invalid @enderror" 
                      id="content" 
                      name="content" 
                      rows="5" 
                      required>{{ old('content') }}</textarea>
            @error('content')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">
                Создать пост
            </button>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">
                Отмена
            </a>
        </div>
    </form>
</div>
@endsection