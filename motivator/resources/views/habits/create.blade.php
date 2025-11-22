@extends('layouts.app')

@section('content')
<style>
    .form-card {
        background-color: var(--card-bg) !important;
        border: 1px solid var(--border-color) !important;
        border-radius: 0.5rem;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
    }
    .form-section {
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid var(--border-color);
    }
    .form-section:last-child {
        border-bottom: none;
        margin-bottom: 0;
        padding-bottom: 0;
    }
    .days-grid {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 0.5rem;
    }
    .day-btn {
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
        border-radius: 0.25rem;
        transition: all 0.2s;
    }
    .day-btn.active {
        background-color: var(--primary);
        color: white;
    }
    .day-btn.inactive {
        background-color: rgba(30, 41, 59, 0.5);
        color: var(--text-muted);
    }
    .reward-preview {
        background: linear-gradient(45deg, #60a5fa, #3b82f6);
        color: white;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
    }
    .streak-preview {
        background: rgba(91, 178, 89, 0.15);
        color: #4ade80;
        padding: 0.75rem;
        border-radius: 0.5rem;
        margin-top: 0.5rem;
    }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Создать новую привычку</h2>
        <a href="{{ route('habits.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Назад к привычкам
        </a>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <form action="{{ route('habits.store') }}" method="POST">
                    @csrf
                    
                    <!-- Название привычки -->
                    <div class="form-section">
                        <h5 class="mb-3">Название привычки</h5>
                        <div class="mb-3">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                                   value="{{ old('title') }}" 
                                   placeholder="Например: Пить воду, Читать 10 страниц, Медитация" 
                                   required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-info bg-transparent border-info text-white">
                            <i class="bi bi-info-circle me-2"></i>
                            Название должно быть конкретным и измеримым, чтобы легко отслеживать прогресс
                        </div>
                    </div>

                    <!-- Частота выполнения -->
                    <div class="form-section">
                        <h5 class="mb-3">Частота выполнения</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Как часто нужно выполнять эту привычку?</label>
                            <select class="form-select @error('frequency_type') is-invalid @enderror" name="frequency_type" id="frequencyType" required>
                                <option value="daily" {{ old('frequency_type', 'daily') == 'daily' ? 'selected' : '' }}>Ежедневно</option>
                                <option value="weekly" {{ old('frequency_type') == 'weekly' ? 'selected' : '' }}>По определённым дням недели</option>
                                <option value="custom" {{ old('frequency_type') == 'custom' ? 'selected' : '' }}>Раз в N дней</option>
                            </select>
                            @error('frequency_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Дни недели (только для weekly) -->
                        <div class="mb-3" id="daysOfWeekSection" style="{{ old('frequency_type', 'daily') == 'weekly' ? '' : 'display:none;' }}">
                            <label class="form-label mb-2">Выберите дни недели:</label>
                            <div class="form-check">
                                @php
                                    $weekDays = [
                                        'mon' => 'Пн', 'tue' => 'Вт', 'wed' => 'Ср',
                                        'thu' => 'Чт', 'fri' => 'Пт', 'sat' => 'Сб', 'sun' => 'Вс'
                                    ];
                                @endphp
                                @foreach($weekDays as $value => $label)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" 
                                            name="days_of_week[]" 
                                            value="{{ $value }}" 
                                            id="day_{{ $value }}"
                                            {{ in_array($value, old('days_of_week', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="day_{{ $value }}">{{ $label }}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                            
                            @error('days_of_week')
                                <div class="invalid-feedback d-block mt-2">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Интервал дней (только для custom) -->
                        <div class="mb-3" id="intervalSection" style="{{ old('frequency_type') == 'custom' ? '' : 'display:none;' }}">
                            <label class="form-label">Интервал в днях:</label>
                            <input type="number" name="interval_days" class="form-control @error('interval_days') is-invalid @enderror" 
                                   value="{{ old('interval_days', 1) }}" 
                                   min="1" max="30" required>
                            @error('interval_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text text-muted">
                                Привычка будет напоминаться каждые N дней (максимум 30 дней)
                            </div>
                        </div>
                    </div>

                    <!-- Награда -->
                    <div class="form-section">
                        <h5 class="mb-3">Награда за выполнение</h5>
                        
                        <div class="mb-3">
                            <label class="form-label">Сколько баллов получить за выполнение?</label>
                            <div class="input-group">
                                <input type="number" name="reward_points" class="form-control @error('reward_points') is-invalid @enderror" 
                                       value="{{ old('reward_points', 5) }}" 
                                       min="1" max="100" required>
                                <span class="input-group-text">💎</span>
                            </div>
                            @error('reward_points')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="reward-preview">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Твоя награда</strong>
                                    <div class="small opacity-75">+{{ old('reward_points', 5) }} баллов за каждое выполнение</div>
                                </div>
                                <i class="bi bi-gift fs-3"></i>
                            </div>
                        </div>
                        
                        <div class="streak-preview mt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>Серия из 7 дней подряд</strong>
                                    <div class="small opacity-75">+дополнительные 35 баллов</div>
                                </div>
                                <i class="bi bi-fire fs-3"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Статус -->
                    <div class="form-section">
                        <h5 class="mb-3">Статус привычки</h5>
                        
                        <div class="form-check form-switch">
                            <input type="hidden" name="is_active" value="0"> <!-- Скрытое поле -->
                            <input class="form-check-input" type="checkbox" name="is_active" id="isActive" 
                                value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="isActive">
                                Активировать привычку сразу после создания
                            </label>
                        </div>
                        <div class="form-text text-muted">
                            Если отключить, привычка будет создана, но не будет отображаться в списке активных привычек
                        </div>
                    </div>
                    
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <!-- Кнопки -->
                    <div class="d-flex gap-3 mt-4">
                        <button  type="submit" class="btn btn-primary flex-grow-1">
                            <i class="bi bi-check-circle me-2"></i>Создать привычку
                        </button>
                        <a href="{{ route('habits.index') }}" class="btn btn-outline-secondary">
                            Отмена
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const frequencyType = document.getElementById('frequencyType');
        const daysOfWeekSection = document.getElementById('daysOfWeekSection');
        const intervalSection = document.getElementById('intervalSection');
        
        // Показать/скрыть секции в зависимости от типа частоты
        function toggleSections() {
            daysOfWeekSection.style.display = frequencyType.value === 'weekly' ? 'block' : 'none';
            intervalSection.style.display = frequencyType.value === 'custom' ? 'block' : 'none';
            
            // Очистить скрытые поля
            if (frequencyType.value !== 'weekly') {
                document.querySelectorAll('#daysOfWeekSection input[type="hidden"]').forEach(input => {
                    input.value = '';
                });
            }
            if (frequencyType.value !== 'custom') {
                document.querySelector('[name="interval_days"]').value = '1';
            }
        }
        
        // Переключение дней недели
        function toggleDay(button) {
            const value = button.getAttribute('data-value');
            const input = document.getElementById(`day_${value}`);
            
            if (button.classList.contains('active')) {
                button.classList.replace('active', 'inactive');
                input.value = ''; // ← пустое значение для неактивных дней
            } else {
                button.classList.replace('inactive', 'active');
                input.value = value;
            }
            
            // Фильтрация перед отправкой
            document.querySelector('form').addEventListener('submit', function(e) {
                const daysInputs = document.querySelectorAll('[name="days_of_week[]"]');
                daysInputs.forEach(input => {
                    if (input.value === '') {
                        input.disabled = true; // ← отключаем пустые поля
                    }
                });
            });
        }
        
        // Обработчики событий
        frequencyType.addEventListener('change', toggleSections);
        
        // Инициализация при загрузке
        toggleSections();
    });
</script>
@endpush

@endsection