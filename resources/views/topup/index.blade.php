@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="text-center mb-5">
            <h1 class="display-5 fw-bold">Pilih Paket Top Up</h1>
            <p class="lead">Pilih paket favoritmu dan masukkan ID Player untuk melanjutkan.</p>
        </div>

        @if(session('error'))
            <div class="alert alert-danger text-center">{{ session('error') }}</div>
        @endif

        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif

        {{-- Peringatan untuk guest user --}}
        @if(!session()->has('api_token') || !session()->has('user'))
            <div class="alert alert-warning text-center mb-4">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <strong>Perhatian!</strong> Anda dapat melihat paket yang tersedia, tetapi harus
                <a href="{{ route('dashboard') }}" class="alert-link">login terlebih dahulu</a> untuk melakukan pembelian.
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-body p-4">
                        <form action="{{ route('topup.store') }}" method="POST" id="topupForm">
                            @csrf
                            <div class="mb-4">
                                <label for="game_player_id" class="form-label fs-5">1. Masukkan ID Player Game</label>
                                <input type="text" id="game_player_id" name="game_player_id"
                                       class="form-control form-control-lg @error('game_player_id') is-invalid @enderror"
                                       placeholder="Contoh: 12345678"
                                       value="{{ old('game_player_id') }}"
                                    {{ session()->has('api_token') && session()->has('user') ? 'required' : 'readonly' }}>
                                @error('game_player_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror

                                @if(!session()->has('api_token') || !session()->has('user'))
                                    <div class="form-text text-muted">
                                        <i class="fas fa-lock me-1"></i>Login diperlukan untuk melakukan top up
                                    </div>
                                @endif
                            </div>

                            <div class="mb-4">
                                <label class="form-label fs-5">2. Pilih Nominal Top Up</label>

                                {{-- Debug info untuk melihat data packages --}}
{{--                                @if(config('app.debug'))--}}
{{--                                    <div class="alert alert-info">--}}
{{--                                        <small>Debug: Total packages = {{ count($packages) }}</small>--}}
{{--                                        @if(count($packages) > 0)--}}
{{--                                            <br><small>First package: {{ json_encode(collect($packages)->first()) }}</small>--}}
{{--                                        @endif--}}
{{--                                    </div>--}}
{{--                                @endif--}}

                                <div class="row row-cols-2 row-cols-md-3 g-3">
                                    @forelse ($packages as $package)
                                        <div class="col">
                                            <label class="card h-100 text-center package-card {{ session()->has('api_token') && session()->has('user') ? '' : 'package-disabled' }}">
                                                <input type="radio" name="package_id"
                                                       value="{{ is_array($package) ? $package['id'] : $package->id }}"
                                                       class="d-none package-radio"
                                                    {{ session()->has('api_token') && session()->has('user') ? 'required' : 'disabled' }}
                                                    {{ old('package_id') == (is_array($package) ? $package['id'] : $package->id) ? 'checked' : '' }}>
                                                <div class="card-body">
                                                    <h5 class="card-title">
                                                        {{ is_array($package) ? $package['diamonds'] : $package->diamonds }}
                                                        <i class="far fa-gem text-primary"></i>
                                                    </h5>
                                                    <p class="card-text text-muted">
                                                        Rp {{ number_format(is_array($package) ? $package['price'] : $package->price) }}
                                                    </p>
                                                    @if(!session()->has('api_token') || !session()->has('user'))
                                                        <div class="overlay">
                                                            <i class="fas fa-lock"></i>
                                                        </div>
                                                    @endif
                                                </div>
                                            </label>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="alert alert-warning text-center">
                                                <i class="fas fa-exclamation-triangle me-2"></i>
                                                Paket tidak tersedia saat ini. Silakan coba lagi nanti.
                                            </div>
                                        </div>
                                    @endforelse
                                </div>
                                @error('package_id')
                                <div class="text-danger mt-2">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid mt-4">
                                @if(session()->has('api_token') && session()->has('user'))
                                    <button type="submit" class="btn btn-primary btn-lg"
                                        {{ count($packages) == 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-shopping-cart me-2"></i>
                                        Beli Sekarang
                                    </button>
                                @else
                                    <a href="{{ route('dashboard') }}" class="btn btn-warning btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>
                                        Login untuk Melakukan Top Up
                                    </a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>

                {{-- Login Reminder Card for Guests --}}
                @if(!session()->has('api_token') || !session()->has('user'))
                    <div class="card mt-4 bg-light">
                        <div class="card-body text-center">
                            <h5><i class="fas fa-user-plus text-primary me-2"></i>Belum punya akun?</h5>
                            <p class="text-muted mb-3">Daftar sekarang untuk mendapatkan akses penuh ke semua fitur</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                                    <i class="fas fa-sign-in-alt me-1"></i>Login
                                </a>
                                <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-user-plus me-1"></i>Register
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .package-card {
            cursor: pointer;
            border: 2px solid #e9ecef;
            transition: all 0.2s ease-in-out;
            position: relative;
        }

        .package-card:hover:not(.package-disabled) {
            border-color: #0d6efd;
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        .package-radio:checked + .card-body {
            background-color: #cfe2ff;
        }

        .package-radio:checked + .card-body .card-title {
            color: #0d6efd;
            font-weight: bold;
        }

        .package-card:has(.package-radio:checked) {
            border-color: #0d6efd;
            background-color: #f8f9ff;
        }

        /* Styling for disabled packages (guest users) */
        .package-disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .package-disabled .card-body {
            position: relative;
        }

        .package-disabled .overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: #6c757d;
            border-radius: inherit;
        }

        .package-disabled:hover {
            transform: none;
            box-shadow: none;
        }

        /* Animation for selected package */
        .package-card.selected {
            animation: pulseSelection 0.3s ease-in-out;
        }

        @keyframes pulseSelection {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1.02); }
        }

        /* Notification style for guest users */
        .guest-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
            min-width: 300px;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if user is logged in
            const isLoggedIn = {{ session()->has('api_token') && session()->has('user') ? 'true' : 'false' }};

            if (isLoggedIn) {
                // Handle package selection visual feedback for logged in users
                const packageRadios = document.querySelectorAll('.package-radio');
                const packageCards = document.querySelectorAll('.package-card');

                packageRadios.forEach((radio, index) => {
                    radio.addEventListener('change', function() {
                        // Reset all cards
                        packageCards.forEach(card => {
                            card.classList.remove('selected');
                        });

                        // Highlight selected card
                        if (this.checked) {
                            packageCards[index].classList.add('selected');
                        }
                    });
                });

                // Form validation
                const form = document.getElementById('topupForm');
                form.addEventListener('submit', function(e) {
                    const selectedPackage = document.querySelector('.package-radio:checked');
                    const gamePlayerId = document.getElementById('game_player_id').value.trim();

                    if (!selectedPackage) {
                        e.preventDefault();
                        showNotification('Silakan pilih paket terlebih dahulu!', 'warning');
                        return false;
                    }

                    if (!gamePlayerId) {
                        e.preventDefault();
                        showNotification('Silakan masukkan ID Player Game!', 'warning');
                        document.getElementById('game_player_id').focus();
                        return false;
                    }

                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Memproses...';

                    // Re-enable button after 10 seconds as fallback
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }, 10000);
                });

                // Auto-save form data to prevent loss
                const gamePlayerIdInput = document.getElementById('game_player_id');
                gamePlayerIdInput.addEventListener('input', function() {
                    localStorage.setItem('topup_game_player_id', this.value);
                });

                // Restore saved data
                const savedPlayerId = localStorage.getItem('topup_game_player_id');
                if (savedPlayerId && !gamePlayerIdInput.value) {
                    gamePlayerIdInput.value = savedPlayerId;
                }

            } else {
                // Handle clicks for guest users - redirect to login
                const packageCards = document.querySelectorAll('.package-card');
                const gamePlayerIdInput = document.getElementById('game_player_id');

                packageCards.forEach(card => {
                    card.addEventListener('click', function(e) {
                        e.preventDefault();
                        showGuestNotification();
                    });
                });

                // Show notification when trying to interact with disabled inputs
                gamePlayerIdInput.addEventListener('focus', function() {
                    showGuestNotification();
                    this.blur();
                });

                gamePlayerIdInput.addEventListener('click', function() {
                    showGuestNotification();
                });
            }

            // Utility functions
            function showNotification(message, type = 'info') {
                const alertClass = type === 'warning' ? 'alert-warning' :
                    type === 'success' ? 'alert-success' : 'alert-info';

                const notification = document.createElement('div');
                notification.className = `alert ${alertClass} alert-dismissible fade show guest-notification`;
                notification.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;

                document.body.appendChild(notification);

                // Auto remove after 5 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 5000);
            }

            function showGuestNotification() {
                const notification = document.createElement('div');
                notification.className = 'alert alert-warning alert-dismissible fade show guest-notification';
                notification.innerHTML = `
                    <strong><i class="fas fa-lock me-2"></i>Login Diperlukan</strong><br>
                    Silakan login terlebih dahulu untuk melakukan top up.
                    <div class="mt-2">
                        <a href="{{ route('dashboard') }}" class="btn btn-sm btn-primary me-2">
                            <i class="fas fa-sign-in-alt me-1"></i>Login
                        </a>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;

                // Remove existing notifications
                const existingNotifications = document.querySelectorAll('.guest-notification');
                existingNotifications.forEach(notif => notif.remove());

                document.body.appendChild(notification);

                // Auto remove after 8 seconds
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 8000);
            }

            // Handle package card hover effects
            const packageCards = document.querySelectorAll('.package-card:not(.package-disabled)');
            packageCards.forEach(card => {
                card.addEventListener('mouseenter', function() {
                    this.style.boxShadow = '0 8px 16px rgba(0,0,0,0.15)';
                });

                card.addEventListener('mouseleave', function() {
                    if (!this.classList.contains('selected')) {
                        this.style.boxShadow = '';
                    }
                });
            });

            // Clear saved data when form is successfully submitted
            window.addEventListener('beforeunload', function() {
                // Only clear if form was submitted successfully
                if (document.querySelector('.alert-success')) {
                    localStorage.removeItem('topup_game_player_id');
                }
            });
        });
    </script>
@endsection
