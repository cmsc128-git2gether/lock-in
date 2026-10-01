<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Todos</title>
    @vite(['resources/css/app.css'])
</head>
<body class="welcome-body">
    <div class="welcome-card">
        <h1 class="welcome-title">Lock the fuck in</h1>
        <p class="welcome-subtitle">JUST DO IT.</p>

        <div class="welcome-actions">
            <a href="{{ route('login') }}" class="welcome-btn welcome-btn-primary">Log In</a>
            <a href="{{ route('register') }}" class="welcome-btn welcome-btn-secondary">Sign Up</a>
        </div>
    </div>
</body>
</html>