<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRole = $request->user()->role()->first();
        if ($userRole) {

            // Set scope as admin/moderator based on user role
            $request->request->add([
                'scope' => $userRole->role
            ]);
        }

        return $next($request);
    }
}
