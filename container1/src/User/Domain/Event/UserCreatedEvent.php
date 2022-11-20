<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use App\Shared\Event\DomainEventInterface;
use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedEvent extends Event implements DomainEventInterface
{
    protected \DateTimeImmutable $occur;

    public function __construct(private int $id, private string $email)
    {
        $this->occur = new \DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getOccur(): \DateTimeImmutable
    {
        return $this->occur;
    }

    public function getEmail(): string
    {
        return $this->email;
    }
}
