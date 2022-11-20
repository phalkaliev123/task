<?php

use App\User\Application\Event\OnUserCreateRequestedEvent;
use PHPUnit\Framework\TestCase;

class OnUserCreateRequestedEventTest extends TestCase
{
    private const MAIL = 'mail@mail.com';
    private const FIRST_NAME = 'first name';
    private const LAST_NAME ='last name';

    public function testOnUserCreateRequestedEvent(): void{
        $event = new OnUserCreateRequestedEvent(self::MAIL, self::FIRST_NAME, self::LAST_NAME);

        $this->assertEquals(self::MAIL, $event->getEmail());
        $this->assertEquals(self::FIRST_NAME, $event->getFirstName());
        $this->assertEquals(self::LAST_NAME, $event->getLastName());

        $event->setFirstName('fn');
        $event->setLastName('ln');
        $event->setEmail('mail@test.com');

        $this->assertEquals('mail@test.com', $event->getEmail());
        $this->assertEquals('fn', $event->getFirstName());
        $this->assertEquals('ln', $event->getLastName());
    }
}