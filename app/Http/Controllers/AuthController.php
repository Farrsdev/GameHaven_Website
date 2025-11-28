<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);

        // Hapus auto-login, langsung redirect ke login
        return redirect('/login')->with('success', 'Register berhasil, silakan login.');
    }


    // LOGIN
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Simpan session
        Session::put('user_id', $user->id);
        Session::put('username', $user->username);
        Session::put('role', $user->role);

        return redirect('/dashboard');
    }

    // LOGOUT
    public function logout()
    {
        Session::flush(); // Hapus semua session
        return redirect('/login');
    }
}
