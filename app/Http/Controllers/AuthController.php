<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    // GET /login: show the login page
    public function showLogin()
    {
        return 'Login page';
    }

    // POST /login: handle the login form
    public function login(Request $request)
    {
        // Only to show the Request object working; no real checking yet
        $credentials = $request->only('email', 'password');

        return redirect()->route('dashboard')->with('success', 'Logged in (placeholder)');
    }

    // POST /logout: log the user out
    public function logout(Request $request)
    {
        return redirect()->route('login')->with('success', 'Logged out (placeholder)');
    }
}