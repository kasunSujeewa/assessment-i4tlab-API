<?php

namespace App\Exceptions;

use App\Constants\Constant;
use Exception;
use Illuminate\Http\JsonResponse;

class CustomNotFoundException extends Exception
{
    protected $statusCode;
    public function __construct($message = Constant::NOT_FOUND_MESSAGE, $statusCode = JsonResponse::HTTP_NOT_FOUND)
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
