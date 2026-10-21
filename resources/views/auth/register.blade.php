<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="auth-field">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
            @error('name')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
            @foreach ($errors->get('email') as $message)
                <div class="auth-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="auth-field">
            <label for="password">Password</label>
            <div class="password-field">
                <input id="password" type="password" name="password" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password', this)">Show</button>
            </div>
            <small class="auth-hint">At least 8 characters, 1 number, 1 special character.</small>
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
            @foreach ($errors->get('password_confirmation') as $message)
                <div class="auth-error">{{ $message }}</div>
            @endforeach
        </div>

        <button type="submit" class="auth-button">Create account</button>

        <p class="auth-footer">
            Already registered? <a href="{{ route('login') }}">Log in</a>
        </p>
    </form>
</x-guest-layout>