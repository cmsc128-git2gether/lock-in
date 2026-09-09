<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body>
    <div class="page-container">
        <div class="header">
            <h1>My Todos</h1>
        </div>

        <div class="main-content">
            @if ($errors->any())
            <div style="background:#fee2e2; color:#991b1b; padding:12px; margin-bottom:12px; border-radius:6px;">
                <strong>Uh oh! ERROR:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <div class="task-card">
                <div class="task-flex">
                    <div class="task-header">
                        <h1>Active Tasks ({{ $tasks->count() }})</h1>
                        <div class="header-actions">
                            <div class="th-sort">
                                <label id="label-sort">Sort by:
                                    <div class="th-sort-choices">
                                        <select name="sort" id="sortSelect" onchange="sortTable(this.value)">
                                            <option value="created_at">Date Added</option>
                                            <option value="due_at">Due Date and Time</option>
                                            <option value="priority">Priority</option>
                                            <option value="tag_id">Tag</option>
                                        </select>
                                    </div>
                                </label>
                            </div>
                        </div>



                        <div class="add-task-form">
                            <button class="open-form" onclick="document.getElementById('form-popup').showModal()">
                                + Add New Task
                            </button>
                        </div>
                    </div>

                    @if ($tasks->isEmpty())
                        <p class="empty-message">No todos yet. Make a new one!</p>
                    @else
                        <table id="table-tasks" class="task-table">
                            <thead>
                                <tr>
                                    <th>Task Name</th>
                                    <th>Due Date and Time</th>
                                    <th>
                                        <div class="th-filter">
                                            <span onclick="toggleColFilter(event)">Priority</span>
                                            <div class="th-filter-choices">
                                                <button type="button" onclick="filterByColumn('priority', '')">All</button>
                                            @foreach ($priorities as $priority)
                                                <button type="button" onclick="filterByColumn('priority', '{{ $priority }}')">{{ $priority }}</button>
                                            @endforeach
                                            </div>
                                        </div>
                                    </th>
                                    <th>
                                        <div class="th-filter">
                                            <span onclick="toggleColFilter(event)">Tag</span>
                                            <div class="th-filter-choices">
                                                <button type="button" onclick="filterByColumn('tag', '')">All</button>
                                            @foreach ($tags as $tag)
                                                <button type="button" onclick="filterByColumn('tag', '{{ $tag->name }}')">{{ $tag->name }}</button>
                                            @endforeach
                                            </div>
                                        </div>
                                    </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr class="{{ $task->is_done ? 'task-done' : '' }}"
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
                                            <input type="checkbox" id="todo-{{ $task->id }}" class="todo" name="is_done" value="1" @checked($task->is_done) onchange="this.form.submit()">
                                            <label for="todo-{{ $task->id }}">{{ $task->title }}</label>
                                        </form>
                                    </td>
                                    <td>{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('M d, Y  |  g:i A') : '—' }}</td>
                                    <td>
                                        <span  class="priority {{ $task->priority }}">
                                        {{ $task->priority }}
                                        </span>
                                    </td>
                                    <td>
                                        @if ($task->tag)
                                            <span class="tag" style="background-color: {{ $task->tag->color }}">
                                                {{ $task->tag->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-dropdown">
                                            <button class="action-btn" onclick="toggle(event)" type="button">&#8942;</button>

                                            <div class="dropdown-choices">
                                                <button type="button" class="dropdown-item" onclick="document.getElementById('edit-popup-{{ $task->id }}').showModal()">Edit</button>
                                                <form class="delete-form" data-task-id="{{ $task->id }}" data-task-title="{{ $task->title }}" style="margin:0">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="dropdown-item delete" onclick="return confirm ('Are you sure you want to delete this task?') && handleDelete(this)">Delete</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- new task form popup -->
    <dialog id="form-popup" class="popup-modal">
        <button class="close-form" onclick="document.getElementById('form-popup').close()">X</button>
        <h2>Add a new Task</h2>
        <form action="/tasks" class="popup-form" method="POST">
            @csrf
            <label for="title">Task Name*</label>
            <input type="text" id="title" name="title" placeholder="Add New Task" required>

            <label for="due_at">Task Due Date and Time</label>
            <input type="datetime-local" id="due_at" name="due_at">
            <!-- im not sane enough to write js to validate date and time rn.. -->

            <label for="priority">Task Priority*</label>
            <select name="priority" required>
                <option disabled selected value>--Select Priority--</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}">
                        {{ $priority }}
                    </option>
                @endforeach
            </select>

            <label for="tag">Task Tag*</label>
            <select name="tag_id" id="tag_id" required>
                <option disabled selected value>--Select Tag/Category--</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>

            <button type="submit">Add</button>
        </form>
    </dialog>

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



</body>
</html>