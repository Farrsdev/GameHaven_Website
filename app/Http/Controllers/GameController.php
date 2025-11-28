<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        return response()->json($games);
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
}
