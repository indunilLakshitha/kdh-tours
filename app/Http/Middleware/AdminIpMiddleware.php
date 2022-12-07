<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminIpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */

     //allowed ip list for admin access
    public $whitelistIps = [

        '127.0.0.1'

    ];


    public function handle(Request $request, Closure $next)
    {
        // if (!in_array($request->getClientIp(), $this->whitelistIps)) {
        //     abort(403, "You are restricted to access the site.");
        // }

        return $next($request);
    }
}
