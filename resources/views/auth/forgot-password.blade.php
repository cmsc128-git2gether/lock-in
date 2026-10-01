<x-guest-layout>
    <p class="auth-footer" style="margin-top:0; margin-bottom:1.25rem;">
        Enter your email and we'll send you a password reset link.
    </p>

    @if (session('status'))
        <div class="auth-status">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="auth-field">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
            @error('email')
                <div class="auth-error">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="auth-button">Email reset link</button>
    </form>
</x-guest-layout>