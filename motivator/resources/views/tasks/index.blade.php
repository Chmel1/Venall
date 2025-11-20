@extends('layouts.app')

@section('title', 'Мои задачи')

@section('content')
<style>
    :root {
        --bg-dark: #0f172a;
        --card-bg: #1e293b;
        --text-color: #e2e8f0;
        --text-muted: #94a3b8;
        --border-color: #334155;
        --primary: #60a5fa;
        --success: #4ade80;
        --info: #7dd3fc;
        --light: #334155;
    }

    body {
        background-color: var(--bg-dark) !important;
        color: var(--text-color) !important;
    }

    .card, .form-control, .btn {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-color) !important;
    }

    .form-control::placeholder {
        color: var(--text-muted) !important;
    }

    .card.bg-light {
        background-color: rgba(30, 41, 59, 0.6) !important;
    }

    .badge.bg-primary {
        background-color: var(--primary) !important;
        color: #0f172a !important;
    }

    .badge.bg-info {
        background-color: var(--info) !important;
        color: #0f172a !important;
    }

    .text-success {
        color: var(--success) !important;
    }

    .btn-outline-primary {
        color: var(--primary) !important;
        border-color: var(--primary) !important;
    }

    .btn-outline-primary:hover {
        background-color: var(--primary) !important;
        color: #0f172a !important;
    }

    .btn-success {
        background-color: var(--success) !important;
        border-color: var(--success) !important;
        color: #0f172a !important;
    }

    .btn-success:hover {
        background-color: #22c55e !important;
    }

    h1, h2, h3, h4, h5, h6 {
        color: var(--text-color);
    }

    hr {
        border-color: var(--border-color);
    }
    .btn-add {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 0.875rem; /* немного уменьшить шрифт */
    }
    @media (max-width: 991px) {
        .btn-add {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem !important;
        }
    }

</style>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1>📝 Мои задачи</h1>
                    <p class="lead">Баланс: <span class="badge bg-primary">{{ $balance }} 💎</span></p>
                </div>
                <a href="{{ route('rewards.index') }}" class="btn btn-outline-primary px-3 py-2">
                    Магазин
                </a>
            </div>

            <hr>

            <!-- Центрированная форма добавления задачи -->

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <form action="{{ route('tasks.store') }}" method="POST" class="p-3 rounded" style="background-color: #1e293b; border: 1px solid #334155;">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-md">
                                <input type="text" name="title" class="form-control" placeholder="Что сделать?" required>
                            </div>
                            <div class="col-12 col-md-auto">
                                <input type="number" name="points" class="form-control w-100" placeholder="Баллы" min="1" max="1000" required>
                            </div>
                            @auth
                            <div class="col-12 col-md-auto">
                                <button type="submit" class="btn btn-success w-100">
                                    <span class="d-none d-md-inline">Добавить</span>
                                    <span class="d-md-none"><i class="bi bi-plus-lg"></i></span>
                                </button>
                            @endauth
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <hr>

            <!-- Список задач -->
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
        </div>
    </div>
</div>
@endsection