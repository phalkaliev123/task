<?php

use App\Shared\Aggregate\AggregateRoot;
use App\User\Domain\Event\UserCreatedNotifyEvent;
use PHPUnit\Framework\TestCase;

class AggregateRootTest extends TestCase
{
    public function testAggregateRoot(): void{
        $event = new UserCreatedNotifyEvent(1, 'mail@mail.com');
        $aggregateRoot = new class extends AggregateRoot {};
        $aggregateRoot->recordDomainEvent($event);
        $this->assertEquals([$event], $aggregateRoot->pullDomainEvents());
    }
}