<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Gate para verificar permisos
        Gate::define('has-permission', function ($user, $permission) {
            return $user->hasPermission($permission);
        });

        // Gate para administradores
        Gate::define('is-admin', function ($user) {
            return $user->isAdmin();
        });

        // Gate para tapiceros
        Gate::define('is-tapicero', function ($user) {
            return $user->isTapicero();
        });

        // Gate para clientes
        Gate::define('is-cliente', function ($user) {
            return $user->isCliente();
        });

        // Gates específicos por módulo
        Gate::define('manage-users', function ($user) {
            return $user->hasPermission('users.manage');
        });

        Gate::define('manage-clientes', function ($user) {
            return $user->hasPermission('clientes.manage');
        });

        Gate::define('manage-trabajos', function ($user) {
            return $user->hasPermission('trabajos.manage');
        });

        Gate::define('manage-facturas', function ($user) {
            return $user->hasPermission('facturas.manage');
        });

        Gate::define('manage-fotos', function ($user) {
            return $user->hasPermission('fotos.manage');
        });

        Gate::define('manage-backups', function ($user) {
            return $user->hasPermission('backups.manage');
        });

        // Gate para recursos propios
        Gate::define('view-own', function ($user, $model) {
            if ($user->isAdmin() || $user->isTapicero()) {
                return true;
            }
            
            if (method_exists($model, 'user_id') && $model->user_id == $user->id) {
                return true;
            }
            
            if (method_exists($model, 'cliente_id') && $model->cliente_id == $user->id) {
                return true;
            }
            
            return false;
        });
    }
}