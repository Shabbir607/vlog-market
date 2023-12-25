<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
    // {
    //     // dd($request);
    //     if($request->user()->role=='admin'){
    //         return $next($request);
    //     }
    //     else{
    //         request()->session()->flash('error','You do not have any permission to access this page');
    //         return redirect()->route($request->user()->role);
    //     }
    // }

      /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth::check() && Auth::user()->role_id == 1) {
            // dd($request);
            return $next($request);
        } else {
            return redirect()->route("login");
        }
    }
}
