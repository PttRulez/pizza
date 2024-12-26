<?php

namespace App\Providers;

use App\Exceptions\NotAdminException;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }
    
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('admin', function (User $user) {
            if (! $user->isAdmin()) {
                throw new NotAdminException();
            }
            return true;
        });
        
//         ParallelTesting::setUpTestDatabase(function (string $database, int $token) {
//            Artisan::call('db:seed');
//        });
        
    }
}
