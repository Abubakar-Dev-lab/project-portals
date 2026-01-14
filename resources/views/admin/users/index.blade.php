@extends('layouts.app')

@section('content')
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">User Management</h1>
            <p class="text-gray-600 mt-1">Control access levels and manage company employees.</p>
        </div>
    </div>

    <!-- Include our reusable table -->
    @include('users._table', ['users' => $users])
@endsection
