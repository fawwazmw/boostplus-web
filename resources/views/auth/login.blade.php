@extends('layouts.app')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0">Login</h3>
                    </div>
                    <div class="card-body p-5">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            @if ($errors->has('email'))
                                <div class="alert alert-danger">{{ $errors->first('email') }}</div>
                            @endif

                            <div class="mb-4">
                                <label for="email" class="form-label">Alamat Email</label>
                                <input id="email" type="email" class="form-control form-control-lg" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input id="password" type="password" class="form-control form-control-lg" name="password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    Login
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center">
                        <small>Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
