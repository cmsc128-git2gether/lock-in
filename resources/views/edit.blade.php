<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form action="/tasks/{{$task->id}}/submit" method="POST">
        @csrf
        @method('PATCH')

        <div>
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="{{$task->title}}" />
        </div>

        <div>
            <label>Priority</label>
            <select name="priority">
                <option value="" disabled hidden {{ is_null($task->priority) ? 'selected' : '' }}>--Select Priority--</option>
                @foreach($priorities as $priority)
                    <option value="{{ $priority }}" {{ $task->priority == $priority ? 'selected' : '' }}>
                        {{ $priority }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label>Due Date</label>
            <input type="datetime-local" name="due_at" value="{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('Y-m-d\TH:i') : '' }}" />
        </div>

        <div>
            <label for="tag_id">Tag</label>
            <select name="tag_id" id="tag_id">
                <option value="" disabled hidden {{ is_null($task->tag_id) ? 'selected' : '' }}>
                    --Select Tag/Category--
                </option>

                @foreach($tags as $tag)
                    <option value="{{ $tag->id }}" {{ $task->tag_id == $tag->id ? 'selected' : '' }}>
                        {{ $tag->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <button type="submit">Update Task</button>
        </div>

    </form>
    
</body>
</html>