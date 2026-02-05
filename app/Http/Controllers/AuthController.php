<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::whereRaw('LOWER(username) = ?', [strtolower($request->username)])->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['username' => __('Invalid credentials.')]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        UserActivity::create([
            'user_id'  => $user->id,
            'activity' => $user->isAdmin() ? 'Admin logged in' : 'User logged in',
        ]);

        return redirect()->intended(
            $user->isAdmin() ? route('admin.dashboard') : route('user.dashboard')
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
