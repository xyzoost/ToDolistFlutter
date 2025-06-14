@extends('layouts.app')

@section('title', 'Create Task')

@section('content')
<div class="container mx-auto max-w-2xl px-4 py-8">
    <!-- Form Container -->
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <!-- Form Header with Gradient Background -->
        <div class="bg-gradient-to-r from-blue-50 to-indigo-100 p-6 border-b border-gray-200">
            <div class="flex items-center">
                <div class="bg-indigo-100 p-3 rounded-full mr-4">
                    <i class="fas fa-plus-circle text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">Create New Task</h2>
                    <p class="text-sm text-indigo-600 mt-1">Organize your work with a new task</p>
                </div>
            </div>
        </div>

        <!-- Form Content -->
        <form action="{{ route('tasks.store') }}" method="POST" class="p-6">
            @csrf

            <!-- Title Field -->
            <div class="mb-6">
                <label for="title" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <span class="bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded mr-2">Required</span>
                    Task Title
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="fas fa-heading text-gray-400"></i>
                    </div>
                    <input type="text" name="title" id="title"
                        class="pl-10 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition duration-200 placeholder-gray-400"
                        placeholder="Enter task title (e.g., Complete project report)" required>
                </div>
                @error('title')
                    <p class="mt-2 text-sm text-red-600 flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Description Field -->
            <div class="mb-6">
                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                    <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded mr-2">Optional</span>
                    Description
                </label>
                <div class="relative">
                    <div class="absolute top-3 left-3">
                        <i class="fas fa-align-left text-gray-400"></i>
                    </div>
                    <textarea name="description" id="description" rows="4"
                        class="pl-10 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition duration-200 placeholder-gray-400"
                        placeholder="Add details about your task..."></textarea>
                </div>
            </div>

            <!-- Due Date Field -->
            <div class="mb-8">
                <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2 flex items-center">
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded mr-2">Recommended</span>
                    Due Date
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class="far fa-calendar-alt text-gray-400"></i>
                    </div>
                    <input type="date" name="due_date" id="due_date"
                        class="pl-10 w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-200 focus:border-indigo-500 outline-none transition duration-200">
                </div>
                <p class="mt-2 text-xs text-gray-500">Setting a due date helps with task prioritization</p>
            </div>

            <!-- Form Actions -->
            <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('tasks.index') }}"
                    class="px-5 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200 text-center font-medium flex items-center justify-center">
                    <i class="fas fa-times mr-2"></i> Cancel
                </a>
                <button type="submit"
                    class="btn-primary px-5 py-2.5 rounded-lg text-white hover:shadow-md transition duration-200 font-medium flex items-center justify-center">
                    <i class="fas fa-plus-circle mr-2"></i> Create Task
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .btn-primary {
        background-color: #4f46e5;
        transition: all 0.2s ease;
    }
    .btn-primary:hover {
        background-color: #4338ca;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.3);
    }
    textarea {
        min-height: 120px;
        resize: vertical;
    }
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0;
        position: absolute;
        right: 0;
        width: 100%;
        height: 100%;
        cursor: pointer;
    }
    .bg-gradient-to-r {
        background-image: linear-gradient(to right, #f0f9ff, #e0f2fe);
    }
</style>
@endsection
