@extends('layouts.app')

@section('content')
<style>
    .habit-card {
        transition: transform 0.2s;
        height: 100%;
    }
    .habit-card:hover {
        transform: translateY(-3px);
    }
    .heatmap-day {
        width: 12px;
        height: 12px;
        border-radius: 2px;
        margin: 1px;
    }
    .heatmap-day.completed {
        background-color: #10b981; /* зелёный */
    }
    .heatmap-day.missed {
        background-color: #6b7280; /* серый */
    }
    .current-day {
        border: 2px solid white;
    }
    .streak-badge {
        font-size: 0.85rem;
        padding: 2px 8px;
        border-radius: 10px;
    }
    .fire-streak { background: linear-gradient(45deg, #ff8a00, #da1b60); color: white; }
    .rocket-streak { background: linear-gradient(45deg, #7928ca, #ff0080); color: white; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>🔥 Мои привычки</h2>
        <a href="{{ route('habits.create') }}" class="btn btn-primary">
            + Новая привычка
        </a>
    </div>

    @if($habits->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.5;"></i>
            <h4 class="mt-3">Ещё нет привычек</h4>
            <p>Начните с создания первой привычки — это первый шаг к изменениям!</p>
            <a href="{{ route('habits.create') }}" class="btn btn-outline-primary mt-3">
                Создать привычку
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach($habits as $habit)
                <div class="col-md-6 col-lg-4">
                    <div class="card habit-card border-0">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <h5 class="card-title mb-1">{{ $habit->title }}</h5>
                                    <span class="badge bg-info">+{{ $habit->reward_points }} 💎</span>
                                </div>
                                <div class="dropdown">
                                    <button class="btn btn-sm" type="button" id="habitMenu{{ $habit->id }}" data-bs-toggle="dropdown">
                                        <i class="bi bi-three-dots"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="habitMenu{{ $habit->id }}">
                                        <li><a class="dropdown-item" href="{{ route('habits.edit', $habit) }}">Изменить</a></li>
                                        <li>
                                            <form action="{{ route('habits.destroy', $habit) }}" method="POST" class="dropdown-item">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-link p-0 text-danger" 
                                                        onclick="return confirm('Удалить привычку?')">
                                                    Удалить
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="mt-3">
                                @if($habit->frequency_type === 'daily')
                                    <span class="badge bg-secondary">Ежедневно</span>
                                @elseif($habit->frequency_type === 'weekly')
                                    <span class="badge bg-secondary">По дням недели</span>
                                @else
                                    <span class="badge bg-secondary">Раз в {{ $habit->interval_days }} дней</span>
                                @endif
                            </div>

                            <!-- Heatmap за последние 3 месяца -->
                            <div class="mt-3">
                                <div class="d-flex justify-content-between mb-1">
                                    <small class="text-muted">Прогресс</small>
                                    <small class="text-muted">{{ $habit->current_streak }} дней подряд</small>
                                </div>
                                <div class="d-flex flex-wrap" style="gap: 2px;">
                                    @foreach($habit->heatmap_data as $date)
                                        <div class="heatmap-day completed 
                                            {{ now()->format('Y-m-d') === $date ? 'current-day' : '' }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="mt-4 d-grid gap-2">
                                @if($habit->today_completed)
                                    <button class="btn btn-success disabled">
                                        <i class="bi bi-check-circle me-1"></i> Сделано сегодня
                                    </button>
                                @else
                                    <form action="{{ route('habits.complete', $habit) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-check-lg me-1"></i> Отметить выполнение
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection