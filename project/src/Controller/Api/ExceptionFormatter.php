<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Validation\ValidationException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationInterface;

class ExceptionFormatter implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if ($exception instanceof ValidationException) {
            $errors = [];
            /** @var ConstraintViolationInterface $violation */
            foreach ($exception->getViolations() as $violation) {
                $errors[$violation->getPropertyPath()] = $violation->getMessage();
            }
            $event->setResponse(new JsonResponse($errors, Response::HTTP_UNPROCESSABLE_ENTITY));
        }

        if ($exception instanceof \DomainException) {
            $event->setResponse(new JsonResponse([
                'title' => $exception->getMessage(),
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $exception->getMessage(),
                ]
            ], Response::HTTP_BAD_REQUEST));
        }

        if ($exception instanceof \InvalidArgumentException) {
            $event->setResponse(new JsonResponse([
                'title' => $exception->getMessage(),
                'error' => [
                    'code' => Response::HTTP_BAD_REQUEST,
                    'message' => $exception->getMessage(),
                ]
            ], Response::HTTP_BAD_REQUEST));
        }
    }
}
