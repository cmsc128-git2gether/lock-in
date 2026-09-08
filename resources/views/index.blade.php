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
                <div class=task-header>
                    <h1> Active Tasks ({{ $tasks->count() }}) </h1>
                    <div class="add-task-form">
                        <form action="/tasks" method="POST">
                        @csrf
                        <input type="text" name="title" placeholder="Add New Task">
                        <button type="submit">Add</button>
                    </form>
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
                    </tr>
                    @endforeach
                    </tbody>
                </table>
                @endif
            </div>
    </div>
</div>
    </div>
    </div>
</body>
</html>