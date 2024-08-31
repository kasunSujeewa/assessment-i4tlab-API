<?php

namespace App\Http\Middleware;

use App\Constants\Constant;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomSanctumMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sanctum')->check()) {
            $user = Auth::guard('sanctum')->user();
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'data'    => [],
            'message' => Constant::UNAUTHORIZE,
        ], JsonResponse::HTTP_UNAUTHORIZED);
    }
}
