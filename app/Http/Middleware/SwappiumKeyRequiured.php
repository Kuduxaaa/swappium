<?php

namespace App\Http\Middleware;

use App\Models\ApiKeys;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SwappiumKeyRequiured
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!$request->hasHeader('X-Swappium-Key'))
        {
            return new Response(['success' => false, 'message' => 'Invalid request'], 400, [
                'Content-Type' => 'application/json'
            ]);
        }

        $key = $request->header('X-Swappium-Key');
        $api = ApiKeys::where('key', $key)->first();

        if (!$api) 
        {
            return new Response(['success' => false, 'message' => 'Unauthorized!'], 401, [
                'Content-Type' => 'application/json'
            ]);
        }

        return $next($request);
    }
}
