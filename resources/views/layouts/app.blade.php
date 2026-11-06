<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header class="topbar">
        <a class="brand" href="{{ auth()->check() ? route('dashboard') : route('login') }}">Ruang Daftar<span>.</span></a>
        @auth
            <nav class="main-nav" aria-label="Navigasi utama">
                <a href="{{ route('dashboard') }}">Beranda</a>
                <a href="{{ route('pendaftaran.index') }}">Pendaftaran</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('usermanagement.index') }}">Pengguna</a>
                @endif
            </nav>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="button button-quiet" type="submit">Keluar</button>
            </form>
        @else
            <nav class="main-nav" aria-label="Navigasi akun">
                <a href="{{ route('login') }}">Masuk</a>
                <a class="button button-small" href="{{ route('register') }}">Daftar</a>
            </nav>
        @endauth
    </header>
    <main class="page-shell">
        @if (session('status'))
            <p class="notice notice-success" role="status">{{ session('status') }}</p>
        @endif
        @yield('content')
    </main>
    @stack('scripts')
</body>
</html>