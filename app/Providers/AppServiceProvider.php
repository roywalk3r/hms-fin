<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
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
        Model::unguard();


        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

        Gate::define('delete-backup', function (User $user) {
            return in_array($user->email, explode(',', env('BACKUP_MANAGERS')));
        });

        Gate::define('download-backup', function (User $user) {
            return in_array($user->email, explode(',', env('BACKUP_MANAGERS')));
        });
    }
}
