<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomSanctumAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();

            if ($user->role == 'Admin') {
                return $next($request);
            } else {
                return response()->json([
                    'success' => false,
                    'data'    => [],
                    'message' => 'Forbidden. You do not have the required permissions to access this resource.',
                ], JsonResponse::HTTP_FORBIDDEN);
            }
        }

        return response()->json([
            'success' => false,
            'data'    => [],
            'message' => 'Unauthorized',
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
