<x-guest-layout class="login-bg">
    <p class="auth-footer" style="margin-top: 0; margin-bottom: 1.25rem; text-align: left;">
        Create a new password. It must have at least <strong>8 characters</strong>, including at least <strong>1 number</strong> and <strong>1 special character</strong>.
    </p>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">New Password</label>
            <div class="password-field">
                <input id="password" type="password" name="password" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">Show</button>
            </div>
            @foreach ($errors->get('password') as $message)
                    <div class="auth-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="auth-field">
            <label for="password_confirmation">Confirm Password</label>
            <div class="password-field">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">Show</button>
            </div>
            @error('password_confirmation')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-button">Reset Password</button>
    </form>
</x-guest-layout>
