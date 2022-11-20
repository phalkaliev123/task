<?php

use App\User\Application\Model\CreateUserCommand;
use App\User\Application\Service\CreateUserHandler;
use App\User\Domain\Entity\User;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use function PHPUnit\Framework\once;

class CreateUserHandlerTest extends TestCase
{
    private const MAIL = 'mail@mail.com';
    private const FIRST_NAME = 'first name';
    private const LAST_NAME = 'last name';
    private const USER_ID = 1;

    public function testHandler(): void
    {
        $jsonUser = '{
             "id" => '.self::USER_ID.',
             "firstName" : "'.self::FIRST_NAME.'",
             "lastName" : "'.self::LAST_NAME.'",
             "email" : "'.self::MAIL.'"
        }';
        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);
        $serializer = $this->createMock(SerializerInterface::class);
        $requestStack = $this->createMock(RequestStack::class);

        $handler = new CreateUserHandler($userRepository, $eventDispatcher, $serializer, $requestStack);

        $createUserCommand = new CreateUserCommand();
        $createUserCommand->setEmail(self::MAIL);
        $createUserCommand->setFirstName(self::FIRST_NAME);
        $createUserCommand->setLastName(self::LAST_NAME);

        $user = new User();

        $user->setEmail(self::MAIL);
        $user->setFirstName(self::FIRST_NAME);
        $user->setLastName(self::LAST_NAME);
        $event = new UserCreatedEvent(self::USER_ID, self::MAIL);
        $userRepository->expects(once())->method('save')->will($this->returnCallback(function ($user) use($event, $serializer, $jsonUser) {
            $user->setId(self::USER_ID);
            $user->recordDomainEvent($event);
            $serializer->expects(once())->method('serialize')->with($user, 'json')->willReturn($jsonUser);
        }));

        $session = $this->createMock(SessionInterface::class);
        $session->expects(once())->method('set')->with('last_user_created', $jsonUser);
        $requestStack->expects(once())->method('getSession')->willReturn($session);

        $eventDispatcher->method('dispatch')->withAnyParameters();

        $handler->__invoke($createUserCommand);
    }
}