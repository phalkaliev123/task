<?php

declare(strict_types=1);

namespace App\User\Application\Controller;

use App\User\Application\Model\FindUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/user/{id}", name: "get_user", methods: ['GET'], requirements: ['id' => '\d+'])]
class GetUserController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(int $id)
    {
        $user = $this->handle(new FindUserQuery($id));
        return $this->json($user);
    }
}
