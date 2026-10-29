<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // $user is the username string for now. After the explicit binding is enabled
    // in AppServiceProvider (Module 5), change it to: User $user
    public function index()
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.users.index')
            ->with('success', 'User created (placeholder)');
    }

    public function show($user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function edit($user)
    {
        $user = [
            'name' => 'John Doe', 'email' => 'john@example.com',
            'username' => $user, 'role' => 'employee', 'status' => 'active',
        ];

        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        return redirect()->route('admin.users.index')
            ->with('success', 'User updated (placeholder)');
    }

    public function destroy($user)
    {
        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated (placeholder)');
    }
}
