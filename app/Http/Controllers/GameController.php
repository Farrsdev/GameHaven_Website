<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\PurchasedGame;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GameController extends Controller
{
    public function index(Request $request)
    {
        $query = Game::query();

        // Search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('developer', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category filter
        if ($request->has('category') && $request->category !== 'all') {
            $query->where('category', $request->category);
        }

        // Price filters
        if ($request->has('price')) {
            switch ($request->price) {
                case 'free':
                    $query->where('price', 0);
                    break;
                case 'under100':
                    $query->where('price', '<', 100000);
                    break;
                case 'under500':
                    $query->where('price', '<', 500000);
                    break;
            }
        }

        // Rating filter
        if ($request->has('rating') && $request->rating !== 'all') {
            $minRating = (float) $request->rating;
            $query->where('rating', '>=', $minRating);
        }

        // Sort options
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('rating', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        // Pagination
        $perPage = $request->get('per_page', 12);
        $games = $query->paginate($perPage);

        return response()->json([
            'data' => $games->items(),
            'current_page' => $games->currentPage(),
            'last_page' => $games->lastPage(),
            'per_page' => $games->perPage(),
            'total' => $games->total(),
        ]);
    }

    public function show($id)
    {
        $game = Game::findOrFail($id);
        return response()->json($game);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'developer' => 'required|string',
            'category' => 'required|string',
            'price' => 'required|numeric',
            'release_date' => 'nullable|date',
            'stock' => 'required|integer',
            'rating' => 'nullable|numeric',
            'file_url' => 'required|string',
            'image_url' => 'required|string'
        ]);

        $game = Game::create($data);

        return response()->json($game, 201);
    }

    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $data = $request->validate([
            'title' => 'sometimes|string',
            'description' => 'sometimes|string',
            'developer' => 'sometimes|string',
            'category' => 'sometimes|string',
            'price' => 'sometimes|numeric',
            'release_date' => 'nullable|date',
            'stock' => 'sometimes|integer',
            'rating' => 'nullable|numeric',
            'file_url' => 'sometimes|string',
            'image_url' => 'sometimes|string'
        ]);

        $game->update($data);

        return response()->json($game);
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return response()->json(['message' => 'Game deleted']);
    }

    // Get available categories
    public function categories()
    {
        $categories = Game::distinct()->pluck('category');
        return response()->json($categories);
    }

    public function showDetail($id)
    {
        $game = Game::findOrFail($id);

        // Cek apakah user sudah membeli game ini
        $userId = Session::get('user_id');
        $isPurchased = false;

        if ($userId) {
            $isPurchased = PurchasedGame::where('user_id', $userId)
                ->where('game_id', $id)
                ->exists();
        }

        // Placeholder image base64
        $placeholderImage = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAwIiBoZWlnaHQ9IjUwMCIgdmlld0JveD0iMCAwIDQwMCA1MDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSI0MDAiIGhlaWdodD0iNTAwIiBmaWxsPSJ1cmwoI2dyYWRpZW50KSIvPgo8dGV4dCB4PSIyMDAiIHk9IjI1MCIgdGV4dC1hbmNob3I9Im1pZGRsZSIgZmlsbD0id2hpdGUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNiI+R2FtZSBJbWFnZTwvdGV4dD4KPGRlZnM+CjxsaW5lYXJHcmFkaWVudCBpZD0iZ3JhZGllbnQiIHgxPSIwIiB5MT0iMCIgeDI9IjQwMCIgeTI9IjUwMCIgZ3JhZGllbnRVbml0cz0idXNlclNwYWNlT25Vc2UiPgo8c3RvcCBvZmZzZXQ9IjAiIHN0b3AtY29sb3I9IiMxZjI5M2IiLz4KPHN0b3Agb2Zmc2V0PSIxIiBzdG9wLWNvbG9yPSIjMGYxNzJhIi8+CjwvbGluZWFyR3JhZGllbnQ+CjwvZGVmcz4KPC9zdmc+';

        return view('user.game-detail', [
            'game' => $game,
            'isPurchased' => $isPurchased,
            'placeholderImage' => $placeholderImage
        ]);
    }

    // Method untuk handle purchase game
    public function purchase(Request $request, $id)
    {
        $userId = Session::get('user_id');

        if (!$userId) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $game = Game::findOrFail($id);

        // Cek apakah game sudah dibeli
        $alreadyPurchased = PurchasedGame::where('user_id', $userId)
            ->where('game_id', $id)
            ->exists();

        if ($alreadyPurchased) {
            return response()->json(['error' => 'Game already purchased'], 400);
        }

        // Buat transaksi
        $transaction = Transactions::create([
            'user_id' => $userId,
            'total_price' => $game->price,
            'date' => now(),
        ]);

        // Buat detail transaksi
        TransactionDetail::create([
            'transaction_id' => $transaction->id,
            'game_id' => $game->id,
            'price' => $game->price,
            'qty' => 1
        ]);

        // Tambahkan ke purchased games
        PurchasedGame::create([
            'user_id' => $userId,
            'game_id' => $game->id,
            'purchase_date' => now(),
            'download_status' => 'NOT_DOWNLOADED'
        ]);

        // Update stock game
        $game->decrement('stock');

        return response()->json([
            'success' => true,
            'message' => 'Game purchased successfully!',
            'transaction_id' => $transaction->id
        ]);
    }
}
