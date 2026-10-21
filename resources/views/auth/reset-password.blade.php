<x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
            @foreach ($errors->get('email') as $message)
                <div class="auth-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="auth-field">
            <label for="password">New Password</label>
            <div class="password-field">
                <input id="password" type="password" name="password" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">Show</button>
            </div>
            <small class="auth-hint">At least 8 characters, 1 number, 1 special character.</small>
            @foreach ($errors->get('password') as $message)
                <div class="auth-error">{{ $message }}</div>
            @endforeach
        </div>

        <div class="auth-field">
            <div class="password-field">
                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation', this)">Show</button>
            </div>
        </div>
        @foreach ($errors->get('password_confirmation') as $message)
            <div class="auth-error">{{ $message }}</div>
        @endforeach

        <button type="submit" class="auth-button">Reset password</button>
    </form>
</x-guest-layout>