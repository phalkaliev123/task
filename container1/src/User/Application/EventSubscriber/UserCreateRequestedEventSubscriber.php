<?php

declare(strict_types=1);

namespace App\User\Application\EventSubscriber;

use App\Shared\Exception\ConstraintViolationException;
use App\User\Domain\Event\UserCreatedNotifyEvent;
use App\User\Application\Event\OnUserValidatedEvent;
use App\User\Application\Model\CreateUserCommand;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Application\Event\OnUserCreateRequestedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UserCreateRequestedEventSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private MessageBusInterface $messageBus,
        private ValidatorInterface $validator,
        private UserRepositoryInterface $userRepository,
        private EventDispatcherInterface $eventDispatcher
    ) {}

    public static function getSubscribedEvents(): array
    {
        return [
            OnUserCreateRequestedEvent::class => 'validateUser',
            OnUserValidatedEvent::class => 'userValidatedEvent',
            UserCreatedEvent::class => 'userCreatedEvent'
        ];
    }

    public function userCreatedEvent(UserCreatedEvent $event): void
    {
        $this->messageBus->dispatch(new UserCreatedNotifyEvent($event->getId(), $event->getEmail()));
    }

    public function userValidatedEvent(OnUserValidatedEvent $event): void
    {
        $createUserCommand = new CreateUserCommand();
        $createUserCommand->setEmail($event->getEmail());
        $createUserCommand->setFirstName($event->getFirstName());
        $createUserCommand->setLastName($event->getLastName());

        $this->messageBus->dispatch($createUserCommand);
    }

    public function validateUser(OnUserCreateRequestedEvent $event): void
    {
        $errors = $this->validator->validate($event);

        if (count($errors) > 0) {
            throw new ConstraintViolationException($errors);
        }

        $this->eventDispatcher->dispatch(new OnUserValidatedEvent(
            $event->getEmail(),
            $event->getFirstName(),
            $event->getLastName()
        ));
    }
}
