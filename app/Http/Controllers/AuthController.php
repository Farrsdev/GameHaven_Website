<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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

        // Debug: log user data
        Log::info('User registered:', ['user_id' => $user->id, 'username' => $user->username]);

        // Simpan session
        Session::put('user_id', $user->id);
        Session::put('username', $user->username);
        Session::put('email', $user->email);
        Session::put('role', $user->role ?? 'user');

        // Debug: check session
        Log::info('Session after register:', [
            'user_id' => Session::get('user_id'),
            'username' => Session::get('username')
        ]);

        return redirect('/home')->with('success', 'Register berhasil!');
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
            Log::warning('Login failed for email:', ['email' => $data['email']]);
            return back()->withErrors(['email' => 'Invalid credentials']);
        }

        // Debug: log successful login
        Log::info('User logged in:', ['user_id' => $user->id, 'username' => $user->username]);

        // Simpan session
        Session::put('user_id', $user->id);
        Session::put('username', $user->username);
        Session::put('email', $user->email);
        Session::put('role', $user->role ?? 'user');

        // Debug: check session
        Log::info('Session after login:', [
            'user_id' => Session::get('user_id'),
            'username' => Session::get('username')
        ]);

        // Regenerate session ID untuk security
        Session::regenerate();
        if ($user->role == 1) {
            return redirect('/admin/dashboard')->with('success', 'Login berhasil!');
        } else {
            return redirect('/home')->with('success', 'Login berhasil!');
        }
    }

    // LOGOUT
    public function logout()
    {
        $userId = Session::get('user_id');
        Session::flush();
        Session::regenerate();

        Log::info('User logged out:', ['user_id' => $userId]);

        return redirect('/login');
    }
}
