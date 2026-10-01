@if (session('status') === 'profile-updated')
    <div class="profile-status">Saved.</div>
@endif

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="profile-field">
        <label for="name">Name</label>
        <input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
        @error('name')
            <div class="auth-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="profile-field">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" required>
        @error('email')
            <div class="auth-error">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="profile-save-btn">Save</button>
</form>