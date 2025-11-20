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
        transition: transform 0.2s;
    }
    .widget-card:hover {
        transform: translateY(-2px);
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
    .reward-item, .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.5rem 0;
        border-bottom: 1px solid var(--border-color);
    }
    .reward-item:last-child, .stat-item:last-child {
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
        display: block;
        margin-bottom: 0.25rem;
    }
    .post-title:hover {
        text-decoration: underline;
    }
    .meta {
        font-size: 0.875rem;
        color: var(--text-muted);
    }
    .guest-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.85);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 0.5rem;
        z-index: 10;
    }
    .guest-message {
        text-align: center;
        color: white;
        padding: 1.5rem;
    }
    .guest-message a {
        color: var(--primary);
        text-decoration: underline;
        font-weight: 500;
    }
    .login-prompt {
        background: rgba(96, 165, 250, 0.1);
        border-left: 3px solid var(--primary);
        padding: 0.75rem;
        margin-top: 1rem;
        border-radius: 0.25rem;
    }
    @media (max-width: 768px) {
        .stat-column {
            display: flex;
            flex-wrap: wrap;
        }
        .stat-item {
            width: 50%;
            padding: 0.5rem;
        }
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>
            @auth
                Привет, {{ Auth::user()->name }}!
            @else
                Добро пожаловать в Venall!
            @endauth
        </h2>
        <div class="d-flex align-items-center gap-3">
            @auth
                <span class="badge bg-primary px-3 py-2">💎 {{ $balance }}</span>
                <a href="{{ route('tasks.index') }}" class="btn btn-outline-primary btn-sm">Мотиватор</a>
                <a href="{{ route('posts.index') }}" class="btn btn-outline-primary btn-sm">Блог</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Войти</a>
                <a href="{{ route('register') }}" class="btn btn-outline-primary btn-sm">Регистрация</a>
            @endauth
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
                            <div class="mt-1">{{ ucfirst($weather['weather'][0]['description']) }}</div>
                        </div>
                        <div class="ms-auto">
                            @if(isset($weather['weather'][0]['icon']))
                                <img src="https://openweathermap.org/img/wn/{{ $weather['weather'][0]['icon'] }}@2x.png" 
                                     alt="Погода" class="img-fluid" style="max-height: 80px;">
                            @endif
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-cloud-sun text-muted" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Не удалось загрузить погоду</p>
                    </div>
                @endif
            </div>

            <!-- Курсы валют -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-currency-dollar"></i> Курсы валют
                </div>
                @if($exchangeRates)
                    <div class="stat-column">
                        <div class="stat-item">
                            <span>USD/RUB:</span>
                            <span class="currency-rate">{{ number_format($exchangeRates['usd_rub'], 2) }}</span>
                        </div>
                        <div class="stat-item">
                            <span>EUR/RUB:</span>
                            <span class="currency-rate">{{ number_format($exchangeRates['eur_rub'], 2) }}</span>
                        </div>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-graph-up text-muted" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Не удалось загрузить курсы</p>
                    </div>
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
                            <div class="meta">
                                {{ $post->user->name ?? 'Аноним' }} • {{ $post->created_at->format('d.m.Y') }}
                            </div>
                        </div>
                    @endforeach
                    <div class="mt-3 text-end">
                        <a href="{{ route('posts.index') }}" class="btn btn-sm btn-outline-primary">
                            Все посты <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-journal-text text-muted" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Пока нет постов</p>
                    </div>
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
                            <div>
                                <strong>{{ $comment->user->name ?? 'Аноним' }}:</strong> 
                                {{ Str::limit($comment->text, 60) }}
                            </div>
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
                    <div class="text-center py-3">
                        <i class="bi bi-chat-left-text text-muted" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Пока нет комментариев</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Правая колонка -->
        <div class="col-lg-4">
            @auth
                <!-- Статистика Motivator -->
                <div class="widget-card">
                    <div class="widget-title">
                        <i class="bi bi-bar-chart"></i> Статистика Motivator
                    </div>
                    <div class="stat-column">
                        <div class="stat-item">
                            <span>✅ Задач сегодня:</span>
                            <strong>{{ $tasksCompletedToday }}</strong>
                        </div>
                        <div class="stat-item">
                            <span>💰 Баланс:</span>
                            <strong class="text-primary">{{ $balance }} 💎</strong>
                        </div>
                        <div class="stat-item">
                            <span>🏆 Доступных наград:</span>
                            <strong>{{ $rewards->count() }}</strong>
                        </div>
                    </div>
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
                        <div class="mt-3 text-end">
                            <a href="{{ route('rewards.index') }}" class="btn btn-sm btn-outline-primary">
                                Все награды <i class="bi bi-arrow-right ms-1"></i>
                            </a>
                        </div>
                    @else
                        <div class="text-center py-3">
                            <i class="bi bi-gift text-muted" style="font-size: 2.5rem;"></i>
                            <p class="mt-2 mb-0">Нет доступных наград</p>
                        </div>
                    @endif
                </div>
            @else
                <!-- Призыв к действию для гостей -->
                <div class="widget-card">
                    <div class="widget-title">
                        <i class="bi bi-stars"></i> Возможности Venall
                    </div>
                    <!-- <div class="guest-overlay"></div> -->
                    <div class="guest-message">
                        <i class="bi bi-shield-lock" style="font-size: 3rem; opacity: 0.7;"></i>
                        <h5 class="mt-3 mb-2">Полный доступ к функциям</h5>
                        <p class="mb-3">Зарегистрируйтесь, чтобы получить доступ к Motivator, личному дашборду и другим функциям</p>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="{{ route('register') }}" class="btn btn-primary">Регистрация</a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light">Войти</a>
                        </div>
                    </div>
                </div>
            @endauth

            <!-- Топ лайков -->
            <div class="widget-card">
                <div class="widget-title">
                    <i class="bi bi-heart-fill text-danger"></i> Популярные посты
                </div>
                @if($topLikedPosts->isNotEmpty())
                    <ol class="list-group list-group-numbered">
                        @foreach($topLikedPosts as $post)
                            <li class="list-group-item bg-transparent border-0 p-2">
                                <a href="{{ route('posts.show', $post) }}" class="post-title">
                                    {{ Str::limit($post->title, 40) }}
                                </a>
                                <div class="d-flex justify-content-between align-items-center mt-1">
                                    <span class="meta">{{ $post->likes_count }} ❤️</span>
                                    <span class="badge bg-secondary">{{ $post->likes_count }}</span>
                                </div>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-heart text-muted" style="font-size: 2.5rem;"></i>
                        <p class="mt-2 mb-0">Пока нет популярных постов</p>
                    </div>
                @endif
            </div>

            @guest
                <div class="login-prompt mt-4">
                    <div class="d-flex">
                        <i class="bi bi-info-circle fs-4 mt-1 me-2 text-primary"></i>
                        <div>
                            <p class="mb-0"><strong>Вы можете просматривать посты, погоду и курсы валют без регистрации.</strong></p>
                            <p class="mb-0 small mt-1">Чтобы получить доступ к Motivator, личному кабинету и другим функциям — <a href="{{ route('login') }}" class="text-primary">войдите</a> или <a href="{{ route('register') }}" class="text-primary">зарегистрируйтесь</a>.</p>
                        </div>
                    </div>
                </div>
            @endguest
        </div>
    </div>
</div>
@endsection