<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form action="/tasks/{{$task->id}}" method="POST">
        @csrf
        @method('PUT')

        <div>
            <label>Title</label>
            <input type="text" name="title" placeholder="Title" value="{{$task->title}}" />
        </div>

        <div>
            <label>Description</label>
            <textarea type="text" name="description" placeholder="Description" value="{{$task->description}}" />
            </textarea>
        </div>

        <div>
            <label>Priority</label>
            <select name="priority">
                <option value="Unlabeled">Unlabeled</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <div>
            <label>Due Date</label>
            <input type="datetime-local" name="due_at" placeholder="Due At" value="{{$task->due_at}}" />
        </div>

        <div>
            <button type="submit">Update Task</button>
        </div>

    </form>
    
</body>
</html>