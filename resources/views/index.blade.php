<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
    <style></style>
</head>
<body>
    <h1>My Todos</h1>

    <form action="/tasks" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Add New Task">
            <button type="submit">Add</button>
    </form>
    @if ($tasks->isEmpty())
        <p>No tasks yet.</p>
    @else
        
        <ul>
            @foreach ($tasks as $task)
                <li>
                    {{ $task->title }}
                    @if ($task->is_done)
                        DONE
                    @else
                        <form action="/tasks/{{ $task->id }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Mark Done</button>
                            <a href="/tasks/{{ $task->id }}/edit">
                                Edit
                            </a>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif

    
</body>
</html>