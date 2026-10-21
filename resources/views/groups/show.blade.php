<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Group List</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-bg">
<div class="app-shell">

    @extends('layouts.sidebar')
    @section('title', 'Recently Deleted')

    @section('content')
    <div class="main-content">
        @php
            $activeCount = $tasks->where('is_done', false)->count();
            $doneCount = $tasks->where('is_done', true)->count();
            $overdueCount = $tasks->where('is_done', false)
                ->filter(fn ($t) => $t->due_at && \Carbon\Carbon::parse($t->due_at)->isPast())
                ->count();
        @endphp
        <section class="card greeting-card">
            <div>
                <h1 class="greeting-title">Group Lists</h1>
                <p class="greeting-sub">Manage your group list.</p>
            </div>
            <div class="stats-metrics">
                <div class="stat">
                    <span class="stat-label">Active Group Tasks</span>
                    <span class="stat-value" id="stat-active">{{ $activeCount }}</span>
                </div>
            </div>
            <div class="stat">
                <span class="stat-label">Completed</span>
                <span class="stat-value" id="stat-done">{{ $doneCount }}</span>
            </div>
            <div class="stat {{ $overdueCount ? 'stat-alert' : '' }}" id="stat-overdue-box">
                <span class="stat-label">Overdue</span>
                <span class="stat-value" id="stat-overdue">{{ $overdueCount }}</span>
            </div>
        </section>

        @if (session('status')) <p class="text-green-700">{{ session('status') }}</p> @endif
        @if ($errors->any())
            <ul class="text-red-600 list-disc pl-5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        @endif


        {{-- add a task --}}
        <form method="POST" action="{{ route('groups.tasks.store', $group) }}" class="flex flex-wrap gap-2">
            @csrf
            <input type="text" name="title" placeholder="New task" required class="border rounded px-3 py-2 flex-1">
            <select name="priority" class="border rounded px-2">
                @foreach ($priorities as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
            </select>
            <select name="tag_id" class="border rounded px-2">
                <option value="">No tag</option>
                @foreach ($tags as $tag) <option value="{{ $tag->id }}">{{ $tag->name }}</option> @endforeach
            </select>
            <input type="datetime-local" name="due_at" class="border rounded px-2">
            <button class="px-4 py-2 bg-gray-800 text-white rounded">Add</button>
        </form>

        {{-- tasks --}}
        <div class="space-y-2"> 
            @forelse ($tasks as $task)
                <div class="flex items-center gap-3 border rounded p-3 {{ $task->is_done ? 'bg-green-50 opacity-60' : 'bg-white' }}">
                    <form method="POST" action="{{ route('groups.tasks.update', [$group, $task]) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="is_done" value="0">
                        <input type="checkbox" name="is_done" value="1"
                               {{ $task->is_done ? 'checked' : '' }} onchange="this.form.submit()">
                    </form>

                    <span class="{{ $task->is_done ? 'line-through' : '' }}">{{ $task->title }}</span>

                    <span class="ml-auto text-xs text-gray-500">
                        {{ $task->priority }} · by {{ $task->user_id === auth()->id() ? 'you' : $task->user->name }}
                    </span>

                    @if ($task->user_id === auth()->id())
                        <a href="{{ url('/tasks/'.$task->id.'/edit') }}" class="text-sm underline">Edit</a>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">No tasks in this list yet.</p>
            @endforelse
        </div>

        {{-- members --}}
        <div>
            <h3 class="font-semibold mb-2">Members</h3>
            <ul class="space-y-1">
                @foreach ($members as $member)
                    <li class="flex items-center gap-2">
                        <span>{{ $member->name }} <span class="text-gray-500">({{ $member->email }})</span></span>
                        @if ($member->id === $group->owner_id)
                            <span class="text-xs bg-gray-200 rounded px-2">owner</span>
                        @elseif ($member->id === auth()->id() || auth()->user()->can('manageMembers', $group))
                            <form method="POST" action="{{ route('groups.members.destroy', [$group, $member]) }}"
                                  onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-600 underline">
                                    {{ $member->id === auth()->id() ? 'Leave' : 'Remove' }}
                                </button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>

            @can('manageMembers', $group)
                <form method="POST" action="{{ route('groups.members.store', $group) }}" class="flex gap-2 mt-3">
                    @csrf
                    <input type="email" name="email" placeholder="teammate@example.com" required
                           class="border rounded px-3 py-2 flex-1">
                    <button class="px-4 py-2 bg-gray-800 text-white rounded">Add by email</button>
                </form>
            @endcan
        </div>

        @can('delete', $group)
            <form method="POST" action="{{ route('groups.destroy', $group) }}"
                  onsubmit="return confirm('Delete this list? Tasks go back to their creators.')">
                @csrf @method('DELETE')
                <button class="text-sm text-red-600 underline">Delete this list</button>
            </form>
        @endcan
    </div>
    @endsection
</body>
</html>