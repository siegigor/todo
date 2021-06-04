<?php

declare(strict_types=1);

namespace App\Tests\Feature\Task;

use App\Tests\Feature\ApiUsersFixture;
use App\Tests\Feature\TransactionalWebTestCase;
use Ramsey\Uuid\Uuid;

class FinishTest extends TransactionalWebTestCase
{
    private const URI = '/api/task/%s/finish';

    public function testSuccess(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request(
            'POST',
            sprintf(self::URI, TasksFixture::TASK_1_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);
        self::assertEquals([], $data);
    }

    public function testNotFound(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request(
            'POST',
            sprintf(self::URI, Uuid::uuid4()->toString()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );
        self::assertEquals(404, $this->client->getResponse()->getStatusCode());
    }

    public function testUnauthorized(): void
    {
        $this->client->request(
            'POST',
            sprintf(self::URI, Uuid::uuid4()->toString()),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testNotAuthor(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request(
            'POST',
            sprintf(self::URI, TasksFixture::TASK_2_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testWithNotFinishedChildren(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user2Credentials());
        $this->client->request(
            'POST',
            sprintf(self::URI, TasksFixture::TASK_2_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());
        $data = json_decode($content, true);
        self::assertArrayHasKey('title', $data);
        self::assertEquals('Complete child tasks before closing this one', $data['title']);
    }

    public function testAlreadyDone(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request(
            'POST',
            sprintf(self::URI, TasksFixture::TASK_3_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());
        $data = json_decode($content, true);
        self::assertArrayHasKey('title', $data);
        self::assertEquals('Task was already marked as done', $data['title']);
    }
}
