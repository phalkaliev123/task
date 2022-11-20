<?php


use App\Shared\Exception\ConstraintViolationException;
use App\User\Application\Event\OnUserCreateRequestedEvent;
use App\User\Application\Event\OnUserValidatedEvent;
use App\User\Application\EventSubscriber\UserCreateRequestedEventSubscriber;
use App\User\Application\Model\CreateUserCommand;
use App\User\Domain\Event\UserCreatedEvent;
use App\User\Domain\Event\UserCreatedNotifyEvent;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function PHPUnit\Framework\once;

class UserCreateRequestedEventSubscriberTest extends TestCase
{
    private UserCreateRequestedEventSubscriber $subscriber;
    private MockObject $messageBus;
    private ValidatorInterface $validator;
    private UserRepositoryInterface $userRepository;
    private EventDispatcherInterface $eventDispatcher;

    private const MAIL = 'mail@mail.com';
    private const FIRST_NAME = 'first name';
    private const LAST_NAME ='last name';
    private const USER_ID = 1;

    protected function setUp(): void
    {
        parent::setUp();

        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $this->subscriber = new UserCreateRequestedEventSubscriber($this->messageBus, $this->validator, $this->userRepository, $this->eventDispatcher);
    }

    public function testGetSubscribedEvents()
    {
        $this->assertEquals([
            OnUserCreateRequestedEvent::class => 'validateUser',
            OnUserValidatedEvent::class => 'userValidatedEvent',
            UserCreatedEvent::class => 'userCreatedEvent'
        ], UserCreateRequestedEventSubscriber::getSubscribedEvents());
    }

    public function testValidateUserSuccess()
    {
        $this->eventDispatcher->expects(once())->method('dispatch')->with(new OnUserValidatedEvent(self::MAIL, self::FIRST_NAME, self::LAST_NAME));
        $event = new OnUserCreateRequestedEvent(self::MAIL, self::FIRST_NAME, self::LAST_NAME);
        $this->subscriber->validateUser($event);
    }

    public function testValidateUseInvalid()
    {
        $event = new OnUserCreateRequestedEvent('mail', self::FIRST_NAME, self::LAST_NAME);

        $constraint = $this->createMock(ConstraintViolationList::class);
        $constraint->method('count')->willReturn(1);

        $this->validator->method('validate')->with($event)->willReturn($constraint);

        $this->expectException(ConstraintViolationException::class);
        $this->subscriber->validateUser($event);
    }

    public function testUserCreateEvent()
    {
        $event = new UserCreatedEvent(self::USER_ID, self::MAIL);
        $this->messageBus->expects(once())->method('dispatch')->with(new UserCreatedNotifyEvent(self::USER_ID, self::MAIL));
        $this->subscriber->userCreatedEvent($event);
    }

    public function testUserValidatedEvent()
    {
        $event = new OnUserValidatedEvent(self::MAIL, self::FIRST_NAME, self::LAST_NAME);

        $createUserCommand = new CreateUserCommand();
        $createUserCommand->setEmail(self::MAIL);
        $createUserCommand->setFirstName(self::FIRST_NAME);
        $createUserCommand->setLastName(self::LAST_NAME);
        $this->messageBus->expects(once())->method('dispatch')->with($createUserCommand);
        $this->subscriber->userValidatedEvent($event);
    }

}