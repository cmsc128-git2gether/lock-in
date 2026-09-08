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
            <label>Description</label>
            <textarea name="description" placeholder="Description">{{$task->description}}</textarea>
        </div>

        <div>
            <label>Priority</label>
            <select name="priority">
                <option value="Unlabeled" {{ $task->priority == 'Unlabeled' ? 'selected' : '' }}>Unlabeled</option>
                <option value="Low" {{ $task->priority == 'Low' ? 'selected' : '' }}>Low</option>
                <option value="Medium" {{ $task->priority == 'Medium' ? 'selected' : '' }}>Medium</option>
                <option value="High" {{ $task->priority == 'High' ? 'selected' : '' }}>High</option>
            </select>
        </div>

        <div>
            <label>Due Date</label>
            <input type="datetime-local" name="due_at" value="{{ $task->due_at ? \Carbon\Carbon::parse($task->due_at)->format('Y-m-d\TH:i') : '' }}" />
        </div>

        <div>
            <button type="submit">Update Task</button>
        </div>

    </form>
    
</body>
</html>