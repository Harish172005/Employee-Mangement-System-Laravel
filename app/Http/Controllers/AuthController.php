<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    // Placeholder. Real authentication (Auth::attempt) comes in Module 8.
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');   // Request object demo

        return redirect()->route('dashboard')->with('success', 'Logged in (placeholder)');
    }

    public function logout(Request $request)
    {
        return redirect()->route('login')->with('success', 'Logged out (placeholder)');
    }
}
