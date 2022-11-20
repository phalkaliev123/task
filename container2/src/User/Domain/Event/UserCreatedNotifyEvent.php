<?php

declare(strict_types=1);

namespace App\User\Domain\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class UserCreatedNotifyEvent extends Event
{
    public function __construct(private int $id, private string $email){}

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
}
