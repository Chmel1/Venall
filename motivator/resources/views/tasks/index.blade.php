@extends('layouts.app')

@section('title', 'Мои задачи')

@section('content')
<div class="row">
    <div class="col-md-8">
        <h1>📝 Мои задачи</h1>
        <p class="lead">Баланс: <span class="badge bg-primary">{{ $balance }} 💎</span></p>
        <a href="{{route('rewards.index')}}" class="btn btn-outline-primary text-black px-3 py-2 rounded shadow-sm">Магазин</a>
    </div>
</div>

<hr>

<!-- Форма добавления задачи -->
<form action="{{ route('tasks.store') }}" method="POST" class="mb-4">
    @csrf
    <div class="row g-3">
        <div class="col-md-8">
            <input type="text" name="title" class="form-control" placeholder="Что сделать?" required>
        </div>
        <div class="col-md-2">
            <input type="number" name="points" class="form-control" placeholder="Баллы" min="1" max="1000" required>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-success w-100">Добавить</button>
        </div>
    </div>
</form>

@foreach($tasks as $task)
    <div class="card mb-3 {{ $task->completed_at ? 'bg-light' : '' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $task->title }}</strong>
                    <span class="badge bg-info ms-2">+{{ $task->points }} баллов</span>
                    @if($task->completed_at)
                        <span class="text-success ms-2">✅ Выполнено {{ $task->completed_at->format('d.m H:i') }}</span>
                    @endif
                </div>
                <div>
                    @unless($task->completed_at)
                        <form action="{{ route('tasks.complete', $task) }}" method="POST" style="display:inline;">
                            @csrf @method('PATCH')
                            <button type="submit" class="btn btn-sm btn-outline-primary">Выполнить</button>
                        </form>
                    @endunless
                </div>
            </div>
        </div>
    </div>
@endforeach
@endsection