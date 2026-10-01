<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Todo App</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="auth-body">
    <div class="auth-wrapper">
        <div class="auth-card">
            <h1 class="auth-logo">My Todos</h1>
            {{ $slot }}
        </div>
    </div>
</body>
</html>