<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>My Todos</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body {{ $attributes->merge(['class' => 'app-body']) }}>
    <main class="main-content">
        @isset($header)
            <h1 class="page-title">{{ $header }}</h1>
        @endisset
        {{ $slot }}
    </main>
</body>
</html>