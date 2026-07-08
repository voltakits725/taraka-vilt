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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! $request->user() || ! in_array($request->user()->role, $roles)) {
            // Jika role tidak sesuai, lemparkan ke dashboard kalau owner, atau ke orders kalau admin/barista, atau ke home kalau customer
            $userRole = $request->user()?->role;
            if (in_array($userRole, ['owner', 'admin', 'barista'])) {
                if (in_array($userRole, ['barista', 'admin'])) {
                    return redirect()->route('admin.orders');
                }
                return redirect()->route('admin.dashboard');
            }
            return redirect('/');
        }

        return $next($request);
    }
}
