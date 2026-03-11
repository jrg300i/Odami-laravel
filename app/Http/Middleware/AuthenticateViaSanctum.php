<?php

namespace App\Http\Middleware;

use Closure;
use Laravel\Sanctum\Sanctum;
use Illuminate\Auth\AuthenticationException;

/**
 * Middleware para autenticar peticiones API con tokens Sanctum.
 */
class AuthenticateViaSanctum
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response
     *
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function handle($request, Closure $next)
    {
        if (! $request->bearerToken()) {
            throw new AuthenticationException('Unauthenticated.');
        }

        // Buscar usuario con el token proporcionado
        $user = Sanctum::findUserFromToken($request->bearerToken());

        if (! $user) {
            throw new AuthenticationException('Unauthenticated.');
        }

        // Autenticar usuario en la petición
        $request->setUserResolver(function () use ($user) {
            return $user;
        });

        return $next($request);
    }
}
