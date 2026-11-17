<?php

namespace App\Http\Controllers;

use App\Enums\Role;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password as PasswordBroker;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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
        $key = Str::lower($request->username).'|'.$request->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $seconds = RateLimiter::availableIn($key);

            return back()->withErrors(['username' => "Too many login attempts. Please try again in $seconds seconds."])->onlyInput('username');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            RateLimiter::hit($key, 60);

            return back()->withErrors(['username' => 'These credentials do not match our records.'])->onlyInput('username');
        }

        if (Auth::user()->status !== Status::Active) {
            Auth::logout();

            return back()->withErrors(['username' => 'This account is inactive.'])->onlyInput('username');
        }
        RateLimiter::clear($key);

        $request->session()->regenerate();
        $home = match ($request->user()->role) {
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
        $user = User::where('email', $request->email)->first();
        if (! $user) {
            return back()->withErrors(['email' => 'No user found with this email address.'])->onlyInput('email');
        }

        $status = PasswordBroker::sendResetLink(
            $request->only('email')
        );

        return $status === PasswordBroker::RESET_LINK_SENT
         ? back()->with([
             'status' => __($status),
             'message' => 'Password reset link sent successfully.',
         ])
         : back()->withErrors([
             'email' => __($status),
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

        $status = PasswordBroker::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => bcrypt($password),
                ])->save();
            }
        );

        return $status === PasswordBroker::PASSWORD_RESET
                    ? redirect()->route('login')->with('success', __($status))
                    : back()->withErrors(['email' => [__($status)]]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}
