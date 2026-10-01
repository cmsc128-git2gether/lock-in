@if (session('status') === 'password-updated')
    <div class="profile-status">Password updated.</div>
@endif

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="profile-field">
        <label for="update_password_current_password">Current Password</label>
        <div class="password-field">
            <input id="update_password_current_password" type="password" name="current_password" autocomplete="current-password">
            <button type="button" class="password-toggle" onclick="togglePassword('update_password_current_password', this)">Show</button>
        </div>
        @error('password', 'updatePassword')
            <div class="auth-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="profile-field">
        <label for="update_password_password">New Password</label>
        <div class="password-field">
            <input id="update_password_password" type="password" name="password" autocomplete="new-password">
            <button type="button" class="password-toggle" onclick="togglePassword('update_password_password', this)">Show</button>
        </div>
        @error('password', 'updatePassword')
            <div class="auth-error">{{ $message }}</div>
        @enderror
    </div>

    <div class="profile-field">
        <label for="update_password_password_confirmation">Confirm Password</label>
        <div class="password-field">
            <input id="update_password_password_confirmation" type="password" name="password_confirmation" autocomplete="new-password">
            <button type="button" class="password-toggle" onclick="togglePassword('update_password_password_confirmation', this)">Show</button>
        </div>
    </div>

    <button type="submit" class="profile-save-btn">Save</button>
</form>