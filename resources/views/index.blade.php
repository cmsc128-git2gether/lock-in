<!DOCTYPE html>
<html>
<head>
    <title>Todo App</title>
</head>
<body>
    <h1>My Todos</h1>

    <form action="/todos" method="POST">
            @csrf
            <input type="text" name="title" placeholder="Add New Task">
            <button type="submit">Add</button>
    </form>
    @if ($todos->isEmpty())
        <p>No todos yet.</p>
    @else
        
        <ul>
            @foreach ($todos as $todo)
                <li>
                    {{ $todo->title }}
                    @if ($todo->is_done)
                        DONE
                    @else
                        <form action="/todos/{{ $todo->id }}" method="POST" style="display:inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit">Mark Done</button>
                        </form>
                    @endif
                </li>
            @endforeach
        </ul>
    @endif
</body>
</html>