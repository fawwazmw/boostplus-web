<?php

// HomeController.php - Gunakan BackendApiService
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BackendApiService;

class HomeController extends Controller
{
    protected $apiService;

    public function __construct(BackendApiService $apiService)
    {
        $this->apiService = $apiService;
    }

    public function index()
    {
        $topPlayers = [];

        // Gunakan service untuk mendapatkan top players
        $response = $this->apiService->getTopPlayers();

        if ($response && $response->successful() && $response->json('success')) {
            $topPlayers = $response->json('data');
        }

        return view('home', [
            'topPlayers' => $topPlayers
        ]);
    }

    public function search(Request $request)
    {
        $request->validate([
            'game_player_id' => 'required|string|max:50',
        ]);

        // Gunakan service untuk cek akun game
        $response = $this->apiService->checkPlayer($request->game_player_id);

        if ($response && $response->successful() && $response->json('success')) {
            return redirect()->route('home')
                ->with('account', (object) $response->json('data'));
        }

        $errorMessage = $response ? $response->json('message') : 'Akun tidak dapat ditemukan.';
        return redirect()->route('home')
            ->with('error', $errorMessage);
    }

    public function leaderboard()
    {
        // Implementasi untuk leaderboard jika diperlukan
        return view('leaderboard');
    }
}
