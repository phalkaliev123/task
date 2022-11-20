<?php

use App\User\Application\Event\OnUserValidatedEvent;
use PHPUnit\Framework\TestCase;

class OnUserValidatedEventTest extends TestCase
{
    public function testUserValidatedEvent(): void{
        $mail = 'mail@mail.com';
        $firstName = 'first name';
        $lastName = 'last name';

        $event = new OnUserValidatedEvent($mail, $firstName, $lastName);

        $this->assertEquals($mail, $event->getEmail());
        $this->assertEquals($firstName, $event->getFirstName());
        $this->assertEquals($lastName, $event->getLastName());
    }
}