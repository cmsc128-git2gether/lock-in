@extends('layouts.sidebar')
@section('title', $group->name)

@section('content')
        <!-- tasks: {{ $tasks->count() }} | members: {{ $members->count() }} -->
        @php
        //manager can delete task
        $canManage = auth()->user()->can('manageMembers', $group);

            $activeCount = $tasks->where('is_done', false)->count();
            $doneCount = $tasks->where('is_done', true)->count();
            $overdueCount = $tasks->where('is_done', false)
                ->filter(fn ($t) => $t->due_at && \Carbon\Carbon::parse($t->due_at)->isPast())
                ->count();
        @endphp

        <section class="card greeting-card">
            <div>
                <h1 class="greeting-title">{{ $group->name }}</h1>
                <p class="greeting-sub">
                    Group list | owned by {{ $group->owner_id === auth()->id() ? 'you' : ($group->owner->name ?? 'Unknown') }}
                </p>
            </div>
            <div class="stats-metrics">
                <div class="stat">
                    <span class="stat-label">Active</span>
                    <span class="stat-value" id="stat-active">{{ $activeCount }}</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Completed</span>
                    <span class="stat-value" id="stat-done">{{ $doneCount }}</span>
                </div>
                <div class="stat {{ $overdueCount ? 'stat-alert' : '' }}" id="stat-overdue-box">
                    <span class="stat-label">Overdue</span>
                    <span class="stat-value" id="stat-overdue">{{ $overdueCount }}</span>
                </div>
            </div>
        </section>

        @if (session('status')) <p class="text-green-700">{{ session('status') }}</p> @endif
        @if ($errors->any())
            <ul class="text-red-600 list-disc pl-5">
                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        @endif

        <section class="card task-card">
        <div class="task-head">
            <h2 class="task-title">
                Active Tasks <span class="count-pill" id="task-count">{{ $activeCount }}</span>
            </h2>
        </div>


        {{-- add a task --}}
        <form method="POST" action="{{ route('groups.tasks.store', $group) }}" class="group-add-form">
            @csrf
            <input type="text" name="title" placeholder="Add a task…" required class="input input-grow">
            <select name="priority" class="select" aria-label="Priority">
                @foreach ($priorities as $p) <option value="{{ $p }}">{{ $p }}</option> @endforeach
            </select>
            <select name="tag_id" class="select" aria-label="Tag">
                <option value="">No tag</option>
                @foreach ($tags as $tag) <option value="{{ $tag->id }}">{{ $tag->name }}</option> @endforeach
            </select>
            <input type="datetime-local" name="due_at" class="input" aria-label="Due date">
            <button type="submit" class="btn btn-dark">@svg('heroicon-o-plus', 'w-4 h-4') Add</button>
        </form>

        {{-- tasks table --}}
        @if ($tasks->isEmpty())
            <p class="empty-message">No tasks in this list yet.</p>
        @else
        <h3 class="toolbar-title">Filter and Sort</h3>
            <div class="task-toolbar">
                
                {{-- sort --}}
                <select id="filterPriority" class="select" onchange="filterByColumn('priority', this.value)" aria-label="Filter by priority">
                    <option value="">All priorities</option>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}">{{ $priority }}</option>
                    @endforeach
                </select>

                <select id="filterTag" class="select" onchange="filterByColumn('tag', this.value)" aria-label="Filter by tag">
                    <option value="">All tags</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
                
                <select id="sortSelect" class="select" onchange="sortTable(this.value)" aria-label="Sort tasks">
                    <option value="created_at">Sort: Date added</option>
                    <option value="due_at">Sort: Due date</option>
                    <option value="priority">Sort: Priority</option>
                    <option value="tag_id">Sort: Tag</option>
                </select>

                <label class="switch">
                    <input type="checkbox" id="showDone" onchange="filterByColumn('status', this.checked ? '' : 'active')">
                    <span class="switch-track"></span>
                    <span class="switch-text">Show completed</span>
                </label>
            </div>
            {{-- tasks itself --}}
            <table id="table-tasks" class="task-table">
                <thead>
                    <tr>
                        <th>Task</th>
                        <th>Due</th>
                        <th>Priority</th>
                        <th>Tag</th>
                        <th>Added by</th>
                        <th class="col-actions"></th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($tasks as $task)
                    @php
                        $due = $task->due_at ? \Carbon\Carbon::parse($task->due_at) : null;
                        $overdue = $due && $due->isPast() && ! $task->is_done;
                        $mine = $task->user_id === auth()->id();
                        $who = $mine ? 'You' : ($task->user->name ?? 'Unknown');
                    @endphp
                    <tr class="{{ $task->is_done ? 'task-done' : '' }}"
                        data-overdue="{{ $overdue ? 1 : 0 }}"
                        data-created="{{ $task->created_at }}"
                        data-due="{{ $task->due_at }}"
                        data-priority="{{ $task->priority }}"
                        data-tag="{{ $task->tag->name ?? '' }}"
                        data-tag-id="{{ $task->tag_id ?? 0 }}"
                        data-status="{{ $task->is_done ? 'done' : 'active' }}">
                        <td>
                            <form method="POST" action="{{ route('groups.tasks.update', [$group, $task]) }}" class="checkbox-form">
                                @csrf @method('PATCH')
                                <input type="hidden" name="is_done" value="0">
                                <input type="checkbox" id="gtask-{{ $task->id }}" class="todo" name="is_done" value="1"
                                        @checked($task->is_done) onchange="this.form.submit()">
                                <label for="gtask-{{ $task->id }}">{{ $task->title }}</label>
                            </form>
                        </td>
                        <td class="due {{ $overdue ? 'overdue' : '' }}">
                            {{ $due ? $due->format('M d, Y · g:i A') : '—' }}
                        </td>
                        <td><span class="priority {{ $task->priority }}">{{ $task->priority }}</span></td>
                        <td>
                            @if ($task->tag)
                                <span class="tag" style="--tag: {{ $task->tag->color ?? '#3b82f6' }}">
                                    <span class="tag-icon">
                                        @svg('heroicon-o-' . ($task->tag->icon ?? 'tag'), 'w-full h-full')
                                    </span>
                                    {{ $task->tag->name }}
                                </span>
                            @else
                                <span class="muted">—</span>
                            @endif
                        </td>
                        <td>
                            <span class="by">
                                <span class="avatar avatar-sm">{{ strtoupper(substr($who, 0, 1)) }}</span>
                                {{ $who }}
                            </span>
                        </td>
                        <td class="col-actions">
                            @if ($mine || $canManage)
                                <div class="action-dropdown">
                                    <button class="action-btn" onclick="toggle(event)" type="button" aria-label="Task actions">&#8942;</button>
                                    <div class="dropdown-choices">
                                        @if ($mine)
                                            <button type="button" class="dropdown-item"
                                                    onclick="document.getElementById('gedit-popup-{{ $task->id }}').showModal()">Edit</button>
                                        @endif
                                        <form class="delete-form" style="margin:0"
                                            data-task-id="{{ $task->id }}"
                                            data-task-title="{{ $task->title }}"
                                            data-destroy-url="{{ route('groups.tasks.destroy', [$group, $task]) }}"
                                            data-restore-url="{{ route('groups.tasks.restore', [$group, $task->id]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="dropdown-item delete"
                                                    onclick="return confirm('Are you sure you want to delete this task?') && handleDelete(this)">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <p class="empty-message" id="no-results" hidden>
                No tasks to show. Try changing the filters, or turn on “Show completed”.
            </p>
        @endif
        </section>

    {{-- members --}}
    <section class="card task-card">
        <div class="task-head">
            <h2 class="task-title">Members <span class="count-pill">{{ count($members) }}</span></h2>
        </div>

        <ul class="member-list">
            @foreach ($members as $member)
                <li class="member">
                    <span class="avatar">{{ strtoupper(substr($member->name, 0, 1)) }}</span>
                    <div class="member-text">
                        <strong>{{ $member->name }}</strong>
                        <small>{{ $member->email }}</small>
                    </div>

                    @if ($member->id === $group->owner_id)
                        <span class="pill-neutral">Owner</span>
                    @elseif ($member->id === auth()->id() || auth()->user()->can('manageMembers', $group))
                        <form method="POST" action="{{ route('groups.members.destroy', [$group, $member]) }}"
                            onsubmit="return confirm('Are you sure?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-danger btn-sm">
                                {{ $member->id === auth()->id() ? 'Leave' : 'Remove' }}
                            </button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>

        @can('manageMembers', $group)
            <form method="POST" action="{{ route('groups.members.store', $group) }}" class="group-add-form member-add">
                @csrf
                <input type="email" name="email" placeholder="teammate@example.com" required class="input input-grow">
                <button type="submit" class="btn btn-dark">Add by email</button>
            </form>
        @endcan
    </section>

    @can('delete', $group)
        <form method="POST" action="{{ route('groups.destroy', $group) }}"
            onsubmit="return confirm('Delete this list? Tasks go back to their creators.')">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Delete this list</button>
        </form>
    @endcan

    {{-- edit popups (own tasks only) --}}
    @foreach ($tasks->where('user_id', auth()->id()) as $task)
        <dialog id="gedit-popup-{{ $task->id }}" class="popup-modal">
            <button type="button" class="close-form" onclick="this.closest('dialog').close()">✕</button>
            <h2>Edit Task</h2>
            <form action="/tasks/{{ $task->id }}/submit" class="popup-form" method="POST">
                @csrf
                @method('PATCH')
                <label for="gtitle-{{ $task->id }}">Task Name</label>
                <input type="text" id="gtitle-{{ $task->id }}" name="title" value="{{ $task->title }}" required>

                <label for="gdue-{{ $task->id }}">Task Due Date and Time</label>
                <input type="datetime-local" id="gdue-{{ $task->id }}" name="due_at"
                    value="{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('Y-m-d\TH:i') : '' }}">

                <label for="gpriority-{{ $task->id }}">Task Priority</label>
                <select name="priority" id="gpriority-{{ $task->id }}" required>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" @selected($task->priority === $priority)>{{ $priority }}</option>
                    @endforeach
                </select>

                <label for="gtag-{{ $task->id }}">Task Tag</label>
                <select name="tag_id" id="tag_id-{{ $task->id }}">
                    <option value="">--No Tag--</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected($task->tag_id === $tag->id)>
                            {{ $tag->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Save</button>
            </form>
        </dialog>
    @endforeach

    @include('components.add-tag-modal')
    @endsection
