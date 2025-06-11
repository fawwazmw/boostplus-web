<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use App\Services\BackendApiService;

class AuthController extends Controller
{
    protected $apiService;

    public function __construct(BackendApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    // MENAMPILKAN VIEW
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    // PROSES LOGIC
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $response = $this->apiService->login($request->email, $request->password);

        if ($response && $response->successful() && $response->json('success')) {
            $token = $response->json('access_token');
            $user = (object) $response->json('data');

            // Log sebelum menyimpan session
            Log::info('Login Success: Preparing to save session.', [
                'token' => $token,
                'user_name' => $user->name,
            ]);

            // Simpan data ke session
            session([
                'api_token' => $token,
                'user' => $user,
            ]);

            // Pastikan session tersimpan dengan benar
            session()->save();

            // Log setelah menyimpan session
            Log::info('Session data saved successfully.', [
                'session_has_token' => session()->has('api_token'),
                'session_has_user' => session()->has('user'),
            ]);

            return redirect()->route('dashboard');
        }

        $errorMessage = $response ? $response->json('message') : 'Gagal menghubungi server.';
        return back()->withErrors(['email' => $errorMessage]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'phone' => 'required|string|min:10',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $response = $this->apiService->register(
            $request->name,
            $request->email,
            $request->phone,
            $request->password,
            $request->password_confirmation
        );

        if ($response && $response->successful() && $response->json('success')) {
            $token = $response->json('access_token');
            $user = (object) $response->json('data');

            // Simpan token dan data user di session
            session([
                'api_token' => $token,
                'user' => $user,
            ]);

            // Pastikan session tersimpan
            session()->save();

            return redirect()->route('dashboard');
        }

        // Tangani error validasi dari API
        $errors = $response ? ($response->json('errors') ?? ['email' => $response->json('message') ?? 'Registrasi gagal.']) : ['email' => 'Gagal menghubungi server.'];
        return back()->withErrors($errors)->withInput();
    }

    public function logout(Request $request)
    {
        $token = session('api_token');

        if ($token) {
            // Panggil API logout di backend
            $this->apiService->logout($token);
        }

        // Hapus session di frontend
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
