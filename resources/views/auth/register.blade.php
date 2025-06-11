@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Buat Akun Baru</h3>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div class="mb-3">
                                <label for="name" class="form-label">Nama Lengkap</label>
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Nomor Telepon</label>
                                <input id="phone" type="text" class="form-control" name="phone" value="{{ old('phone') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>

                            <div class="mb-4">
                                <label for="password-confirm" class="form-label">Konfirmasi Password</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Register
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
