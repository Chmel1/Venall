@extends('layouts.app')

@section('title', 'Магазин наград')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-1">
        <h1 class="h3 mb-0">
            <i class="bi bi-gift-fill text-warning me-2"></i>Мой магазин наград
        </h1>
        <div class="d-flex align-items-center gap-3">
        <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary text-black px-3 py-2 rounded shadow-sm">
            Перейти к задачам
        </a>
        <div class="bg-primary text-white px-3 py-2 rounded shadow-sm">
            <strong>💎 Баланс:</strong> {{ $balance }}
        </div>
    </div>
    </div>

    <hr class="my-4">

    <!-- Форма добавления награды -->
    <div class="card mb-5 shadow-sm">
        <div class="card-header bg-light">
            <h5 class="mb-0">➕ Добавить новую награду</h5>
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
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-plus-circle me-1"></i>Добавить
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Список наград -->
    @if($rewards->isEmpty())
        <div class="text-center py-5">
            <i class="bi bi-emoji-frown display-4 text-muted mb-3"></i>
            <h4>Нет наград в магазине</h4>
            <p class="text-muted">Добавь первую награду — заслужи свой отдых!</p>
        </div>
    @else
        <div class="row g-4">
            @foreach($rewards as $reward)
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold">{{ $reward->title }}</h5>

                            <div class="mt-auto">
                                <!-- Строка с кнопками -->
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="badge bg-danger fs-6">-{{ $reward->cost }} 💎</span>

                                    <!-- Группа кнопок справа -->
                                    <div class="d-flex gap-1">
                                        <!-- Кнопка "Взять" -->
                                        <form action="{{ route('rewards.use', $reward) }}" method="POST" class="mb-0">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="btn btn-sm {{ $balance >= $reward->cost ? 'btn-success' : 'btn-outline-secondary disabled' }}"
                                                {{ $balance < $reward->cost ? 'disabled' : '' }}
                                                title="{{ $balance < $reward->cost ? 'Недостаточно баллов' : 'Использовать награду' }}"
                                            >
                                                <i class="bi bi-check-circle me-1"></i>Взять
                                            </button>
                                        </form>

                                        <!-- Кнопки управления (только для владельца, но он и есть) -->
                                        <a href="{{ route('rewards.edit', $reward) }}" class="btn btn-sm btn-outline-secondary" title="Изменить">
                                            <i class="bi bi-pencil">Изменить награду</i>
                                        </a>
                                        <form action="{{ route('rewards.destroy', $reward) }}" method="POST" class="d-inline" title="Удалить">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                onclick="return confirm('Вы уверены, что хотите удалить награду?')">
                                                <i class="bi bi-trash">Удалить награду</i>
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