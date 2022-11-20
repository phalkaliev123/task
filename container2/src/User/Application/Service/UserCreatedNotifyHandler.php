<?php

namespace App\User\Application\Service;

use App\User\Domain\Event\UserCreatedNotifyEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UserCreatedNotifyHandler implements MessageHandlerInterface
{
    public function __construct(protected  LoggerInterface $logger){}

    public function __invoke(UserCreatedNotifyEvent $userCreatedNotifyEvent): void
    {
        $this->logger->info("User id created: ".$userCreatedNotifyEvent->getId());
        $this->logger->info("User email created: ".$userCreatedNotifyEvent->getEmail());
    }
}