@extends('layouts.app')
@section('content')
    <div class="max-w-md mx-auto bg-white p-8 rounded-lg shadow-md border border-gray-200 mt-10">
        <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">Update Profile</h2>
        <form action="{{ route('profile.update') }}" method="POST">
            @csrf @method('PUT')
            @include('users._fields')
            <button type="submit">Update My Profile</button>
        </form>
    </div>
@endsection
