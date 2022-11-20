<?php

use App\User\Domain\Event\UserCreatedNotifyEvent;
use PHPUnit\Framework\TestCase;

class UserCreatedNotifyEventTest extends TestCase
{
    private const ID = 1;
    private const MAIL = 'mail@mail.com';

    public function testEvent(): void{
        $event = new UserCreatedNotifyEvent(self::ID, self::MAIL);
        $this->assertEquals(self::ID, $event->getId());
        $this->assertEquals(self::MAIL, $event->getEmail());
    }
}