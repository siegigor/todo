<?php

declare(strict_types=1);

namespace App\Tests\Feature\Task;

use App\Tests\Feature\ApiUsersFixture;
use App\Tests\Feature\TransactionalWebTestCase;
use Ramsey\Uuid\Uuid;

class EditTest extends TransactionalWebTestCase
{
    private const URI = '/api/task/%s/edit';

    public function testSuccess(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request(
            'PUT',
            sprintf(self::URI, TasksFixture::TASK_1_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'priority' => 5,
                'title' => 'Some title',
                'description' => 'Some description',
                'parent' => TasksFixture::TASK_3_ID
            ])
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
            'PUT',
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
            'PUT',
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
            'PUT',
            sprintf(self::URI, TasksFixture::TASK_2_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json']
        );

        self::assertEquals(403, $this->client->getResponse()->getStatusCode());
    }

    public function testLoopParent(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user2Credentials());
        $this->client->request(
            'PUT',
            sprintf(self::URI, TasksFixture::TASK_2_ID),
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode([
                'priority' => 5,
                'title' => 'Some title',
                'description' => 'Some description',
                'parent' => TasksFixture::TASK_4_ID
            ])
        );

        self::assertEquals(400, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());
        $data = json_decode($content, true);
        self::assertArrayHasKey('title', $data);
        self::assertEquals('Task can\'t be its own parent', $data['title']);
    }
}
