<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>BoostPlus - TopUp Game Cepat & Terpercaya</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"/>

    <!-- Custom CSS -->
    <style>
        /* Membuat body setidaknya setinggi layar dan menggunakan flexbox */
        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f7f6;
        }
        /* Konten utama akan mengisi sisa ruang yang tersedia */
        main {
            flex: 1;
        }
        /* Style untuk footer */
        .footer {
            background-color: #212529; /* Warna bg-dark */
            color: #ffffff;
        }
        .footer .social-links a {
            transition: opacity 0.2s;
        }
        .footer .social-links a:hover {
            opacity: 0.8;
        }
        .fadeIn {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    </style>
</head>
<body class="antialiased">

{{-- =================================== --}}
{{--          NAVBAR BARU DIMASUKKAN     --}}
{{-- =================================== --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="fas fa-rocket me-2"></i>BoostPlus
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i>Home
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('topup.index') ? 'active' : '' }}" href="{{ route('topup.index') }}">
                        <i class="fas fa-coins me-1"></i>Top Up
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                        <i class="fas fa-tachometer-alt me-1"></i>Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">
                        <i class="fas fa-info-circle me-1"></i>Tentang Kami
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Konten Utama Halaman --}}
<main class="py-4">
    @yield('content')
</main>

{{-- =================================== --}}
{{--          FOOTER BARU DIMASUKKAN     --}}
{{-- =================================== --}}
<footer class="footer py-4 mt-auto">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">BoostPlus</h5>
                <p class="small">Platform untuk top up game favoritmu secara cepat, aman, dan terpercaya.</p>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Quick Links</h5>
                <ul class="list-unstyled">
                    <li><a href="{{ route('home') }}" class="text-white text-decoration-none">Home</a></li>
                    <li><a href="{{ route('topup.index') }}" class="text-white text-decoration-none">Top Up</a></li>
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About Us</a></li>
                </ul>
            </div>
            <div class="col-md-4 mb-3">
                <h5 class="fw-bold">Connect With Us</h5>
                <div class="social-links">
                    <a href="#" class="text-white me-2"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="text-white me-2"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="text-white"><i class="fab fa-discord"></i></a>
                </div>
            </div>
        </div>
        <hr class="my-4" style="background-color: rgba(255,255,255,0.1);">
        <div class="text-center small">
            &copy; {{ date('Y') }} BoostPlus. All Rights Reserved.
        </div>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
