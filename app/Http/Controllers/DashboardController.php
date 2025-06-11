<?php
// DashboardController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Services\BackendApiService;

class DashboardController extends Controller
{
    protected $apiService;

    public function __construct(BackendApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        // Log session data untuk debugging
        Log::info('Dashboard accessed. Session data:', [
            'has_token' => session()->has('api_token'),
            'has_user' => session()->has('user'),
            'user_data' => session('user'),
        ]);

        $user = session('user');
        $token = session('api_token');

        // Get transactions
        $transactions = [];
        if ($token) {
            $response = $this->apiService->getTransactions($token);

            // Debug log untuk response
            if ($response) {
                Log::info('Transactions API Response:', [
                    'status' => $response->status(),
                    'successful' => $response->successful(),
                    'body' => $response->body(),
                    'json' => $response->json()
                ]);

                if ($response->successful()) {
                    $responseData = $response->json();

                    // Cek berbagai struktur response yang mungkin
                    if (isset($responseData['success']) && $responseData['success'] && isset($responseData['data'])) {
                        $transactions = $responseData['data'];
                    } elseif (isset($responseData['data'])) {
                        $transactions = $responseData['data'];
                    } elseif (is_array($responseData)) {
                        $transactions = $responseData;
                    }

                    // Log struktur data transaksi untuk debugging
                    if (!empty($transactions)) {
                        Log::info('Transaction structure (first item):', [
                            'first_transaction' => $transactions[0] ?? null,
                            'available_keys' => array_keys($transactions[0] ?? [])
                        ]);
                    }
                }
            } else {
                Log::error('Transactions API Response is null - Connection failed');
            }
        }

        return view('dashboard', compact('user', 'transactions'));
    }
}
