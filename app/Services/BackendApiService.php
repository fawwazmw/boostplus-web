<?php

namespace App\Services;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class BackendApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        // CARA YANG BENAR: Langsung ambil dari env().
        // Pastikan variabel ini ada di file .env Anda.
        $this->baseUrl = env('API_BASE_URL');

        // Pastikan base URL tidak memiliki garis miring di akhir.
        if (!$this->baseUrl) {
            throw new \Exception('API_BASE_URL tidak ditemukan di file .env');
        }
    }

    /**
     * Metode pusat untuk mengirim request ke API backend.
     *
     * @param string $method Metode HTTP (get, post, put, delete).
     * @param string $uri Endpoint yang dituju (misal: '/login').
     * @param array $data Data yang akan dikirim (untuk post/put).
     * @param string|null $token Token otentikasi jika diperlukan.
     * @return Response|null Mengembalikan objek Response atau null jika koneksi gagal.
     */
    protected function request(string $method, string $uri, array $data = [], ?string $token = null): ?Response
    {
        try {
            // Bangun request dengan header dan token yang sesuai.
            $request = Http::acceptJson()->when($token, function ($http, $token) {
                $http->withToken($token);
            });

            // Kirim request ke API.
            $response = $request->{$method}($this->baseUrl . $uri, $data);

            // Log untuk debugging jika mode debug aktif.
            if (config('app.debug')) {
                Log::info("API Request: {$method} {$this->baseUrl}{$uri}", [
                    'request_data' => $data,
                    'response_status' => $response->status(),
                    'response_body' => $response->json()
                ]);
            }

            return $response;

        } catch (ConnectionException $e) {
            // Tangani jika server API tidak bisa dihubungi.
            Log::error("API Connection Error: {$method} {$this->baseUrl}{$uri}", [
                'error' => $e->getMessage(),
            ]);

            // Kembalikan null agar controller bisa menanganinya dengan baik.
            return null;
        }
    }

    // --- Auth Endpoints ---
    public function login(string $email, string $password): ?Response
    {
        return $this->request('post', '/login', [
            'email' => $email,
            'password' => $password
        ]);
    }

    public function register(string $name, string $email, string $phone, string $password, string $passwordConfirmation): ?Response
    {
        return $this->request('post', '/register', [
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ]);
    }

    public function logout(string $token): ?Response
    {
        return $this->request('post', '/logout', [], $token);
    }

    // --- Public Endpoints ---
    public function getPackages(): ?Response
    {
        return $this->request('get', '/packages');
    }

    public function getTopPlayers(): ?Response
    {
        return $this->request('get', '/top-players');
    }

    public function checkPlayer(string $playerId): ?Response
    {
        return $this->request('post', '/check-game-account', [
            'game_id' => $playerId
        ]);
    }

    // --- Authenticated Endpoints ---
    public function submitTopUp(string $token, string $playerId, int $packageId, string $gameName): ?Response
    {
        return $this->request('post', '/transactions', [
            'game_player_id' => $playerId,
            'package_id' => $packageId,
            'game_name' => $gameName
        ], $token);
    }

    public function getUser(string $token): ?Response
    {
        return $this->request('get', '/user', [], $token);
    }

    public function getTransactions(string $token): ?Response
    {
        return $this->request('get', '/transactions', [], $token);
    }
}
