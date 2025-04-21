<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission)
    {
        // Check if user is authenticated
        if (!Auth::check()) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'error' => 'unauthenticated'
            ], 401);
        }

        $user = Auth::user();

        // Admins have all permissions
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user has the required permission
        if (!$user->hasPermission($permission)) {
            return response()->json([
                'message' => 'Access Denied: You do not have the required permission',
                'error' => 'insufficient_permissions',
                'required_permission' => $permission
            ], 403);
        }

        return $next($request);
    }
}