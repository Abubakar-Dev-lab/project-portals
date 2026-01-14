@extends('layouts.app')

@section('content')
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h2 class="text-2xl font-bold mb-6">User Management: Edit Account</h2>

        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')

            @include('users._fields')

            <div class="flex items-center justify-between mt-8 border-t pt-6">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded font-bold hover:bg-blue-700">
                    Update User
                </button>
                <a href="{{ route('admin.users.index') }}" class="text-gray-500 hover:underline">Cancel</a>
            </div>
        </form>
    </div>
@endsection
