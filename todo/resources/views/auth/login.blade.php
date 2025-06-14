@extends('layouts.guest')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-md w-full bg-white rounded-xl shadow-xl overflow-hidden transition-all duration-300 hover:shadow-2xl">
        <div class="bg-white py-8 px-8 border-b border-gray-100">
            <div class="flex justify-center">
                <div class="flex items-center">
                    <i class="fas fa-tasks text-indigo-600 text-4xl mr-3"></i>
                    <span class="text-3xl font-bold text-gray-800">Todo<span class="text-indigo-600">App</span></span>
                </div>
            </div>
            <h2 class="mt-6 text-center text-2xl font-bold text-gray-800">Welcome Back</h2>
            <p class="mt-2 text-center text-gray-500">Please enter your details to sign in</p>
        </div>

        <div class="py-8 px-8">
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <input id="email" type="email" name="email" required autofocus
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none transition duration-200 text-gray-700"
                            value="{{ old('email') }}" placeholder="your@email.com">
                    </div>
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-lock text-gray-400"></i>
                        </div>
                        <input id="password" type="password" name="password" required
                            class="w-full pl-10 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none transition duration-200 text-gray-700"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-600">Remember me</label>
                    </div>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:text-indigo-500 font-medium">
                            Forgot password?
                        </a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-3 px-4 rounded-lg transition duration-200 transform hover:-translate-y-0.5 shadow-md hover:shadow-indigo-200">
                    Sign In
                    <i class="fas fa-arrow-right ml-2"></i>
                </button>
            </form>

            <div class="mt-8 text-center">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-200"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">
                            Don't have an account?
                        </span>
                    </div>
                </div>

                <div class="mt-6">
                    <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-gray-200 rounded-lg text-indigo-600 hover:bg-indigo-50 font-medium transition duration-200">
                        Sign Up Now
                        <i class="fas fa-user-plus ml-2"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
