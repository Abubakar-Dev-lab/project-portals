@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200 mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Create your Account</h2>
        <form action="{{ route('register') }}" method="POST">
            @csrf

            <x-form-input name="name" label="Full Name" placeholder="e.g. Ali Ahmed" />
            <x-form-input name="email" type="email" label="Email Address" placeholder="ali@example.com" />
            <x-form-input name="password" type="password" label="Password" />
            <x-form-input name="password_confirmation" type="password" label="Confirm Password" />
            <div class="mt-6">
                <button type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200">
                    Register
                </button>
            </div>
            <p class="mt-4 text-center text-sm text-gray-600">
                Already have an account?
                <a href="{{route('login')}}" class="text-blue-600 hover:underline">Log in</a>
            </p>
        </form>
    </div>
@endsection
