<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Model\FindUserQuery;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class UserFinderHandler implements MessageHandlerInterface
{
    public function __construct(
       private UserRepositoryInterface $userRepository,
       private SerializerInterface $serializer,
    ) {}

    public function __invoke(FindUserQuery $findUserQuery): User
    {
        $userId = $findUserQuery->getId();

        $user = $this->userRepository->find($userId);
        if (!$user) {
            throw new \InvalidArgumentException(\sprintf('User with ID: %s not found', $userId));
        }

        return $user;
    }
}
