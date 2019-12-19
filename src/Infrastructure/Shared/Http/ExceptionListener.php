<?php
declare(strict_types=1);

namespace App\Infrastructure\Shared\Http;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $response = JsonResponse::create([
            'code' => $exception->getCode(),
            'message' => $exception->getMessage(),
        ]);

        $event->setResponse($response);
    }
}
