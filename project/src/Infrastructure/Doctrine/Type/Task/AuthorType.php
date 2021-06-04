<?php

declare(strict_types=1);

namespace App\Infrastructure\Doctrine\Type\Task;

use App\Model\Task\Entity\Task\AuthorId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;

class AuthorType extends GuidType
{
    public const NAME = 'task_task_author_id';

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return $value instanceof AuthorId ? $value->getValue() : $value;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return !empty($value) ? new AuthorId((string)$value) : null;
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
