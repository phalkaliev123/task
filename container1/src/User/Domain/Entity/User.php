<?php

namespace App\User\Domain\Entity;

use App\Shared\Aggregate\AggregateRoot;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[UniqueEntity('email', message: "Mail must be unique")]
#[Entity]
class User extends AggregateRoot
{
    #[Id, Column(type: "integer"), GeneratedValue(strategy: "IDENTITY")]
    private int $id;

    #[Column(type: "string", length: 100, unique: false, nullable: false)]
    private string $firstName;

    #[Column(type: "string", length: 100, unique: false, nullable: false)]
    private string $lastName;

    #[Column(type: "string", length: 100, unique: true, nullable: false)]
    private string $email;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }
}
