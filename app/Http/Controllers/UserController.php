<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    // $user is the username string for now. After the explicit binding is enabled
    // in AppServiceProvider (Module 5), change it to: User $user
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = User::all();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('create', User::class);
        try{
        $validated = $request->validated();

        $user = User::create($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
        } 
        catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error',$e->getMessage());
        }
    }

    public function show($user)
    {
        Gate::authorize('viewAny', User::class);
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

    public function destroy($user)
    {
        Gate::authorize('delete', User::class);
        $user->status = 'inactive';
        $user->delete();
        $user->saveQuietly();
        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated (placeholder)');
    }
}
