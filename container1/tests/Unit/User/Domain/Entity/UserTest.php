<?php

use App\User\Domain\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    private const ID = 1;
    private const MAIL = 'mail@mail.com';
    private const FIRST_NAME = 'first name';
    private const LAST_NAME ='last name';

    public function testUser(): void{
        $user = new User();
        $user->setId(self::ID);
        $user->setEmail(self::MAIL);
        $user->setFirstName(self::FIRST_NAME);
        $user->setLastName(self::LAST_NAME);

        $this->assertEquals(self::ID, $user->getId());
        $this->assertEquals(self::MAIL, $user->getEmail());
        $this->assertEquals(self::FIRST_NAME, $user->getFirstName());
        $this->assertEquals(self::LAST_NAME, $user->getLastName());
    }
}