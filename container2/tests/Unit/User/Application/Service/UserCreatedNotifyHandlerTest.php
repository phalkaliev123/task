<?php

namespace App\Tests\Unit\User\Application\Service;

use App\User\Application\Service\UserCreatedNotifyHandler;
use App\User\Domain\Event\UserCreatedNotifyEvent;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class UserCreatedNotifyHandlerTest extends TestCase
{
    public function testHandler()
    {
        $logger = $this->createMock(LoggerInterface::class);

        $userCreatedNotifyEvent = $this->createMock(UserCreatedNotifyEvent::class);
        $userCreatedNotifyEvent->expects(self::once())->method('getId')->willReturn(1);
        $userCreatedNotifyEvent->expects(self::once())->method('getEmail')->willReturn('mail@mail.com');

        $userCreatedNotifyHandler = new UserCreatedNotifyHandler($logger);
        $userCreatedNotifyHandler->__invoke($userCreatedNotifyEvent);

        $logger->method('info')->withConsecutive(
            ["User id created: 1"],
            ["User email created: mail@mail.com"]
        );
    }
}