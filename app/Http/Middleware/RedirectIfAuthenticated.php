<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

// class RedirectIfAuthenticated
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle($request, Closure $next)
//     {
//         if (Auth::check() && Auth::user()->role_id == 1) {
            
//             return redirect()->route("/dashboard");
//         } elseif (Auth::check() && Auth::user()->role_id == 2) {
             
//             return redirect()->route("user");
//         }elseif (Auth::check() && Auth::user()->role_id == 3) {
           
//            return redirect()->route("superadmin.dashboard");
//        }
//          else {

//             return $next($request);
//         }
//     }
// }
class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return redirect(RouteServiceProvider::HOME);
        }
        return $next($request);
    }
}