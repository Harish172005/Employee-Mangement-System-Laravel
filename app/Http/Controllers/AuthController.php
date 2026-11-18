<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Services\AuthService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected AuthService $authService) {}

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string'],
        ]);
        $result = $this->authService->login(
            $credentials,
            $request->boolean('remember'),
            $request->ip()
        );

        if (! $result['success']) {
            return back()->withErrors(['username' => $result['error']])->onlyInput('username');
        }

        $request->session()->regenerate();
        $home = match ($result['user']->role) {
            Role::Admin => route('admin.dashboard'),
            Role::Employee => route('profile.show'),
        };

        return redirect()->intended($home)->with('success', 'Logged in successfully.');
    }

    public function showForgotForm()
    {

        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);
        $result = $this->authService->sendResetLink($request->email);

        if (! $result['status']) {
            return back()->withErrors(['email' => 'No user found with this email address.'])->onlyInput('email');
        }

        return $result['success']
         ? back()->with([
             'status' => __($result['status']),
             'message' => 'Password reset link sent successfully.',
         ])
         : back()->withErrors([
             'email' => __($result['status']),
         ])->with([
             'message' => 'Unable to send the password reset link.',
         ]);
    }

    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password', ['token' => $token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $result = $this->authService->resetPassword(
            $request->only('email', 'password', 'password_confirmation', 'token')
        );

        return $result['success']
                    ? redirect()->route('login')->with('success', __($result['status']))
                    : back()->withErrors(['email' => [__($result['status'])]]);
    }

    public function logout(Request $request)
    {
        $this->authService->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
