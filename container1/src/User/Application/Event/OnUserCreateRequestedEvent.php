<?php

declare(strict_types=1);

namespace App\User\Application\Event;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Contracts\EventDispatcher\Event;

final class OnUserCreateRequestedEvent extends Event
{
    #[Email]
    #[NotNull]
    #[NotBlank]
    #[Length(max: 100)]
    private ?string $email;

    #[NotNull]
    #[NotBlank]
    #[Length(min: 1, max: 100)]
    private ?string $firstName;

    #[NotNull]
    #[NotBlank]
    #[Length(min: 1, max: 100)]
    private ?string $lastName;

    public function __construct(?string $email, ?string $firstName, ?string $lastName){
        $this->email = $email;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function setFirstName(?string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function setLastName(?string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }
}
