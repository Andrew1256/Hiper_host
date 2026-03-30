<?php

namespace App\Providers;

// @deprecated — Bindings have been moved to AppServiceProvider::register().
// This file is kept as an empty stub to avoid breaking any cached config.
// Safe to delete after running: php artisan config:clear

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // intentionally empty — bindings moved to AppServiceProvider
    }
}
