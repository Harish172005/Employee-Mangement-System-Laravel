<?php

namespace App\Repositories;

use App\Contracts\UserRepository;
use App\Enums\Status;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements UserRepository
{
    public function deleteUser($user)
    {
        return DB::transaction(function () use ($user) {
            $user->status = Status::Inactive;
            $user->saveQuietly();
            $user->delete();
        });
    }

    public function restoreUser($user)
    {
        DB::transaction(function () use ($user) {
            $user->status = Status::Active;
            $user->saveQuietly();
            $user->restore();
        });
    }

    public function forceDeleteUser($user): void
    {
        DB::transaction(function () use ($user) {
            $user->forceDelete();
        });
    }
}
