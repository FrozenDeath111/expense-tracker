<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = '';

        if (session()->has('username') && session()->get('username') == $username) {
            return $next($request);
        }

        $user = User::where('username', $request->username)->first();

        if ($user && $request->password == $user->password) {
            return $next($request);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Aunthentication Failed',
                'code' => 401,
            ]);
        }
    }
}
