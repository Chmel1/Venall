@extends('layouts.app')

@section('content')
<style>
    :root {
        --bg-dark: #0f172a; /* темно-синий фон (slate-900) */
        --card-bg: #1e293b; /* карточка (slate-800) */
        --text-color: #e2e8f0; /* светлый текст (slate-200) */
        --text-muted: #94a3b8; /* приглушённый текст (slate-400) */
        --border-color: #334155; /* граница (slate-600) */
        --primary: #60a5fa; /* синий акцент (blue-400) */
        --success: #4ade80; /* зелёный (emerald-400) */
        --info: #7dd3fc; /* голубой (sky-400) */
        --secondary: #94a3b8; /* серый (slate-400) */
    }

    body {
        background-color: var(--bg-dark) !important;
        color: var(--text-color) !important;
    }

    .card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        color: var(--text-color) !important;
    }

    .card-title {
        color: var(--text-color) !important;
    }

    .card-text {
        color: var(--text-muted) !important;
    }

    .text-muted {
        color: var(--text-muted) !important;
    }

    .badge.bg-primary {
        background-color: var(--primary) !important;
        color: #0f172a !important;
    }

    .badge.bg-success {
        background-color: var(--success) !important;
        color: #0f172a !important;
    }

    .badge.bg-info {
        background-color: var(--info) !important;
        color: #0f172a !important;
    }

    .badge.bg-secondary {
        background-color: var(--secondary) !important;
        color: #0f172a !important;
    }

    .alert-success {
        background-color: rgba(74, 222, 128, 0.2) !important;
        border-color: var(--success) !important;
        color: var(--text-color) !important;
    }

    .btn-close {
        filter: invert(1) grayscale(100%) brightness(200%);
    }

    .hover-shadow:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.3) !important;
        transform: translateY(-2px);
        transition: all 0.2s ease;
    }

    h2 {
        color: var(--text-color);
    }
</style>

<div class="container py-4">
    {{-- Flash-сообщения --}}
    @if (session('status') || session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('status') ?? session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row justify-content-center">
        <div class="col-md-10">
            <h2 class="mb-4 text-center">Мои проекты</h2>

            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                {{-- Карточка: Задачи --}}
                <div class="col">
                    <a href="{{ route('tasks.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">✅ Мотиватор</h5>
                                <p class="card-text flex-grow-1">
                                    Управление задачами, прогрессом и наградами.
                                </p>
                                <div class="mt-auto">
                                    <span class="badge bg-primary">Задачи</span>
                                    <span class="badge bg-success">Награды</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Карточка: Блог --}}
                <div class="col">
                    <a href="{{ route('posts.index') }}" class="text-decoration-none">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">📝 Блог</h5>
                                <p class="card-text flex-grow-1">
                                    Пишите посты, добавляйте комментарии.
                                </p>
                                <div class="mt-auto">
                                    <span class="badge bg-info">Посты</span>
                                    <span class="badge bg-secondary">Комментарии</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Карточка: Скоро --}}
                <div class="col">
                    <a href="#" class="text-decoration-none disabled">
                        <div class="card h-100 shadow-sm">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">🚀 Новый проект</h5>
                                <p class="card-text flex-grow-1">
                                    Скоро будет добавлено.
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection