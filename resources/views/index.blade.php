<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="app-bg">
<div class="app-shell">

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-top">
            <a href="/" class="brand">
                <span class="brand-mark"></span>
                {{ config('app.name') }}
            </a>
        </div>
        <nav class="sidebar-nav">
            <a href="#" class="sidebar-item active">
                @svg('heroicon-o-clipboard-document-check', 'w-4 h-4')
                <span>Personal</span>
            </a>
        </nav>

        <div class="sidebar-heading-row">
            <p class="sidebar-heading">Lists</p>
            <button type="button" class="sidebar-add" aria-label="New list">
                @svg('heroicon-o-plus', 'w-3.5 h-3.5')
            </button>
        </div>

        <details class="sidebar-group" open>
            <summary>
                @svg('heroicon-o-chevron-down', 'w-3 h-3 chevron')
                My lists
            </summary>
            {{-- later: @foreach over lists you own --}}
            <a href="#" class="sidebar-item sub">MY Group List</a>
        </details>

        <details class="sidebar-group" open>
            <summary>
                @svg('heroicon-o-chevron-down', 'w-3 h-3 chevron')
                Shared with me
            </summary>
            {{-- later: @foreach over lists you were added to --}}
            <a href="#" class="sidebar-item sub">
                Bee Group List
                <small class="sidebar-owner">by Bee</small>
            </a>
        </details>

        <div class="sidebar-bottom">
            <div class="user-card">
                <span class="avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                <div class="user-card-text">
                    <strong>{{ Auth::user()->name }}</strong>
                    <small>{{ Auth::user()->email }}</small>
                </div>
            </div>
            <div class="user-actions">
                <a href="{{ route('profile.edit') }}">Edit Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit">Log out</button>
                </form>
            </div>
        </div>
    </aside>

    <div class="main-content">
        <div class="main-content">
            @php
                $hour = now()->hour;
                $greeting = $hour < 12 ? 'Good Morning' : ($hour < 18 ? 'Good Afternoon' : 'Good Evening');

                $activeCount = $tasks->where('is_done', false)->count();
                $doneCount = $tasks->where('is_done', true)->count();
                $overdueCount = $tasks->where('is_done', false)
                    ->filter(fn ($t) => $t->due_at && \Carbon\Carbon::parse($t->due_at)->isPast())
                    ->count();
            @endphp

        {{-- greeting card --}}
        <section class="card greeting-card">
            <div>
                <h1 class="greeting-title">{{ $greeting }}, {{ Str::before(Auth::user()->name, ' ') }}!</h1>
                <p class="greeting-sub">What do you plan to do today?</p>
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
            @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:12px; margin-bottom:12px; border-radius:6px;">
                <strong>ERROR:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <section class="card task-card">
    <div class="task-head">
        <h2 class="task-title">
            Active Tasks <span class="count-pill" id="task-count">{{ $tasks->where('is_done', false)->count() }}</span>
        </h2>
        <div class="task-actions">
            <button type="button" class="btn btn-outline" onclick="document.getElementById('tag-form-popup').showModal()">
                @svg('heroicon-o-tag', 'w-4 h-4') New tag
            </button>
            <button type="button" class="btn btn-dark" onclick="document.getElementById('form-popup').showModal()">
                @svg('heroicon-o-plus', 'w-4 h-4') Add task
            </button>
        </div>
    </div>

    @if ($tasks->isEmpty())
        <p class="empty-message">No todos yet. Make a new one!</p>
    @else
        <div class="task-toolbar">
            <select id="filterPriority" class="select" onchange="filterByColumn('priority', this.value)" aria-label="Filter by priority">                <option value="">All priorities</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}">{{ $priority }}</option>
                @endforeach
            </select>

            <select id="filterTag" class="select" onchange="filterByColumn('tag', this.value)" aria-label="Filter by tag">                <option value="">All tags</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->name }}">{{ $tag->name }}</option>
                @endforeach
            </select>

            <select id="sortSelect" class="select" onchange="sortTable(this.value)" aria-label="Sort tasks">                <option value="created_at">Sort: Date added</option>
                <option value="due_at">Sort: Due date</option>
                <option value="priority">Sort: Priority</option>
                <option value="tag_id">Sort: Tag</option>
            </select>

            <label class="switch">
            <input type="checkbox" id="showDone" onchange="filterByColumn('status', this.checked ? '' : 'active')">                <span class="switch-track"></span>
                <span class="switch-text">Show completed</span>
            </label>
        </div>

        <table id="table-tasks" class="task-table">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Due</th>
                    <th>Priority</th>
                    <th>Tag</th>
                    <th class="col-actions"></th>
                </tr>
            </thead>
            <tbody>
            @foreach ($tasks as $task)
                @php
                    $due = $task->due_at ? \Carbon\Carbon::parse($task->due_at) : null;
                    $overdue = $due && $due->isPast() && ! $task->is_done;
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
                        <form action="/tasks/{{ $task->id }}" method="POST" class="checkbox-form">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="is_done" value="0">
                            <input type="checkbox" id="todo-{{ $task->id }}" class="todo" name="is_done" value="1"
                                @checked($task->is_done) onchange="this.form.submit()">
                            <label for="todo-{{ $task->id }}">{{ $task->title }}</label>
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
                    <td class="col-actions">
                        <div class="action-dropdown">
                            <button class="action-btn" onclick="toggle(event)" type="button" aria-label="Task actions">&#8942;</button>
                            <div class="dropdown-choices">
                                <button type="button" class="dropdown-item" onclick="document.getElementById('edit-popup-{{ $task->id }}').showModal()">Edit</button>
                                <form class="delete-form" data-task-id="{{ $task->id }}" data-task-title="{{ $task->title }}" style="margin:0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="dropdown-item delete" onclick="return confirm('Are you sure you want to delete this task?') && handleDelete(this)">Delete</button>
                                </form>
                            </div>
                        </div>
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
        </div>
    </div>

    <!-- new task form popup -->

    <!-- edit task popup, same concept with add form popup -->
    @foreach ($tasks as $task)
        <dialog id="edit-popup-{{ $task->id }}" class="popup-modal">
            <button class="close-form" onclick="document.getElementById('edit-popup-{{ $task->id }}').close()">X</button>
            <h2>Edit Task</h2>
            <form action="/tasks/{{ $task->id }}/submit" class="popup-form" method="POST">
                @csrf
                @method('PATCH')
                <label for="title-{{ $task->id }}">Task Name</label>
                <input type="text" id="title-{{ $task->id }}" name="title" value="{{ $task->title }}" required>

                <label for="due_at-{{ $task->id }}">Task Due Date and Time</label>
                <input type="datetime-local" id="due_at-{{ $task->id }}" name="due_at"
                    value="{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('Y-m-d\TH:i') : '' }}">

                <label for="priority-{{ $task->id }}">Task Priority</label>
                <select name="priority" id="priority-{{ $task->id }}" required>
                    @foreach ($priorities as $priority)
                        <option value="{{ $priority }}" @selected($task->priority === $priority)>
                            {{ $priority }}
                        </option>
                    @endforeach
                </select>

                <label for="tag_id-{{ $task->id }}">Task Tag</label>
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
    <div id="notif-container"></div>
    </div>

@include('components.add-tag-modal')
@include('components.add-task-modal')
</body>
</html>