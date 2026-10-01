<div class="header">
    <a href="{{ route('dashboard') }}" style="color:white; text-decoration:none;">
        <h1>My Todos</h1>
    </a>

    <div class="user-info">
        <a href="{{ route('dashboard') }}" class="nav-link">Home</a>
        <a href="{{ route('profile.edit') }}" class="nav-link">Profile</a>
        <span>{{ Auth::user()->name }}</span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Log Out</button>
        </form>
    </div>
</div>