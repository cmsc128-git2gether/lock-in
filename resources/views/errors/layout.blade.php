{{-- error page when user accidentally gets to it --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    @vite(['resources/css/app.css'])
</head>
<body class="app-bg" style="display:grid;place-items:center;padding:1.5rem">
    <main class="card" style="max-width:420px;padding:2rem;text-align:center">
        <p class="stat-value" style="font-size:2.5rem;color:var(--color-accent)">{{ $code }}</p>
        <h1 class="greeting-title">{{ $title }}</h1>
        <p class="greeting-sub" style="margin:.5rem 0 1.5rem">{{ $message }}</p>
        <a href="{{ url('/home') }}" class="btn btn-dark">Back to dashboard</a>
    </main>
</body>
</html>