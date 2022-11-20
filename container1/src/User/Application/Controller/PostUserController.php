<?php

declare(strict_types=1);

namespace App\User\Application\Controller;

use App\User\Application\Event\OnUserCreateRequestedEvent;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/users", name: "add_user", methods: ['POST'])]
class PostUserController extends AbstractController
{
    private EventDispatcherInterface $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function __invoke(Request $request): JsonResponse
    {
        $parameters = json_decode(
            $request->getContent(),
            true, 512,
            JSON_THROW_ON_ERROR
        );

        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent(
            $parameters['email'] ?? '',
            $parameters['firstName'] ?? '',
            $parameters['lastName'] ?? ''
        ));

        return JsonResponse::fromJsonString(
            $request->getSession()->get('last_user_created')
        );
    }
}
