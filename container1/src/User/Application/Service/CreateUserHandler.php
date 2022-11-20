<?php

declare(strict_types=1);

namespace App\User\Application\Service;

use App\User\Application\Model\CreateUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\SerializerInterface;

final class CreateUserHandler implements MessageHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher,
        private SerializerInterface $serializer,
        private RequestStack $requestStack
    ) {}

    public function __invoke(CreateUserCommand $createUserCommand): void
    {
        $user = new User();
        $user->setEmail($createUserCommand->getEmail());
        $user->setFirstName($createUserCommand->getFirstName());
        $user->setLastName($createUserCommand->getLastName());

        $this->userRepository->save($user);
        $event = new UserCreatedEvent($user->getId(), $user->getEmail());

        $user->recordDomainEvent($event);

        $this->requestStack->getSession()->set(
            'last_user_created',
            $this->serializer->serialize($user, 'json')
        );

        foreach ($user->pullDomainEvents() as $domainEvent) {
            $this->eventDispatcher->dispatch($domainEvent);
        }
    }
}
