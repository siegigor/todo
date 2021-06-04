<?php

declare(strict_types=1);

namespace App\Validation;

interface ValidatorInterface
{
    /**
     * @throws ValidationException
     */
    public function validate(object $object): void;
}
