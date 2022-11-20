<?php

namespace App\User\Application\Service;

use App\User\Domain\Event\UserCreatedNotifyEvent;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class UserCreatedNotifyHandler implements MessageHandlerInterface
{
    public function __invoke(UserCreatedNotifyEvent $userCreatedNotifyEvent): void
    {

    }
}