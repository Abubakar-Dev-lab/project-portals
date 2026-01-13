@extends('layouts.app')
@section('content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">All Tasks</h2>
        <a href="{{ route('tasks.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded">+ New Task</a>
    </div>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        @include('tasks._table', ['tasks' => $tasks])
    </div>
@endsection
