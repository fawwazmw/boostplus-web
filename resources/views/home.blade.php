@extends('layouts.app')

@section('content')
    <div class="container py-5">
        {{-- Hero Section untuk form pencarian --}}
        <div class="hero-section p-5 mb-5 text-center bg-light rounded-3 shadow-sm">
            <h1 class="display-4 fw-bold mb-4 text-black">Cek ID Game Anda</h1>
            <p class="lead mb-5 text-black">Temukan nama pemain berdasarkan ID yang terdaftar di sistem kami.</p>

            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="search-box p-4 border rounded">
                        <form action="{{ route('player.search') }}" method="POST">
                            @csrf
                            <div class="input-group mb-3">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                                <input type="text" class="form-control form-control-lg"
                                       placeholder="Masukkan ID Player Game"
                                       name="game_player_id"
                                       required>
                                <button class="btn btn-primary" type="submit">Cek Sekarang</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================================================================== --}}
        {{-- BAGIAN INI DITAMBAHKAN UNTUK MENAMPILKAN HASIL ATAU ERROR PENCARIAN --}}
        {{-- ================================================================== --}}
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                {{-- Tampilkan blok ini jika pencarian BERHASIL --}}
                @if(session('account'))
                    @php $account = session('account'); @endphp
                    <div class="card shadow-sm fadeIn">
                        <div class="card-header bg-success text-white">
                            <strong>Akun Ditemukan!</strong>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless mb-0">
                                <tbody>
                                <tr>
                                    <th style="width: 30%;">ID Akun</th>
                                    <td>: {{ $account->game_id }}</td>
                                </tr>
                                <tr>
                                    <th>Nickname</th>
                                    <td>: {{ $account->nickname }}</td>
                                </tr>
                                <tr>
                                    <th>Game</th>
                                    <td>: {{ $account->game_name }}</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                {{-- Tampilkan blok ini jika pencarian GAGAL --}}
                @if(session('error'))
                    <div class="alert alert-danger text-center fadeIn">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>


        {{-- Features Section --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="feature-card p-4 text-center">
                    <i class="fas fa-bolt hero-icon mb-3"></i>
                    <h3>Top Up Instan</h3>
                    <p>Proses transaksi cepat dan otomatis masuk ke akun game Anda.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 text-center">
                    <i class="fas fa-shield-alt hero-icon mb-3"></i>
                    <h3>Aman & Terpercaya</h3>
                    <p>Keamanan transaksi Anda adalah prioritas utama kami.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card p-4 text-center">
                    <i class="fas fa-tags hero-icon mb-3"></i>
                    <h3>Harga Terbaik</h3>
                    <p>Dapatkan penawaran dan paket diamond dengan harga paling kompetitif.</p>
                </div>
            </div>
        </div>

        {{-- Top Players Table --}}
        @if(!empty($topPlayers))
            <div class="row mb-5">
                <div class="col-12">
                    <h2 class="text-center mb-4">Players Terbaru yang Terdaftar</h2>
                    <div class="table-responsive bg-white rounded-3 shadow">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                            <tr>
                                <th>No.</th>
                                <th>ID Player</th>
                                <th>Nickname</th>
                                <th>Game</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($topPlayers as $index => $player)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $player['id_akun'] }}</td>
                                    <td>{{ $player['nickname'] }}</td>
                                    <td>{{ $player['nama_game'] }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
