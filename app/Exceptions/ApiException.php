<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

/**
 * Base class for project exceptions
 */
class ApiException extends Exception
{
    protected const string ERROR_CAPTION = 'errors';

    protected int $statusCode;

    /**
     * Additional arguments for exception for custom messages
     *
     * @var array
     */
    protected array $args {
        set {
            $this->args = $value;
        }
    }

    /**
     * @param  string  $message  Exception message
     * @param  int  $statusCode  HTTP status code
     * @param  array  $args  additional arguments for exception for custom messages
     */
    public function __construct(
        string $message,
        int $statusCode = ResponseAlias::HTTP_INTERNAL_SERVER_ERROR,
        array $args = []
    ) {
        parent::__construct(message: $message);
        $this->statusCode = $statusCode;
        $this->args = $args;
    }

    public function report(): void
    {
        report($this);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        $message = $this->exceptionMessage();

        return new JsonResponse(
            data: [self::ERROR_CAPTION => $message],
            status: $this->statusCode,
            json: false
        );
    }

    protected function exceptionMessage(): string|array
    {
        return $this->getMessage();
    }
}
