<dialog id="form-popup" class="popup-modal">
    <h2>Create New Task</h2>
    <button type="button" class="close-form" onclick="document.getElementById('form-popup').close()">✕</button>

    <form method="POST" action="/tasks" class="popup-form">
        @csrf

        <label for="task-title">Task Name</label>
        <input type="text" id="task-title" name="title" required placeholder="What needs to be done?">

        <label for="task-due">Due Date and Time</label>
        <input type="datetime-local" id="task-due" name="due_at">

        <label for="task-priority">Priority</label>
        <select id="task-priority" name="priority" required>
            @isset($priorities)
                @foreach ($priorities as $priority)
                    <option value="{{ $priority }}">{{ $priority }}</option>
                @endforeach
            @endisset
        </select>

        <label for="task-tag">Assign Tag</label>
        <select id="task-tag" name="tag_id">
            <option value="">No Tag</option>
            @isset($tags)
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                @endforeach
            @endisset
        </select>

        <button type="submit">Save Task</button>
    </form>
</dialog>
