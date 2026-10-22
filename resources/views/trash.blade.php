    @extends('layouts.sidebar')

    @section('title', 'Recently Deleted')

    @section('content')
    <div class="main-content">
        <section class="card greeting-card">
            <div>
                <h1 class="greeting-title">Recently Deleted</h1>
                <p class="greeting-sub">Restore or permanently delete your old tasks.</p>
            </div>
            <div class="stats-metrics">
                <div class="stat">
                    <span class="stat-label">Trash</span>
                    <span class="stat-value">{{ $tasks->count() }}</span>
                </div>
            </div>
        </section>

        @if ($errors->any())
        <div style="background:#fee2e2; color:#991b1b; padding:12px; margin-bottom:12px; border-radius:6px;">
            <strong>ERROR:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <section class="card task-card">
            <div class="task-head">
                <h2 class="task-title">
                    Deleted Tasks <span class="count-pill" id="task-count">{{ $tasks->count() }}</span>
                </h2>
            </div>

            @if ($tasks->isEmpty())
                <p class="empty-message">Nothing to display here.</p>
            @else
                <table id="table-trash" class="trash-tasks">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Deleted At</th>
                            <th class="col-actions"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tasks as $task)
                        <tr>
                            <td>
                                <span>{{ $task->title }}</span>
                            </td>
                            <td class="due">
                                {{ $task->deleted_at->diffForHumans() }}
                            </td>
                            <td class="col-actions">
                                <div class="task-actions">
                                    <form method="POST" action="{{ url("/tasks/{$task->id}/restore") }}" style="display:inline">
                                        @csrf 
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline" style="padding: 4px 10px; font-size: 0.85rem;">Restore</button>
                                    </form>
                                    <form method="POST" action="{{ url("/tasks/{$task->id}/force") }}" style="display:inline"
                                        onsubmit="return confirm('Delete permanently? This action cannot be undone.')">
                                        @csrf 
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-dark" style="padding: 4px 10px; font-size: 0.85rem; background-color: #dc2626;">Delete forever</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </section>
    </div>
@endsection