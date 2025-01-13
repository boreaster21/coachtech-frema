<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if (!$user) {
            Log::error('Unauthorized access attempt', ['url' => $request->url()]);
            return redirect()->route('login');
        }

        if (optional($user->role)->name !== 'admin') {
            Log::warning('Access denied for non-admin user', [
                'user_id' => $user->id,
                'url' => $request->url(),
            ]);
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return $next($request); 
    }
}
