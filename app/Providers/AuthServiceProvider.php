<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use App\Permission;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot()
    {
        $this->registerPolicies();

        $permissions = Permission::all();       
        foreach($permissions as $permission) {
            Gate::define($permission->name, function ($user) use ($permission) {
                return $user->hasRole($permission->roles);
            });
        }
    }
}
