<?php

namespace App\Providers;

use App\Models\Permission;
use Illuminate\Auth\Access\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        foreach (Permission::all() as $permission){
            \Illuminate\Support\Facades\Gate::define($permission->name, function ($user) use ($permission){
                return $user->hasPermission($permission);
            });
        }
    }
}
