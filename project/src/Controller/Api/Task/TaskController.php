<?php

declare(strict_types=1);

namespace App\Controller\Api\Task;

use App\Model\Task\Command\Create;
use App\Model\Task\Command\Delete;
use App\Model\Task\Command\Edit;
use App\Model\Task\Command\Finish;
use App\Model\Task\Entity\Task\Id;
use App\Model\Task\Entity\Task\Task;
use App\Security\UserIdentity;
use App\Security\Voter\TaskAccess;
use App\Validation\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/task')]
class TaskController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer, private ValidatorInterface $validator)
    {
    }

    #[Route('/create', methods: ["POST"])]
    public function create(Request $request, Create\Handler $handler, #[CurrentUser] UserIdentity $user): JsonResponse
    {
        /** @var Create\Command $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            Create\Command::class,
            'json',
            [
                'object_to_populate' => new Create\Command($id = Id::generate()->getValue(), $user->getId()),
                'ignored_attributes' => ['id', 'authorId']
            ]
        );
        $this->validator->validate($command);
        $handler($command);

        return $this->json(['id' => $id], Response::HTTP_CREATED);
    }

    #[Route('/{task}/edit', methods: ["PUT"])]
    public function edit(Request $request, Edit\Handler $handler, Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted(TaskAccess::ACCESS_MANAGE, $task);
        /** @var Edit\Command $command */
        $command = $this->serializer->deserialize(
            $request->getContent(),
            Edit\Command::class,
            'json',
            [
                'object_to_populate' => new Edit\Command($task->getId()->getValue()),
                'ignored_attributes' => ['id']
            ]
        );
        $this->validator->validate($command);
        $handler($command);

        return $this->json([], Response::HTTP_OK);
    }

    #[Route('/{task}/finish', methods: ["POST"])]
    public function finish(Finish\Handler $handler, Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted(TaskAccess::ACCESS_MANAGE, $task);
        $command = new Finish\Command($task->getId()->getValue());
        $this->validator->validate($command);
        $handler($command);

        return $this->json([], Response::HTTP_OK);
    }

    #[Route('/{task}/remove', methods: ["DELETE"])]
    public function remove(Delete\Handler $handler, Task $task): JsonResponse
    {
        $this->denyAccessUnlessGranted(TaskAccess::ACCESS_MANAGE, $task);
        $command = new Delete\Command($task->getId()->getValue());
        $this->validator->validate($command);
        $handler($command);

        return $this->json([], Response::HTTP_OK);
    }
}
