<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
        // Para peticiones API, retornar null para que se devuelva 401 JSON
        if ($request->expectsJson() || $request->is('api/*')) {
            return null;
        }

        return null;
    }
}
