@extends('layouts.app')

@section('content')
<style>
    .widget-card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 0.5rem !important;
        padding: 1.25rem !important;
        margin-bottom: 1.5rem !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
    }
    .widget-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--text-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .weather-temp {
        font-size: 2rem;
        font-weight: bold;
        color: var(--primary);
    }
    .currency-rate {
        font-size: 1.2rem;
        color: var(--success);
    }
    .reward-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .reward-item:last-child {
        border-bottom: none;
    }
    .post-item, .comment-item {
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .post-item:last-child, .comment-item:last-child {
        border-bottom: none;
    }
    .post-title {
        font-weight: 600;
        color: var(--primary);
        text-decoration: none;
    }
    .post-title:hover {
        text-decoration: underline;
    }
    .meta {
        font-size: 0.875rem;
        color: var(--text-muted);
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Привет, {{ Auth::user()->name }}!</h2>
        <div class="d-flex align-items-center gap-3">
            <span class="badge bg-primary">💎 {{ $balance }}</span>
            <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">Мотиватор</a>
            <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">Блог</a>
        </div>
    </div>

    <div class="row">
        <!-- Левая колонка -->
        <div class="col-lg-8">
            <!-- Погода -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-cloud-sun"></i> Погода
                </div>
                @if($weather)
                    <div class="d-flex align-items-center">
                        <div>
                            <div class="weather-temp">{{ $weather['main']['temp'] }}°C</div>
                            <div class="meta">{{ $weather['name'] }}, {{ $weather['sys']['country'] }}</div>
                            <div class="mt-1">{{ $weather['weather'][0]['description'] }}</div>
                        </div>
                        <div class="ms-auto">
                            @if(isset($weather['weather'][0]['icon']))
                                <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" alt="Погода">
                            @endif
                        </div>
                    </div>
                @else
                    <p class="text-muted">Не удалось загрузить погоду</p>
                @endif
            </div>

            <!-- Курсы валют -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-currency-dollar"></i> Курсы валют
                </div>
                @if($exchangeRates)
                    <div class="d-flex flex-column gap-2">
                        <div>
                            <span class="me-2">USD/RUB:</span>
                            <span class="currency-rate">{{ number_format($exchangeRates['usd_rub'], 2) }}</span>
                        </div>
                        <div>
                            <span class="me-2">EUR/RUB:</span>
                            <span class="currency-rate">{{ number_format($exchangeRates['eur_rub'], 2) }}</span>
                        </div>
                    </div>
                @else
                    <p class="text-white-muted">Не удалось загрузить курсы</p>
                @endif
            </div>

            <!-- Последние посты -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-journal-text"></i> Последние посты
                </div>
                @if($recentPosts->isNotEmpty())
                    @foreach($recentPosts as $post)
                        <div class="post-item">
                            <a href="{{ route('posts.show', $post) }}" class="post-title">
                                {{ $post->title }}
                            </a>
                            <div class="meta mt-1">
                                {{ $post->user->name }} • {{ $post->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-3">
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">Все посты</a>
                    </div>
                @else
                    <p class="text-muted">Нет постов</p>
                @endif
            </div>

            <!-- Комментарии -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-chat"></i> Последние комментарии
                </div>
                @if($recentComments->isNotEmpty())
                    @foreach($recentComments as $comment)
                        <div class="comment-item">
                            <div><strong>{{ $comment->user->name ?? 'Аноним' }}:</strong> {{ Str::limit($comment->text, 60) }}</div>
                            <div class="meta mt-1">
                                @if($comment->post)
                                    к посту "{{ Str::limit($comment->post->title, 30) }}"
                                @else
                                    <em>к удалённому посту</em>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @else
                    <p class="text-muted">Нет комментариев</p>
                @endif
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-lg-4">
            <!-- Статистика -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-bar-chart"></i> Статистика
                </div>
                <ul class="list-unstyled">
                    <li class="mb-2">✅ Выполнено задач сегодня: <strong>{{ $tasksCompletedToday }}</strong></li>
                    <li class="mb-2">📝 Всего постов: <strong>{{ $recentPosts->count() }}</strong></li>
                    <li class="mb-2">💬 Комментариев: <strong>{{ $recentComments->count() }}</strong></li>
                </ul>
            </div>

            <!-- Награды -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-gift"></i> Доступные награды
                </div>
                @if($rewards->isNotEmpty())
                    @foreach($rewards as $reward)
                        <div class="reward-item">
                            <span>{{ $reward->title }}</span>
                            <span class="badge bg-danger">-{{ $reward->cost }} 💎</span>
                        </div>
                    @endforeach
                    <div class="mt-3">
                        <a href="{{ route('rewards.index') }}" class="btn btn-sm btn-outline-primary">Все награды</a>
                    </div>
                @else
                    <p class="text-muted">Нет наград</p>
                @endif
            </div>

            <!-- Топ лайков -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-heart-fill text-danger"></i> Популярные посты
                </div>
                @if($topLikedPosts->isNotEmpty())
                    <ol class="list-group list-group-numbered">
                        @foreach($topLikedPosts as $post)
                            <li class="list-group-item bg-transparent border-0 p-1">
                                <a href="{{ route('posts.show', $post) }}" class="post-title">
                                    {{ Str::limit($post->title, 40) }}
                                </a>
                                <span class="badge bg-secondary ms-2">{{ $post->likes_count }} ❤️</span>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-muted">Нет лайков</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection