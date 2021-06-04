<?php

declare(strict_types=1);

namespace App\Controller\Api\Task;

use App\FetchModel\Task\Filter;
use App\FetchModel\Task\Sort;
use App\FetchModel\Task\TaskFetcher;
use App\Security\UserIdentity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

#[Route('/task')]
class TaskReadController extends AbstractController
{
    public function __construct(private TaskFetcher $fetcher, private DenormalizerInterface $denormalizer)
    {
    }

    #[Route('/list', methods: ["GET"])]
    public function tasksList(Request $request, #[CurrentUser] UserIdentity $user): JsonResponse
    {
        /** @var Filter $filter */
        $filter = $this->denormalizer->denormalize(
            $request->query->get('filter') ?: [],
            Filter::class,
            'array',
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );
        /** @var Sort $sort */
        $sort = $this->denormalizer->denormalize(
            $request->query->get('sort') ?: [],
            Sort::class,
            'array',
            [AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true]
        );

        $tasks = $this->fetcher->findBy($filter, $sort, $user->getId(), $request->query->getInt('page', 1));

        return $this->json(
            [
                'tasks' => $tasks->getItems(),
                'current_page' => $tasks->getCurrentPageNumber(),
                'total' => $tasks->getTotalItemCount()
            ],
            Response::HTTP_OK
        );
    }
}
