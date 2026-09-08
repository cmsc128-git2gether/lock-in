<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    @vite(['resources/css/app.css'])
</head>
<body>
    <div class="page-container">
        <div class="header">
            <h1>My Todos</h1>
        </div>

        <div class="main-content">
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
                                            <input type="checkbox" class="todo" name="is_done" value="1" @checked($task->is_done) onchange="this.form.submit()">
                                            <label for="todo-{{ $task->id }}">{{ $task->title }}</label>
                                        </form>
                                    </td>
                                    <td>{{ $task->due_at }}</td>
                                    <td>{{ $task->priority }}</td>
                                    <td>{{ $task->tag }}</td>
                                    <td></td>
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
            <label for="title">Task Name*</label>
            <input type="text" id="title" name="title" placeholder="Add New Task" required>

            <label for="due_at">Task Due Date and Time</label>
            <input type="datetime-local" id="due_at" name="due_at">
            <!-- im not sane enough to write js to validate date and time rn.. -->

            <label for="priority">Task Priority*</label>
            <select name="priority" id="priority" required>
                <option disabled selected value>Select Task Priority</option>
                <option value="Unlabeled">N/A</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>

            <label for="tag">Task Tag * (NOT WORKING)</label>
            <select name="tag" id="tag" required>
                <option disabled selected value>Select Task Tag</option>
                <option value="School">School</option>
                <option value="Personal">Personal</option>
                <option value="Others">Others</option>
            </select>

            <button type="submit">Add</button>
        </form>
    </dialog>
</body>
</html>