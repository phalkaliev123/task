<?php

use App\User\Application\Model\CreateUserCommand;
use PHPUnit\Framework\TestCase;

class CreateUserCommandTest extends TestCase
{
    private const MAIL = 'mail@mail.com';
    private const FIRST_NAME = 'first name';
    private const LAST_NAME ='last name';

    public function testCommand(): void
    {
        $command = new CreateUserCommand();
        $command->setEmail(self::MAIL);
        $command->setFirstName(self::FIRST_NAME);
        $command->setLastName(self::LAST_NAME);

        $this->assertEquals(self::MAIL, $command->getEmail());
        $this->assertEquals(self::FIRST_NAME, $command->getFirstName());
        $this->assertEquals(self::LAST_NAME, $command->getLastName());
    }
}