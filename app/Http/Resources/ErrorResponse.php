<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;

class ErrorResponse extends JsonResponse
{
    public function __construct(string|array $messages, int $status = 400)
    {
        parent::__construct(['message' => $messages], $status);
    }

    public static function notFound(): self
    {
        return new static('Not Found', 404);
    }

    public static function unauthorized(): self
    {
        return new static('Unauthorized', 403);
    }

    public static function fromString(string $message, int $status = 400): self
    {
        return new static($message, $status);
    }
}
