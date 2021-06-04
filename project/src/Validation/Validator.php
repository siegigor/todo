<?php

declare(strict_types=1);

namespace App\Validation;

use Symfony\Component\Validator\Validator\ValidatorInterface as SymfonyValidatorInterface;

class Validator implements ValidatorInterface
{
    public function __construct(private SymfonyValidatorInterface $validator)
    {
    }

    /**
     * @throws ValidationException
     */
    public function validate(object $object): void
    {
        $violations = $this->validator->validate($object);
        if (\count($violations)) {
            throw new ValidationException($violations);
        }
    }
}
