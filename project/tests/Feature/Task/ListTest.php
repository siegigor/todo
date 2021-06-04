<?php

declare(strict_types=1);

namespace App\Tests\Feature\Task;

use App\Tests\Feature\ApiUsersFixture;
use App\Tests\Feature\TransactionalWebTestCase;

class ListTest extends TransactionalWebTestCase
{
    private const URI = '/api/task/list';

    public function testSuccess(): void
    {
        $this->client->setServerParameters(ApiUsersFixture::user1Credentials());
        $this->client->request('GET', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);
        self::assertEquals(200, $this->client->getResponse()->getStatusCode());
        self::assertJson($content = $this->client->getResponse()->getContent());

        $data = json_decode($content, true);
        self::assertArrayHasKey('tasks', $data);
        self::assertArrayHasKey('current_page', $data);
        self::assertArrayHasKey('total', $data);
        self::assertEquals(1, $data['current_page']);
        self::assertEquals(2, $data['total']);
        self::assertEquals(TasksFixture::TASK_1_ID, $data['tasks'][0]['id'] ?? '');
        self::assertEquals(ApiUsersFixture::USER_1_ID, $data['tasks'][0]['author_id'] ?? '');
        self::assertEquals('todo', $data['tasks'][0]['status'] ?? '');
        self::assertEquals(TasksFixture::TASK_3_ID, $data['tasks'][1]['id'] ?? '');
        self::assertEquals(ApiUsersFixture::USER_1_ID, $data['tasks'][1]['author_id'] ?? '');
        self::assertEquals('done', $data['tasks'][1]['status'] ?? '');
    }

    public function testUnauthorized(): void
    {
        $this->client->request('GET', self::URI, [], [], ['CONTENT_TYPE' => 'application/json']);

        self::assertEquals(401, $this->client->getResponse()->getStatusCode());
    }
}
