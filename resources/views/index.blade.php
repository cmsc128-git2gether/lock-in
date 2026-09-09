<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
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
        <strong>Please fix the following:</strong>
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
                                    <th>Task</th>
                                    <th>Due Date and Time</th>
                                    <th>Priority</th>
                                    <th>Tag</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($tasks as $task)
                                <tr class="{{ $task->is_done ? 'task-done' : '' }}">
                                    <td>
                                        <form action="/tasks/{{ $task->id }}" method="POST" class="checkbox-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="is_done" value="0">
                                            <input type="checkbox" id="todo-{{ $task->id }}" class="todo" name="is_done" value="1" @checked($task->is_done) onchange="this.form.submit()">
                                            <label for="todo-{{ $task->id }}">{{ $task->title }}</label>
                                        </form>
                                    </td>
                                    <td>{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('M d, Y g:i A') : '—' }}</td>
                                    <td>{{ $task->priority }}</td>
                                    <td>
                                        @if ($task->tag)
                                            <span style="background-color: {{ $task->tag->color }}">
                                                {{ $task->tag->name }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="action-dropdown">
                                            <button class="action-btn" onclick="toggle(event)" type="button">&#8942;</button>

                                            <div class="dropdown-choices">
                                                <a href="/tasks/{{ $task->id }}/edit" class="dropdown-item">Edit</a>
                                                <form action="/tasks/{{ $task->id }}/destroy" method="POST" style="margin:0" onsubmit="return confirm('Are you sure you want to delete this task?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item delete">Delete</button>
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
        <button class="close-form" onclick="document.getElementById('form-popup').close()">✕</button>
        <form action="/tasks" class="popup-form" method="POST">
            @csrf
            <label for="title">Task Name</label>
            <input type="text" id="title" name="title" placeholder="Add New Task" required>

            <label for="due_at">Task Due Date and Time</label>
            <input type="datetime-local" id="due_at" name="due_at">
            <!-- im not sane enough to write js to validate date and time rn.. -->

            <label for="priority">Task Priority</label>
            <select name="priority" required>
                <option disabled selected value>--Select Priority--</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}">
                        {{ $priority }}
                    </option>
                @endforeach
            </select>

            <label for="tag">Task Tag</label>
            <select name="tag_id" id="tag_id" required>
                <option disabled selected value>--Select Tag/Category--</option>
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            </select>

            <button type="submit">Add</button>
        </form>
    </dialog>
</body>
</html>