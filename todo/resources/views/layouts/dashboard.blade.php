@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-slate-800">Dashboard Overview</h1>
        <a href="{{ route('tasks.create') }}" class="btn-primary flex items-center px-4 py-2 rounded-lg">
            <i class="fas fa-plus mr-2"></i> New Task
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Total Tasks</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $totalTasks }}</h3>
                    <p class="text-slate-400 text-xs mt-1">All your tasks</p>
                </div>
                <div class="p-3 rounded-full bg-indigo-100 text-indigo-600">
                    <i class="fas fa-tasks text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Completed Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Completed</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $completedTasks }}</h3>
                    <p class="text-slate-400 text-xs mt-1">{{ $totalTasks > 0 ? round(($completedTasks/$totalTasks)*100) : 0 }}% of total</p>
                </div>
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-check-circle text-lg"></i>
                </div>
            </div>
        </div>

        <!-- Pending Tasks -->
        <div class="bg-white rounded-xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-500 text-sm font-medium">Pending</p>
                    <h3 class="text-2xl font-bold text-slate-800 mt-1">{{ $pendingTasks }}</h3>
                    <p class="text-slate-400 text-xs mt-1">{{ $totalTasks > 0 ? round(($pendingTasks/$totalTasks)*100) : 0 }}% of total</p>
                </div>
                <div class="p-3 rounded-full bg-amber-100 text-amber-600">
                    <i class="fas fa-clock text-lg"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Tasks Section -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="flex items-center justify-between p-6 border-b border-slate-100">
            <h2 class="text-xl font-bold text-slate-800">Recent Tasks</h2>
            <a href="{{ route('tasks.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium flex items-center">
                View All <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        @if($recentTasks->isEmpty())
            <div class="p-8 text-center">
                <div class="mx-auto w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-tasks text-3xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-medium text-slate-700 mb-2">No tasks yet</h3>
                <p class="text-slate-500 mb-4">Get started by creating your first task</p>
                <a href="{{ route('tasks.create') }}" class="btn-primary inline-flex items-center px-4 py-2 rounded-lg">
                    <i class="fas fa-plus mr-2"></i> Create Task
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-100">
                @foreach($recentTasks as $task)
                    <div class="task-card p-5 hover:bg-slate-50 transition-colors duration-150 flex items-center justify-between @if($task->completed) completed opacity-80 @endif">
                        <div class="flex items-start space-x-4">
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="mt-1 flex-shrink-0">
                                    <div class="w-5 h-5 rounded-full border-2 @if($task->completed) border-green-500 bg-green-100 @else border-slate-300 @endif flex items-center justify-center">
                                        @if($task->completed)
                                            <i class="fas fa-check text-green-500 text-xs"></i>
                                        @endif
                                    </div>
                                </button>
                            </form>
                            <div>
                                <h3 class="font-medium text-slate-800 @if($task->completed) line-through @endif">{{ $task->title }}</h3>
                                @if($task->due_date)
                                    <div class="flex items-center mt-1 text-sm">
                                        <span class="text-slate-500 mr-2"><i class="far fa-calendar-alt mr-1"></i> {{ $task->due_date->format('M d, Y') }}</span>
                                        @if($task->due_date->isPast() && !$task->completed)
                                            <span class="bg-red-100 text-red-800 text-xs px-2 py-0.5 rounded-full">Overdue</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('tasks.edit', $task) }}" class="p-2 text-slate-400 hover:text-indigo-600 rounded-full hover:bg-indigo-50 transition-colors" title="Edit">
                                <i class="fas fa-pencil-alt"></i>
                            </a>
                            @if($task->completed)
                                <span class="px-2 py-1 bg-green-100 text-green-800 text-xs rounded-full">Completed</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
    .task-card {
        border-left: 3px solid var(--accent-color);
    }
    .task-card.completed {
        border-left-color: #10b981;
    }
    .btn-primary {
        background-color: var(--accent-color);
        color: white;
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: var(--accent-hover);
        transform: translateY(-1px);
    }
</style>
@endsection
