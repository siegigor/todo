<?php

declare(strict_types=1);

namespace App\Tests\Feature\Task;

use App\Tests\Feature\ApiUsersFixture;
use App\Tests\Feature\TransactionalWebTestCase;

class CreateTest extends TransactionalWebTestCase
{
    private const URI = '/api/task/create';

    public function testSuccess(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'priority' => 4,
            'title' => 'Some title',
            'description' => 'Some description',
            'parent' => TasksFixture::TASK_1_ID
        ]));

        self::assertEquals(201, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);
        self::assertArrayHasKey('id', $data);
    }

    public function testValidationFailed(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json'], json_encode([
            'priority' => 0,
            'parent' => '125'
        ]));

        self::assertEquals(422, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);
        self::assertArrayHasKey('priority', $data);
        self::assertEquals('This value should be between 1 and 5.', $data['priority']);
        self::assertArrayHasKey('title', $data);
        self::assertEquals('This value should not be blank.', $data['title']);
        self::assertArrayHasKey('description', $data);
        self::assertEquals('This value should not be blank.', $data['description']);
        self::assertArrayHasKey('parent', $data);
        self::assertEquals('This is not a valid UUID.', $data['parent']);
    }

    public function testUnauthorized(): void
    {
        $this->client->request('POST', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}
