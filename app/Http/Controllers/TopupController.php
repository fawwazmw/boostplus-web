<?php

// TopupController.php - Dengan debugging untuk mengetahui masalah
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BackendApiService;

class TopupController extends Controller
{
    protected $apiService;

    public function __construct(BackendApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $packages = [];
        $response = $this->apiService->getPackages();

        // Debug: Log response details
        if ($response) {
            Log::info('TopUp Packages API Response:', [
                'status' => $response->status(),
                'successful' => $response->successful(),
                'body' => $response->body(),
                'json' => $response->json(),
                'success_flag' => $response->json('success'),
                'data' => $response->json('data')
            ]);
        } else {
            Log::error('TopUp Packages API Response is null - Connection failed');
        }

        // Coba berbagai cara untuk mendapatkan data packages
        if ($response && $response->successful()) {
            $responseData = $response->json();

            // Cek berbagai struktur response yang mungkin
            if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                $packages = $responseData['data'];
                Log::info('Packages loaded from success->data structure:', ['count' => count($packages)]);
            } elseif (isset($responseData['data'])) {
                $packages = $responseData['data'];
                Log::info('Packages loaded from data structure:', ['count' => count($packages)]);
            } elseif (is_array($responseData)) {
                $packages = $responseData;
                Log::info('Packages loaded directly from response array:', ['count' => count($packages)]);
            } else {
                Log::error('Unexpected response structure:', ['response' => $responseData]);
            }
        } else {
            // Log specific error
            if (!$response) {
                Log::error('API connection failed for packages');
            } elseif (!$response->successful()) {
                Log::error('API returned error status:', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
            }
        }

        // Tambahkan debugging untuk view
        Log::info('Sending packages to view:', [
            'packages_count' => count($packages),
            'packages_data' => $packages
        ]);

        return view('topup.index', compact('packages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id' => 'required|integer',
            'game_player_id' => 'required|string',
        ]);

        // 1. Cek akun game
        $checkResponse = $this->apiService->checkPlayer($request->game_player_id);

        if (!$checkResponse || !$checkResponse->successful() || !$checkResponse->json('success')) {
            return back()->with('error', 'Player ID tidak ditemukan atau tidak valid.');
        }

        // Ambil nama game dari hasil cek
        $gameAccount = (object) $checkResponse->json('data');

        // 2. Proses transaksi
        $token = session('api_token');
        $transactionResponse = $this->apiService->submitTopUp(
            $token,
            $request->game_player_id,
            $request->package_id,
            $gameAccount->game_name
        );

        if ($transactionResponse && $transactionResponse->successful() && $transactionResponse->json('success')) {
            return redirect()->route('dashboard')
                ->with('success', 'Top up berhasil! Saldo Anda telah diperbarui.');
        }

        $errorMessage = $transactionResponse ? $transactionResponse->json('message') : 'Terjadi kesalahan saat top up.';
        return back()->with('error', $errorMessage);
    }
}
