<!-- resources/views/add-tag-modal.blade.php -->
<dialog id="tag-form-popup" class="popup-modal">
    <h2>Create Custom Tag</h2>
    <button type="button" class="close-form" onclick="document.getElementById('tag-form-popup').close()">✕</button>

    <form method="POST" action="{{ route('tags.store') }}" class="popup-form">
        @csrf

        <label for="tag-name">Tag Name</label>
        <input type="text" id="tag-name" name="name" required placeholder="e.g., Bug, Feature, Urgent">

        <label for="tag-color">Tag Color</label>
        <input type="color" id="tag-color" name="color" value="#3b82f6">

        <label>Select Icon</label>
        <div class="icon-grid">
            @php
                $icons = ['tag', 'clock', 'briefcase', 'home', 'envelope', 'star', 'academic-cap', 'exclamation-triangle', 'gift','user'];
            @endphp

            @foreach($icons as $icon)
                <label>
                    <input type="radio" name="icon" value="{{ $icon }}" required @checked($loop->first)>
                    <div class="icon-choice-box">
                        <x-dynamic-component :component="'heroicon-o-' . $icon" />
                    </div>
                </label>
            @endforeach
        </div>

        <button type="submit">Save Custom Tag</button>
    </form>
</dialog>
