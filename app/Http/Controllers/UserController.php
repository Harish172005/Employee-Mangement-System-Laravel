<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(protected UserService $service)
    {
    }
    // $user is the username string for now. After the explicit binding is enabled
    // in AppServiceProvider (Module 5), change it to: User $user
    public function index()
    {
        Gate::authorize('viewAny', User::class);
        $users = User::with('employee')
                ->whereKeyNot(auth()->user()->id)          // leaves out the logged-in admin
                ->orderBy('name')
                ->paginate(10)
                ->withQueryString(); // Exclude the currently logged-in user

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(UserRequest $request)
    {
        Gate::authorize('create', User::class);
        $validated = $request->validated();

        $this->service->createUser($validated);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created successfully.');
        } 

    public function show($user)
    {
        Gate::authorize('viewAny', User::class);
        return redirect()->route('admin.users.edit', $user);
    }

    public function destroy($user)
    {
        Gate::authorize('delete', User::class);
        $this->service->deleteUser($user);
        return redirect()->route('admin.users.index')
            ->with('success', 'User deactivated successfully.');
    }

    public function restore($user)
    {
        Gate::authorize('restore', User::class);
        $this->service->restoreUser($user);
        return redirect()->route('admin.users.index')
            ->with('success', 'User restored successfully.');
    }

    public function forceDelete($user)
    {
        Gate::authorize('forceDelete', User::class);
        $this->service->forceDeleteUser($user);
        return redirect()->route('admin.users.index')
            ->with('success', 'User permanently deleted successfully.');
    }
}
