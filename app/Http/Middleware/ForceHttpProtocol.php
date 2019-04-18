<?php

namespace App\Http\Middleware;

use Closure;

class ForceHttpProtocol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!$request->secure() && env('APP_ENV') === 'production') { // 本番環境のみ常時SSL化する
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
