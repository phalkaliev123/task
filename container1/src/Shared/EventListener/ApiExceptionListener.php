<?php

namespace App\Shared\EventListener;

use App\Shared\Exception\ConstraintViolationException;
use BaseHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Serializer\SerializerInterface;


class ApiExceptionListener
{
    private KernelInterface $kernel;

    private SerializerInterface $serializer;

    public function __construct(KernelInterface $kernel, SerializerInterface $serializer)
    {
        $this->kernel = $kernel;
        $this->serializer = $serializer;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        if($exception instanceof HandlerFailedException){
            $exception = $exception->getNestedExceptions()[0];
        }
        $message = $exception->getMessage();

        $errors = [];
        if ($exception instanceof ConstraintViolationException) {
            $errors = json_decode($this->serializer->serialize($exception->getViolations(), 'json'));
        }

        $statusCode = $exception instanceof HttpExceptionInterface ? $exception->getStatusCode() : $exception->getCode();
        $statusCode = $statusCode ?: Response::HTTP_INTERNAL_SERVER_ERROR;

        $response = [
            'code' => $statusCode,
            'message' => $message,
            'errors' => $errors,
        ];

        $response = new JsonResponse($response);
        $response->setStatusCode($statusCode);
        $response->headers->set('Content-Type', 'application/problem+json');
        $event->setResponse($response);
    }
}
