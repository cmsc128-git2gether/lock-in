<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
</head>
<body>

    <nav class="header-nav">
        <a href="{{ route('dashboard') }}" class="back-link">← Back to Todos</a>
    </nav>
    <h1>Recently Deleted</h1>

    @forelse ($tasks as $task)
        <div class="task-row">
            <span>{{ $task->title }}</span>
            <small>Deleted {{ $task->deleted_at->diffForHumans() }}</small>

            <form method="POST" action="/tasks/{{ $task-> id }}/restore" style="display:inline">
                @csrf
                @method('PATCH')
                <button type="submit">Restore</button>
            </form>

            <form method="POST" action="/tasks/{{ $task-> id }}/force" style="display:inline"
                onsubmit="return confirm('Delete permanently? This action cannot be undone.')">
                @csrf
                @method('DELETE')
                <button type="submit">Delete forever</button>
            </form>
        </div>
    @empty
        <p>Nothing to display here.</p>
    @endforelse
</body>
</html>