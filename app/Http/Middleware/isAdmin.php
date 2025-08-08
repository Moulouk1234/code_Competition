<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            if (auth()->user()->is_admin == 1) {
                return $next($request); // Laisser passer la requête pour les administrateurs
            } else {
                return redirect()->route('user.homeuser'); // Rediriger les utilisateurs non-administrateurs vers leur page d'accueil
            }
        }


    }
}

