<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class BadRequestException extends Exception
{
    protected $message;
    protected $data;
    public function __construct(string $message, array $data = []) 
    {
        $this->message = $message;
        $this->data = $data;
    }

    public function render(): JsonResponse
    {
        return response()->json(['message' => $this->message, 'data' => $this->data], 400);
    }
}
