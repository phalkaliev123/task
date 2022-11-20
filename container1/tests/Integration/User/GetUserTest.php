<?php


use App\User\Application\Model\FindUserQuery;
use App\User\Domain\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class GetUserTest extends KernelTestCase
{
    public function testGetUser(): void{
        self::bootKernel();
        $container = static::getContainer();
        $messageBus = $container->get(MessageBusInterface::class);

        // found user
        $envelope = $messageBus->dispatch(new FindUserQuery(1));
        $handledStamps = $envelope->all(HandledStamp::class);
        $user = $handledStamps[0]->getResult();
        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals(1, $user->getId());
        $this->assertEquals('test first name', $user->getFirstName());
        $this->assertEquals('test last name', $user->getLastName());
        $this->assertEquals('test@test.com', $user->getEmail());


        // not found
        $this->expectException(HandlerFailedException::class);
        $messageBus->dispatch(new FindUserQuery(9999));
    }
}