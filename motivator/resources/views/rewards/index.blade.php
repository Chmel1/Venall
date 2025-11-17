@extends('layouts.app')

@section('title', 'Магазин наград')

@section('content')
<style>
    /* Цветовая палитра */
    :root {
        --bg-dark: #0f172a;
        --card-bg: #1e293b;
        --border-color: #334155;
        --text-color: #e2e8f0;
        --text-muted: #94a3b8;
        --primary: #60a5fa;
        --success: #4ade80;
        --danger: #f87171;
        --warning: #fbbf24;
    }

    body {
        background-color: var(--bg-dark) !important;
        color: var(--text-color) !important;
    }

    .header-section {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 0.5rem;
    }

    .card, .form-control, .form-select, .input-group-text {
        background-color: var(--card-bg) !important;
        border-color: var(--border-color) !important;
        color: var(--text-color) !important;
    }

    .form-control::placeholder {
        color: var(--text-muted) !important;
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

    .btn-outline-secondary {
        color: var(--text-muted) !important;
        border-color: var(--border-color) !important;
    }

    .btn-outline-light {
        color: var(--text-color) !important;
        border-color: var(--border-color) !important;
    }

    .btn-outline-danger {
        color: var(--danger) !important;
        border-color: var(--danger) !important;
    }

    .badge.bg-danger {
        background-color: var(--danger) !important;
        color: #0f172a !important;
    }

    .balance-badge {
        background: linear-gradient(135deg, #60a5fa, #3b82f6);
        color: white !important;
        font-weight: 600;
    }

    .reward-card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .reward-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.3);
    }

    .empty-state {
        color: var(--text-muted);
    }
    .empty-state i {
        font-size: 3rem;
        opacity: 0.6;
    }

    hr {
        border-color: var(--border-color) !important;
    }
</style>

<div class="container mt-4">
    <!-- Заголовок -->
    <div class="header-section p-3 mb-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div class="d-flex align-items-center mb-2 mb-md-0">
                <i class="bi bi-gift-fill text-warning me-2" style="font-size: 1.5rem;"></i>
                <h1 class="h4 mb-0">Мой магазин наград</h1>
            </div>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary px-3 py-2">
                    <i class="bi bi-arrow-left me-1"></i> К задачам
                </a>
                <div class="balance-badge px-3 py-2 rounded d-flex align-items-center">
                    <i class="me-1"></i>
                    <strong>{{ $balance }} 💎</strong>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-4">

    <!-- Форма добавления награды -->
    <div class="card mb-5 shadow-sm border-0">
        <div class="card-header bg-transparent border-0 pb-0">
            <h5 class="mb-3 text-white">
                <i class="bi bi-plus-circle text-success me-2"></i>
                Добавить новую награду
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('rewards.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-7">
                        <input
                            type="text"
                            name="title"
                            class="form-control"
                            placeholder="Например: 1 час игры, поход в кафе и т.д."
                            required
                        >
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text">💎</span>
                            <input
                                type="number"
                                name="cost"
                                class="form-control"
                                placeholder="Стоимость"
                                min="1"
                                max="9999"
                                required
                            >
                        </div>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-lg me-1"></i> Добавить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Список наград -->
    @if($rewards->isEmpty())
        <div class="text-center py-5 empty-state">
            <i class="bi bi-emoji-frown"></i>
            <h4 class="mt-3">Нет наград в магазине</h4>
            <p>Добавь первую награду — заслужи свой отдых!</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($rewards as $reward)
                <div class="col-md-6 col-lg-4">
                    <div class="reward-card card h-100 border-0 text-white">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $reward->title }}</h5>
                            <div class="mt-auto pt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-danger px-3 py-2">
                                        <i class="me-1"></i> 💎{{ $reward->cost }}
                                    </span>
                                    <div class="d-flex gap-2">
                                        <form action="{{ route('rewards.use', $reward) }}" method="POST" class="mb-0">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $balance >= $reward->cost ? 'btn-success' : 'btn-outline-secondary disabled' }}"
                                                {{ $balance < $reward->cost ? 'disabled' : '' }}
                                                title="{{ $balance < $reward->cost ? 'Недостаточно баллов' : 'Использовать награду' }}"
                                            >
                                                <i class="bi bi-check-circle me-1"></i> Взять
                                            </button>
                                        </form>
                                        <a href="{{ route('rewards.edit', $reward) }}" class="btn btn-sm btn-outline-light" title="Изменить">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <form action="{{ route('rewards.destroy', $reward) }}" method="POST" class="mb-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Удалить награду?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection