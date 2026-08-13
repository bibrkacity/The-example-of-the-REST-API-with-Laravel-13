<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class ApiValidationException extends ApiException
{
    protected array $errors;

    public function __construct($message, array $errors = [])
    {
        parent::__construct(
            $message,
            ResponseAlias::HTTP_UNPROCESSABLE_ENTITY
        );
        $this->errors = $errors;
    }

    #[\Override]
    public function exceptionMessage(): string|array
    {
        if (count($this->errors) > 0) {
            $message = $this->errors;
        } else {
            $message = $this->message;
        }

        return $message;
    }
}
