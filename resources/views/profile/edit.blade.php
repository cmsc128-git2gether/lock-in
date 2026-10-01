<x-app-layout class="default-bg">
    <a href="{{ route('dashboard') }}" class="back-link">← Back to Todos</a>
    <div class="profile-card">
        <h2>Profile Information</h2>
        <p class="profile-desc">Update your name and email address.</p>
        @include('profile.partials.update-profile-information-form')
    </div>

    <div class="profile-card">
        <h2>Update Password</h2>
        <p class="profile-desc">Use a long, random password to stay secure.</p>
        @include('profile.partials.update-password-form')
    </div>
</x-app-layout>