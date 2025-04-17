<?php

namespace App\Http\Middleware;

use Closure;

class VerifyApiKey
{
/**
 * Handle an incoming request.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
 * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
 */

    // public function handle($request, Closure $next){
    //     $apiKey = $request->bearerToken("Authorization");
    //     dd($apiKey);
    //     if (!$apiKey || $apiKey !== env('API_KEY')) {
    //         return response()->json(['error' => 'Unauthorized'], 401);
    //     }

    //     return $next($request);
    // }

    public function handle($request, Closure $next) {
    
        $apiKey = $request->header('X-API-Key');

        if ($apiKey !==  env('API_KEY') ) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}




