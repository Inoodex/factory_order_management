<?php

namespace App\Providers;

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

        // echo "Booting AppServiceProvider\n";
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // Super-admin bypass
            if (method_exists($user, 'hasRole') && ($user->hasRole('super-admin') || $user->hasRole('admin'))) {
                return true;
            }

            // Support multiple abilities/roles separated by pipe |
            $abilities = explode('|', $ability);

            foreach ($abilities as $singleAbility) {
                // Check if Tyro has this privilege
                if (method_exists($user, 'hasPrivilege') && $user->hasPrivilege($singleAbility)) {
                    return true;
                }

                // Check if Tyro has this role (some checks might use role name as ability)
                if (method_exists($user, 'hasRole') && $user->hasRole($singleAbility)) {
                    return true;
                }
            }

            return null; // Fallback to other gates/policies
        });

        // Override the vendor UserController with our local one
        $this->app->bind(
            \HasinHayder\TyroDashboard\Http\Controllers\UserController::class,
            \App\Http\Controllers\Admin\UserController::class
        );

        // Override the vendor RoleController with our local one
        $this->app->bind(
            \HasinHayder\TyroDashboard\Http\Controllers\RoleController::class,
            \App\Http\Controllers\Admin\RoleController::class
        );
    }
}
