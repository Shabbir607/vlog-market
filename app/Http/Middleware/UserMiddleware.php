<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
 
class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
    
     *  (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth::check() && Auth::user()->role_id == 2) {
            return $next($request);
        } else {
            return redirect()->route("login");
        }
    }

}
