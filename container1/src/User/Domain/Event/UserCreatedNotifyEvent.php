<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedNotifyEvent extends Event implements DomainEventInterface
{
    public function __construct(private int $id, private string $email){}

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
