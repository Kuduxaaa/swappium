<?php

namespace App\Http\Middleware;

use App\Models\kyc;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VerifyRequired
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
        $user = $request->user();

        if ($user->role >= 2) {
            return $next($request);
        }

        if (!$user) {
            return new Response(['success' => false, 'message' => 'Unauthorized!'], 401, [
                'Content-Type' => 'application/json'
            ]);
        }

        $uid = $user->id;
        $kyc = kyc::where('user_id', $user->id)->first();
        $verified = (!$kyc) ? false : $kyc->is_verified;

        if ($verified) {
            return $next($request);
            
        } else {
            return new Response(['success' => false, 'message' => 'Your account needs verification before continue'], 200, [
                'Content-Type' => 'application/json'
            ]);
        }
    }
}
