<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\User;
use App\Models\Profile;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Automatically create profile when user registers
        User::created(function ($user) {
            Profile::create([
                'user_id' => $user->id
            ]);
        });
    }
}