<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Itihasa - Museum Ticketing')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    @stack('styles')
</head>
<body>
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">Itihasa</a>
        <div class="nav-links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('booking') }}">Book Tickets</a>
            <a href="{{ route('about') }}">About</a>
            @auth
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">Dashboard</a>
                @elseif(auth()->user()->role === 'staff')
                    <a href="{{ route('petugas.dashboard') }}">Dashboard</a>
                @endif
                <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" style="background: none; border: none; color: white; cursor: pointer; font-size: 1rem;">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}">Login</a>
            @endauth
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <p><span>Itihasa</span> © 2024 Itihasa Museum Ticketing. All rights reserved.</p>
    </footer>

    <script src="{{ asset('js/main.js') }}"></script>
    @stack('scripts')
</body>
</html>