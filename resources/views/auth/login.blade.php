@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200 mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Login</h2>
        <form action="{{ route('login') }}" method="POST">
            @csrf

            <x-form-input name="email" type="email" label="Email Address" placeholder=" ali@example.com" />
            <x-form-input name="password" type="password" label="Password" />
            <div class="flex items-center mb-4">
                <input type="checkbox" name="remember" id="remember"
                    class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500">
                <label for="remember" class="ml-2 text-sm text-gray-600">Remember Me</label>
            </div>
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    Login
                </button>
            </div>

        </form>
    </div>
@endsection
