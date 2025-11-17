@extends('layouts.app')

@section('content')
<style>
    .login-card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
    }
    .login-header {
        background-color: rgba(30, 41, 59, 0.5);
        border-bottom: 1px solid var(--border-color);
    }
    .form-check-input:checked {
        background-color: var(--primary) !important;
        border-color: var(--primary) !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7 col-lg-6">
            <div class="login-card card border-0">
                <div class="login-header card-header text-center">
                    <h4 class="mb-0">{{ __('Авторизация') }}</h4>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">{{ __('Почта') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" 
                                   name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">{{ __('Пароль') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                {{ __('Запомнить меня') }}
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Войти') }}
                            </button>

                            <a href="{{ route('register') }}" class="btn btn-outline-secondary text-center">
                                {{ __('Зарегистрироваться') }}
                            </a>

                            @if (Route::has('password.request'))
                                <div class="text-center mt-2">
                                    <a href="{{ route('password.request') }}" class="text-warning text-decoration-underline small">
                                        {{ __('Забыли пароль?') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection