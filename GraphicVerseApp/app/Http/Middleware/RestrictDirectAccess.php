<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RestrictDirectAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        if ($request->isMethod('GET') && $request->path() === 'three-dim') {
            return redirect()->route('your-error-route')->with('error', 'Invalid request');
        }

        return $next($request);
    }
}
