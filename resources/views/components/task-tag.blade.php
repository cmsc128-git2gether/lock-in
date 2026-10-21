@if ($task->tag)
    @php
        $hexColor = $task->tag->color ?? '#3b82f6'; 
        $iconKey = "heroicon-o-" . ($task->tag->icon ?? 'tag');
    @endphp
    

    <span class="custom-tag" style="background-color: {{ $hexColor }}20; color: {{ $hexColor }};">
        
        <div class="custom-tag-icon">
            @svg($iconKey, 'w-full h-full', ['style' => "stroke: $hexColor; stroke-width: 2;"])
        </div>

        <span>{{ $task->tag->name }}</span>
    </span>
@else
    <span class="text-gray-400">—</span>
@endif
