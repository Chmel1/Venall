@extends('layouts.app')

@section('title', 'Редактировать награду')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">✏️ Редактировать награду</h1>

    <div class="card shadow-sm">
        <div class="card-body">
            <form action="{{ route('rewards.update', $reward) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="title" class="form-label">Название награды</label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        class="form-control"
                        value="{{ old('title', $reward->title) }}"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label for="cost" class="form-label">Стоимость (в баллах)</label>
                    <input
                        type="number"
                        id="cost"
                        name="cost"
                        class="form-control"
                        value="{{ old('cost', $reward->cost) }}"
                        min="1"
                        max="1000"
                        required
                    >
                </div>

                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <a href="{{ route('rewards.index') }}" class="btn btn-secondary">Отмена</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection