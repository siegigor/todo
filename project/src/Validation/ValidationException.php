<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationException extends \Exception
{
    public function __construct(
        private ConstraintViolationListInterface $violations,
        string $message = 'Invalid input.',
        int $code = 422,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
