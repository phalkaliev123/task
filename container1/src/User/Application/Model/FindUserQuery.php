<?php

declare(strict_types=1);

namespace App\User\Application\Model;

final class FindUserQuery
{
    public function __construct(private int $id){}

    public function getId(): int
    {
        return $this->id;
    }
}
