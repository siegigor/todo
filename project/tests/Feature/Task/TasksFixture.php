<?php

declare(strict_types=1);

namespace App\Tests\Feature\Task;

use App\Model\Task\Entity\Task\AuthorId;
use App\Model\Task\Entity\Task\Id;
use App\Tests\Builder\Task\TaskBuilder;
use App\Tests\Feature\ApiUsersFixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TasksFixture extends Fixture
{
    public const TASK_1_ID = '4cc1eb98-2ed8-44b1-8104-ead10ed97eb6';
    public const TASK_2_ID = '18c8f084-ee11-4be3-8e23-d72c3e31094e';
    public const TASK_3_ID = 'f1fcbd26-51cb-48b6-b42e-e8ef2ea1c854';
    public const TASK_4_ID = '098101cc-ba49-43a2-8fad-5a42de694313';

    public function load(ObjectManager $manager): void
    {
        $task1 = (new TaskBuilder(new Id(self::TASK_1_ID), new AuthorId(ApiUsersFixture::USER_1_ID)))->build();
        $task2 = (new TaskBuilder(new Id(self::TASK_2_ID), new AuthorId(ApiUsersFixture::USER_2_ID)))->build();
        $task3 = (new TaskBuilder(new Id(self::TASK_3_ID), new AuthorId(ApiUsersFixture::USER_1_ID)))
            ->markAsDone()
            ->build();
        $task4 = (new TaskBuilder(new Id(self::TASK_4_ID), new AuthorId(ApiUsersFixture::USER_2_ID)))
            ->assignToParent($task2)
            ->build();
        $manager->persist($task1);
        $manager->persist($task2);
        $manager->persist($task3);
        $manager->persist($task4);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            ApiUsersFixture::class,
        ];
    }
}
