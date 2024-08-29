<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CustomServerErrorException extends Exception
{
    protected $statusCode;
    public function __construct($message = "Please Check Your Database Connection", $statusCode = JsonResponse::HTTP_INTERNAL_SERVER_ERROR)
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

        return redirect()->back()->with('error', $this->getMessage());
    }
}
