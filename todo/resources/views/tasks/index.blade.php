@extends('layouts.app')

@section('title', 'My Tasks')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">My Tasks</h1>
            <p class="text-slate-500 mt-1">Manage your daily activities efficiently</p>
        </div>
        <a href="{{ route('tasks.create') }}" class="btn-primary px-5 py-3 rounded-lg flex items-center justify-center sm:justify-start hover:shadow-md transition-all">
            <i class="fas fa-plus mr-2"></i> Add New Task
        </a>
    </div>

    <!-- Task Filters -->
    <div class="mb-8 flex flex-wrap gap-3">
        <a href="{{ route('tasks.index') }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all
            @if(request('filter') === null) bg-indigo-600 text-white shadow-md
            @else bg-white text-slate-700 border border-slate-200 hover:border-indigo-300 hover:text-indigo-600 @endif">
            All Tasks
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'pending']) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all
            @if(request('filter') === 'pending') bg-amber-100 text-amber-800
            @else bg-white text-slate-700 border border-slate-200 hover:border-amber-200 hover:text-amber-600 @endif">
            <i class="fas fa-clock mr-1"></i> Pending
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'completed']) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all
            @if(request('filter') === 'completed') bg-green-100 text-green-800
            @else bg-white text-slate-700 border border-slate-200 hover:border-green-200 hover:text-green-600 @endif">
            <i class="fas fa-check-circle mr-1"></i> Completed
        </a>
        <a href="{{ route('tasks.index', ['filter' => 'overdue']) }}" class="px-4 py-2 rounded-full text-sm font-medium transition-all
            @if(request('filter') === 'overdue') bg-red-100 text-red-800
            @else bg-white text-slate-700 border border-slate-200 hover:border-red-200 hover:text-red-600 @endif">
            <i class="fas fa-exclamation-circle mr-1"></i> Overdue
        </a>
    </div>

    @if($tasks->isEmpty())
        <!-- Empty State -->
        <div class="bg-white rounded-xl shadow-sm p-8 text-center border border-slate-100">
            <div class="mx-auto w-20 h-20 bg-indigo-50 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-tasks text-3xl text-indigo-400"></i>
            </div>
            <h3 class="text-xl font-medium text-slate-700 mb-2">No tasks found</h3>
            <p class="text-slate-500 mb-6 max-w-md mx-auto">You don't have any tasks yet. Start by creating your first task to stay organized.</p>
            <a href="{{ route('tasks.create') }}" class="btn-primary px-6 py-3 rounded-lg inline-flex items-center hover:shadow-md">
                <i class="fas fa-plus mr-2"></i> Create Your First Task
            </a>
        </div>
    @else
        <!-- Task Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($tasks as $task)
                <div class="task-card bg-white rounded-xl shadow-sm border border-slate-100 hover:shadow-md transition-all overflow-hidden
                    @if($task->completed) border-l-4 border-l-green-500 @else border-l-4 border-l-indigo-500 @endif">
                    <div class="p-5">
                        <!-- Task Header -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-bold text-lg text-slate-800 truncate @if($task->completed) line-through @endif">
                                    {{ $task->title }}
                                </h3>
                                @if($task->due_date)
                                    <div class="flex items-center mt-2 text-sm">
                                        <i class="far fa-calendar-alt mr-2 text-slate-400"></i>
                                        <span class="@if($task->due_date->isPast() && !$task->completed) text-red-500 font-medium @else text-slate-500 @endif">
                                            {{ $task->due_date->format('M d, Y') }}
                                            @if($task->due_date->isPast() && !$task->completed)
                                                <span class="ml-2 bg-red-50 text-red-600 text-xs px-2 py-0.5 rounded-full">Overdue</span>
                                            @endif
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <form action="{{ route('tasks.toggle', $task) }}" method="POST">
                                @csrf
                                <button type="submit" class="p-1.5 rounded-full hover:bg-slate-50 transition-colors"
                                    aria-label="{{ $task->completed ? 'Mark as pending' : 'Mark as complete' }}">
                                    @if($task->completed)
                                        <i class="fas fa-check-circle text-xl text-green-500"></i>
                                    @else
                                        <i class="far fa-circle text-xl text-slate-300 hover:text-indigo-400"></i>
                                    @endif
                                </button>
                            </form>
                        </div>

                        <!-- Task Description -->
                        @if($task->description)
                            <p class="text-slate-600 mb-4 text-sm line-clamp-3">{{ $task->description }}</p>
                        @endif

                        <!-- Task Footer -->
                        <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                            <span class="text-xs text-slate-400">
                                <i class="far fa-clock mr-1"></i> {{ $task->created_at->diffForHumans() }}
                            </span>
                            <div class="flex space-x-2">
                                <a href="{{ route('tasks.edit', $task) }}" class="p-2 rounded-full hover:bg-indigo-50 text-indigo-500 hover:text-indigo-600 transition-colors"
                                    aria-label="Edit task">
                                    <i class="fas fa-pencil-alt text-sm"></i>
                                </a>
                                <form action="{{ route('tasks.destroy', $task) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-full hover:bg-red-50 text-red-400 hover:text-red-600 transition-colors"
                                        aria-label="Delete task">
                                        <i class="fas fa-trash text-sm"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<style>
    .btn-primary {
        background-color: var(--primary-color);
        color: rgb(0, 0, 0);
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: var(--primary-hover);
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }
    .task-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .task-card:hover {
        transform: translateY(-3px);
    }
</style>
@endsection
