<?php

use App\User\Domain\Event\UserCreatedEvent;
use PHPUnit\Framework\TestCase;

class UserCreatedEventTest extends TestCase
{
    private const ID = 1;
    private const MAIL = 'mail@mail.com';

    public function testEvent(): void{
        $event = new UserCreatedEvent(self::ID, self::MAIL);
        $this->assertEquals(self::ID, $event->getId());
        $this->assertEquals(self::MAIL, $event->getEmail());
        $this->assertInstanceOf(DateTimeImmutable::class, $event->getOccur());

    }
}