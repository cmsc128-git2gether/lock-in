@extends('layouts.sidebar')

@section('title', 'Edit Profile')

@section('content')
    <div class="main-content">
        <section class="card greeting-card">
            <div>
                <h1 class="greeting-title">Profile Settings</h1>
                <p class="greeting-sub">Manage your account details and password.</p>
            </div>
        </section>

        <section class="card task-card">
            <h2 class="task-title" style="margin-bottom: 0.25rem;">Profile Information</h2>
            <p class="profile-desc">Update your name and email address. Changing your email will require logging in again.</p>
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="card task-card">
            <h2 class="task-title" style="margin-bottom: 0.25rem;">Update Password</h2>
            <p class="profile-desc">Password must have at least 8 characters, with 1 special character and 1 number. Changing your password will log you out.</p>
            @include('profile.partials.update-password-form')
        </section>
    </div>
@endsection
