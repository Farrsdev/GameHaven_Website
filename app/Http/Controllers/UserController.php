<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Semua user (admin)
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    // Detail user
    public function show($id)
    {
        $user = User::findOrFail($id);
        return response()->json($user);
    }

    // Buat user baru (register)
    public function store(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'nullable|boolean',
            'photo' => 'nullable|string'
        ]);

        $data['password'] = Hash::make($data['password']); // enkripsi password

        $user = User::create($data);

        return response()->json($user, 201);
    }

    // Update user
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'username' => 'sometimes|string',
            'email' => 'sometimes|email|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:6',
            'role' => 'sometimes|boolean',
            'photo' => 'nullable|string'
        ]);

        if(isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return response()->json($user);
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }
}
