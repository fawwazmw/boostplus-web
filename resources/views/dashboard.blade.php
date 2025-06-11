@extends('layouts.app')

@section('content')
    <div class="container py-5">
        {{-- Check if user is logged in --}}
        @if(session()->has('api_token') && session()->has('user'))
            {{-- LOGGED IN USER VIEW --}}

            {{-- Welcome Section --}}
            <div class="row mb-5">
                <div class="col-12">
                    <div class="card bg-primary text-white shadow-lg">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="flex-grow-1">
                                    <h2 class="mb-1">Selamat Datang, {{ $user->name ?? 'User' }}! 👋</h2>
                                    <p class="mb-0 opacity-75">Kelola top up game favoritmu dengan mudah</p>
                                </div>
                                <div class="text-end">
                                    <div class="dropdown">
                                        <button class="btn btn-outline-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-user-circle me-1"></i>
                                            {{ $user->name ?? 'User' }}
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Quick Actions --}}
            <div class="row mb-5">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 text-center hover-card">
                        <div class="card-body">
                            <i class="fas fa-plus-circle fa-3x text-primary mb-3"></i>
                            <h5 class="card-title">Top Up Baru</h5>
                            <p class="card-text text-muted">Beli diamond atau coin untuk game favoritmu</p>
                            <a href="{{ route('topup.index') }}" class="btn btn-primary">Mulai Top Up</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 text-center hover-card">
                        <div class="card-body">
                            <i class="fas fa-search fa-3x text-success mb-3"></i>
                            <h5 class="card-title">Cek ID Player</h5>
                            <p class="card-text text-muted">Verifikasi ID player sebelum melakukan top up</p>
                            <a href="{{ route('home') }}" class="btn btn-success">Cek Sekarang</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100 text-center hover-card">
                        <div class="card-body">
                            <i class="fas fa-trophy fa-3x text-warning mb-3"></i>
                            <h5 class="card-title">Leaderboard</h5>
                            <p class="card-text text-muted">Lihat top player dengan diamond terbanyak</p>
                            <a href="{{ route('home') }}" class="btn btn-warning">Lihat Ranking</a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction History --}}
            <div class="row">
                <div class="col-12">
                    <div class="card shadow-lg">
                        <div class="card-header bg-white py-3">
                            <h4 class="mb-0"><i class="fas fa-history me-2"></i>Riwayat Transaksi</h4>
                        </div>
                        <div class="card-body">
{{--                            @if(config('app.debug') && !empty($transactions))--}}
{{--                                <div class="alert alert-info">--}}
{{--                                    <small>Debug: Struktur data transaksi pertama:</small>--}}
{{--                                    <pre>{{ json_encode($transactions[0] ?? [], JSON_PRETTY_PRINT) }}</pre>--}}
{{--                                </div>--}}
{{--                            @endif--}}

                            @if(!empty($transactions))
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-dark">
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>Game</th>
                                            <th>ID Player</th>
                                            <th>Jumlah</th>
                                            <th>Status</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($transactions as $transaction)
                                            <tr>
                                                <td>
                                                    @php
                                                        // Coba berbagai kemungkinan field tanggal
                                                        $dateField = null;
                                                        $possibleDateFields = ['tanggal', 'created_at', 'date', 'transaction_date'];

                                                        foreach($possibleDateFields as $field) {
                                                            if (isset($transaction[$field])) {
                                                                $dateField = $transaction[$field];
                                                                break;
                                                            }
                                                        }
                                                    @endphp

                                                    @if($dateField)
                                                        {{ \Carbon\Carbon::parse($dateField)->format('d M Y, H:i') }}
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @php
                                                        // Coba berbagai kemungkinan field nama game
                                                        $gameField = $transaction['nama_game'] ??
                                                                    $transaction['game_name'] ??
                                                                    $transaction['game'] ??
                                                                    '-';
                                                    @endphp
                                                    {{ $gameField }}
                                                </td>

                                                <td>
                                                    @php
                                                        // Coba berbagai kemungkinan field ID akun
                                                        $playerIdField = $transaction['id_akun'] ??
                                                                       $transaction['player_id'] ??
                                                                       $transaction['game_player_id'] ??
                                                                       '-';
                                                    @endphp
                                                    {{ $playerIdField }}
                                                </td>

                                                <td>
                                                    @php
                                                        // Coba berbagai kemungkinan field jumlah
                                                        $amountField = $transaction['jumlah'] ??
                                                                      $transaction['amount'] ??
                                                                      $transaction['total'] ??
                                                                      $transaction['price'] ?? 0;
                                                    @endphp
                                                    Rp {{ number_format($amountField) }}
                                                </td>

                                                <td>
                                                    @php
                                                        $status = $transaction['status'] ?? 'unknown';
                                                    @endphp

                                                    @if($status == 'success' || $status == 'completed' || $status == 'paid')
                                                        <span class="badge bg-success">Success</span>
                                                    @elseif($status == 'pending' || $status == 'processing')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @elseif($status == 'failed' || $status == 'cancelled')
                                                        <span class="badge bg-danger">Failed</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Belum Ada Transaksi</h5>
                                    <p class="text-muted">Transaksi top up Anda akan muncul di sini</p>
                                    <a href="{{ route('topup.index') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Mulai Top Up
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- GUEST USER VIEW (LOGIN/REGISTER FORM) --}}

            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="text-center mb-5">
                        <h1 class="display-5 fw-bold">Dashboard</h1>
                        <p class="lead">Login atau daftar untuk mengakses dashboard dan riwayat transaksi Anda</p>
                    </div>

                    {{-- Auth Cards --}}
                    <div class="row">
                        {{-- Login Card --}}
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-lg h-100">
                                <div class="card-header bg-primary text-white text-center">
                                    <h4 class="mb-0"><i class="fas fa-sign-in-alt me-2"></i>Login</h4>
                                </div>
                                <div class="card-body p-4">
                                    @if(session('error'))
                                        <div class="alert alert-danger">{{ session('error') }}</div>
                                    @endif

                                    <form action="{{ route('login') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="login_email" class="form-label">Email</label>
                                            <input type="email" id="login_email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="login_password" class="form-label">Password</label>
                                            <input type="password" id="login_password" name="password"
                                                   class="form-control @error('password') is-invalid @enderror" required>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary btn-lg">
                                                <i class="fas fa-sign-in-alt me-2"></i>Login
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        {{-- Register Card --}}
                        <div class="col-lg-6 mb-4">
                            <div class="card shadow-lg h-100">
                                <div class="card-header bg-success text-white text-center">
                                    <h4 class="mb-0"><i class="fas fa-user-plus me-2"></i>Register</h4>
                                </div>
                                <div class="card-body p-4">
                                    <form action="{{ route('register') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label for="register_name" class="form-label">Nama Lengkap</label>
                                            <input type="text" id="register_name" name="name"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   value="{{ old('name') }}" required>
                                            @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="register_email" class="form-label">Email</label>
                                            <input type="email" id="register_email" name="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   value="{{ old('email') }}" required>
                                            @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="register_phone" class="form-label">No. Telepon</label>
                                            <input type="tel" id="register_phone" name="phone"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   value="{{ old('phone') }}" required>
                                            @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="register_password" class="form-label">Password</label>
                                            <input type="password" id="register_password" name="password"
                                                   class="form-control @error('password') is-invalid @enderror" required>
                                            @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label for="register_password_confirmation" class="form-label">Konfirmasi Password</label>
                                            <input type="password" id="register_password_confirmation"
                                                   name="password_confirmation" class="form-control" required>
                                        </div>

                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success btn-lg">
                                                <i class="fas fa-user-plus me-2"></i>Register
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Info Section --}}
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="card bg-light">
                                <div class="card-body text-center">
                                    <h5><i class="fas fa-info-circle text-info me-2"></i>Mengapa perlu login?</h5>
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <i class="fas fa-history fa-2x text-primary mb-2"></i>
                                            <p class="small">Lihat riwayat transaksi</p>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-shield-alt fa-2x text-success mb-2"></i>
                                            <p class="small">Transaksi lebih aman</p>
                                        </div>
                                        <div class="col-md-4">
                                            <i class="fas fa-user-cog fa-2x text-warning mb-2"></i>
                                            <p class="small">Kelola akun Anda</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <style>
        .hover-card {
            transition: transform 0.2s ease-in-out;
        }

        .hover-card:hover {
            transform: translateY(-5px);
        }

        .table th {
            border-top: none;
            font-weight: 600;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.5em 0.75em;
        }

        .card {
            border: none;
            border-radius: 15px;
        }

        .card-header {
            border-radius: 15px 15px 0 0 !important;
        }
    </style>
@endsection
