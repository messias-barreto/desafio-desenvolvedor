<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequestException extends Exception
{
    protected $message;
    public function __construct(string $message) 
    {
        $this->message = $message;
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message], 400);
    }
}
