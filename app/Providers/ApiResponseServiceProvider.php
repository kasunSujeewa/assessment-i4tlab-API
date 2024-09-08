<?php

namespace App\Providers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\ServiceProvider;

class ApiResponseServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Response::macro('apiSuccess',function ($result, string $message = 'Operation successful',$code = JsonResponse::HTTP_OK) : JsonResponse {
            return Response::json([
                'success' => true,
                'data'    => $result,
                'message' => $message,
            ],$code);
        });

        Response::macro('apiError',function (string $error, array $errorMessages = [], int $code = JsonResponse::HTTP_NOT_FOUND) : JsonResponse {
            return Response::json([
                'success' => false,
                'message' => $error,
                'data'    => $errorMessages
            ],$code);
        });
    }
}
