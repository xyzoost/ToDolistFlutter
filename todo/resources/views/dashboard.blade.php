@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Dashboard Overview</h1>
            <p class="text-gray-500 mt-1">Ringkasan aktivitas dan tugas Anda</p>
        </div>
        <div class="bg-white px-4 py-2 rounded-lg shadow-sm border border-gray-200 flex items-center">
            <i class="fas fa-calendar-alt text-indigo-500 mr-2"></i>
            <span id="current-date" class="font-medium text-gray-700">{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Tugas</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['total'] }}</h3>
                    <p class="text-gray-400 text-xs mt-1">Semua tugas Anda</p>
                </div>
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-tasks text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['completed'] }}</h3>
                    <p class="text-gray-400 text-xs mt-1">{{ $stats['total'] > 0 ? round(($stats['completed']/$stats['total'])*100) : 0 }}% dari total</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Belum Selesai</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['pending'] }}</h3>
                    <p class="text-gray-400 text-xs mt-1">{{ $stats['total'] > 0 ? round(($stats['pending']/$stats['total'])*100) : 0 }}% dari total</p>
                </div>
                <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Overdue Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Kedaluwarsa</p>
                    <h3 class="text-2xl font-bold text-gray-800 mt-1">{{ $stats['overdue'] }}</h3>
                    <p class="text-gray-400 text-xs mt-1">Perlu perhatian</p>
                </div>
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-exclamation-triangle text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Calendar Section -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Kalender Tugas</h2>
                <div class="flex space-x-2">
                    <button id="prev-month" class="p-2 rounded-full hover:bg-gray-50 text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <button id="next-month" class="p-2 rounded-full hover:bg-gray-50 text-gray-500 hover:text-indigo-600 transition-colors">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
            <div id="calendar" class="calendar"></div>
        </div>

        <!-- Upcoming Tasks -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Tugas Mendatang</h2>
                <a href="{{ route('tasks.index') }}" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                    Lihat Semua
                </a>
            </div>

            @if($upcomingTasks->isEmpty())
                <div class="text-center py-8">
                    <div class="mx-auto w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mb-4">
                        <i class="fas fa-check-circle text-2xl text-gray-400"></i>
                    </div>
                    <p class="text-gray-500">Tidak ada tugas mendatang</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($upcomingTasks as $task)
                        <div class="task-card bg-white hover:bg-gray-50 rounded-lg p-4 border border-gray-100 transition-colors duration-150">
                            <div class="flex justify-between items-start">
                                <div class="flex-1 min-w-0">
                                    <h3 class="font-medium text-gray-800 truncate @if($task->completed) line-through @endif">
                                        {{ $task->title }}
                                    </h3>
                                    <div class="flex items-center mt-1 text-sm">
                                        <i class="far fa-calendar-alt mr-2 text-gray-400"></i>
                                        <span class="@if($task->isOverdue()) text-red-500 font-medium @else text-gray-500 @endif">
                                            {{ $task->due_date->isoFormat('D MMM Y') }}
                                            @if($task->isOverdue())
                                                <span class="ml-2 bg-red-50 text-red-600 text-xs px-2 py-0.5 rounded-full">Terlambat</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="p-1.5 rounded-full hover:bg-gray-100 transition-colors"
                                        aria-label="{{ $task->completed ? 'Tandai belum selesai' : 'Tandai selesai' }}">
                                        @if($task->completed)
                                            <i class="fas fa-check-circle text-xl text-green-500"></i>
                                        @else
                                            <i class="far fa-circle text-xl text-gray-300 hover:text-indigo-400"></i>
                                        @endif
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .calendar {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        text-align: center;
        font-weight: 600;
        margin-bottom: 12px;
        color: #4f46e5;
        font-size: 0.875rem;
    }

    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 8px;
    }

    .calendar-day {
        aspect-ratio: 1;
        padding: 8px;
        border-radius: 8px;
        display: flex;
        flex-direction: column;
        font-size: 0.875rem;
        background-color: #f9fafb;
        transition: all 0.2s ease;
    }

    .calendar-day:hover {
        background-color: #f3f4f6;
        transform: translateY(-2px);
    }

    .day-number {
        font-weight: 600;
        margin-bottom: 4px;
        align-self: flex-end;
    }

    .today {
        background-color: #4f46e5;
        color: white;
    }

    .today .day-number {
        color: white;
    }

    .has-tasks {
        border: 2px solid #a5b4fc;
    }

    .task-indicator {
        display: flex;
        justify-content: center;
        gap: 2px;
        margin-top: 4px;
    }

    .task-dot {
        width: 6px;
        height: 6px;
        background-color: #4f46e5;
        border-radius: 50%;
    }

    .other-month {
        color: #9ca3af;
        background-color: #f9fafb;
    }

    .task-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .task-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                          "Juli", "Agustus", "September", "Oktober", "November", "Desember"];

        const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

        let currentDate = new Date();
        let tasksByDate = @json($tasksByDate);

        function renderCalendar() {
            const calendarEl = document.getElementById('calendar');
            calendarEl.innerHTML = '';

            // Header hari
            const headerEl = document.createElement('div');
            headerEl.className = 'calendar-header';
            dayNames.forEach(day => {
                const dayEl = document.createElement('div');
                dayEl.textContent = day.substring(0, 3);
                headerEl.appendChild(dayEl);
            });
            calendarEl.appendChild(headerEl);

            // Body kalender
            const bodyEl = document.createElement('div');
            bodyEl.className = 'calendar-body';

            const firstDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1).getDay();
            const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();
            const daysInPrevMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 0).getDate();

            // Hari dari bulan sebelumnya
            for (let i = firstDay - 1; i >= 0; i--) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                const dayNumberEl = document.createElement('div');
                dayNumberEl.className = 'day-number';
                dayNumberEl.textContent = daysInPrevMonth - i;
                dayEl.appendChild(dayNumberEl);
                bodyEl.appendChild(dayEl);
            }

            // Hari di bulan ini
            const today = new Date();
            for (let i = 1; i <= daysInMonth; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day';

                const dateStr = `${currentDate.getFullYear()}-${(currentDate.getMonth()+1).toString().padStart(2, '0')}-${i.toString().padStart(2, '0')}`;
                const tasksCount = tasksByDate[dateStr] ? tasksByDate[dateStr].length : 0;

                if (tasksCount > 0) {
                    dayEl.classList.add('has-tasks');
                }

                if (i === today.getDate() && currentDate.getMonth() === today.getMonth() && currentDate.getFullYear() === today.getFullYear()) {
                    dayEl.classList.add('today');
                }

                const dayNumberEl = document.createElement('div');
                dayNumberEl.className = 'day-number';
                dayNumberEl.textContent = i;
                dayEl.appendChild(dayNumberEl);

                if (tasksCount > 0) {
                    const taskIndicatorEl = document.createElement('div');
                    taskIndicatorEl.className = 'task-indicator';

                    // Limit to 3 dots max
                    const dotsCount = Math.min(tasksCount, 3);
                    for (let j = 0; j < dotsCount; j++) {
                        const taskDotEl = document.createElement('div');
                        taskDotEl.className = 'task-dot';
                        taskIndicatorEl.appendChild(taskDotEl);
                    }

                    if (tasksCount > 3) {
                        const moreText = document.createElement('span');
                        moreText.className = 'text-xs text-gray-500 ml-1';
                        moreText.textContent = `+${tasksCount - 3}`;
                        taskIndicatorEl.appendChild(moreText);
                    }

                    dayEl.appendChild(taskIndicatorEl);

                    dayEl.addEventListener('click', () => {
                        alert(`Anda memiliki ${tasksCount} tugas pada tanggal ${i} ${monthNames[currentDate.getMonth()]}`);
                    });
                }

                bodyEl.appendChild(dayEl);
            }

            // Calculate remaining cells
            const totalCells = 42; // 6 rows x 7 days
            const filledCells = firstDay + daysInMonth;
            const remainingCells = totalCells - filledCells;

            // Hari dari bulan berikutnya
            for (let i = 1; i <= remainingCells; i++) {
                const dayEl = document.createElement('div');
                dayEl.className = 'calendar-day other-month';
                const dayNumberEl = document.createElement('div');
                dayNumberEl.className = 'day-number';
                dayNumberEl.textContent = i;
                dayEl.appendChild(dayNumberEl);
                bodyEl.appendChild(dayEl);
            }

            calendarEl.appendChild(bodyEl);

            // Update judul bulan dan tahun
            document.getElementById('current-date').textContent =
                `${monthNames[currentDate.getMonth()]} ${currentDate.getFullYear()}`;
        }

        document.getElementById('prev-month').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() - 1);
            renderCalendar();
        });

        document.getElementById('next-month').addEventListener('click', () => {
            currentDate.setMonth(currentDate.getMonth() + 1);
            renderCalendar();
        });

        renderCalendar();
    });
</script>
@endsection
