<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->segment(1) ?? 'en';
        if (!in_array($locale, ['en', 'vn'])) {
            $locale = 'en';
        }
        app()->setLocale($locale);
        URL::defaults(['locale' => $locale]);
        return $next($request);
    }
}
