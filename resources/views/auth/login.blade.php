<x-guest-layout>
    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="password-field">
                <input id="password" type="password" name="password" required autocomplete="current-password">
            <button type="button" class="password-toggle" onclick="togglePassword('password', this)">Show</button>
                @error('password')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-checkbox-row">
            <label>
                <input type="checkbox" name="remember">
                Remember me
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="auth-button">Log in</button>

        <p class="auth-footer">
            Don't have an account? <a href="{{ route('register') }}">Sign up</a>
        </p>
    </form>
</x-guest-layout>