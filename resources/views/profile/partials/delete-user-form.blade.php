<button type="button" class="profile-delete-btn" onclick="document.getElementById('delete-account-popup').showModal()">
    Delete Account
</button>

<dialog id="delete-account-popup" class="popup-modal">
    <button class="close-form" onclick="document.getElementById('delete-account-popup').close()">X</button>
    <h2>Delete Account</h2>
    <p class="profile-desc">Enter your password to confirm. This cannot be undone.</p>

    <form method="POST" action="{{ route('profile.destroy') }}" class="popup-form">
        @csrf
        @method('DELETE')

        <label for="delete_password">Password</label>
        <input id="delete_password" type="password" name="password" required>
        @error('password', 'userDeletion')
            <div class="auth-error">{{ $message }}</div>
        @enderror

        <button type="submit" class="profile-delete-btn">Delete Account</button>
    </form>
</dialog>