<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // Verificar si el usuario está autenticado y tiene uno de los roles permitidos
        if (!in_array($request->user()->role->name, $roles)) {
            // Redirigir o devolver un error si no tiene el rol adecuado
            return redirect()->route('dashboard')->with('error', 'No tienes acceso a esta página.');
        }

        return $next($request);
    }
}
