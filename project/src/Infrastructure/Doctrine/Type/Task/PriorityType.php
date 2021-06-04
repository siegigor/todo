<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type\Task;

use App\Model\Task\Entity\Task\Priority;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class PriorityType extends IntegerType
{
    public const NAME = 'task_task_priority';

    public function convertToDatabaseValue($value, AbstractPlatform $platform): int
    {
        return $value instanceof Priority ? $value->getValue() : (int)$value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?Priority
    {
        return !empty($value) ? new Priority((int)$value) : null;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
