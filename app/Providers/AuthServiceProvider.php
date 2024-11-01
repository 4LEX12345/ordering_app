<?php

namespace App\Providers;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // Define your model policies here
    ];


    public function boot()
    {
        // $this->registerPolicies();
        Gate::define('view-dashboard', function ($user) {
            return $user->roles[0]->name === 'admin' ||$user->roles[0]->name === 'staff'; 
        });
        Gate::define('view-masterdata', function ($user) {
            return $user->roles[0]->name === 'admin'; 
        });
        Gate::define('view-products', function ($user) {
            return $user->roles[0]->name === 'admin' || $user->roles[0]->name === 'staff'; 
        });
        Gate::define('view-order', function ($user) {
            return $user->roles[0]->name === 'admin' || $user->roles[0]->name === 'staff'; 
        });
        Gate::define('view-system', function ($user) {
            return $user->roles[0]->name === 'admin'; 
        });
    }
}
