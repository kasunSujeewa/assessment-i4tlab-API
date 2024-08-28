<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomAuthException extends Exception
{
    protected $statusCode;
    public function __construct($message = "Invalid credentials", $statusCode = JsonResponse::HTTP_UNAUTHORIZED)
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
    }

    /**
     * Convert the exception into an HTTP response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $this->getMessage(),
            ], $this->statusCode);
        }

        return redirect()->guest(route('login'))->with('error', $this->getMessage());
    }
}
