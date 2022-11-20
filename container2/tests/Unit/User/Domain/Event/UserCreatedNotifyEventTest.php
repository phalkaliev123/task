<?php

declare(strict_types=1);

namespace App\Unit\User\Domain\Event;

use App\User\Domain\Event\UserCreatedNotifyEvent;
use PHPUnit\Framework\TestCase;

class UserCreatedNotifyEventTest extends TestCase
{
    public function testHandler()
    {
        $id = 1;
        $email = 'mail@mail.com';
        $event = new UserCreatedNotifyEvent($id, $email);

        $this->assertEquals($id, $event->getId());
        $this->assertEquals($email, $event->getEmail());
    }
}