<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\UserRepository;
use Illuminate\Support\Facades\DB;
use App\Enums\Status;

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

    public function forceDeleteUser($user) : void
    {
        DB::transaction(function () use ($user) {
            $user->forceDelete();
        });
    }
}