<?php


use App\Shared\Exception\ConstraintViolationException;
use App\User\Application\Event\OnUserCreateRequestedEvent;
use PragmaRX\Random\Random;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Storage\MockFileSessionStorage;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

class PostUserTest extends KernelTestCase
{
    private EventDispatcherInterface $eventDispatcher;
    private Request $request;

    protected function setUp(): void
    {
        $container = static::getContainer();
        $requestStack = $container->get(RequestStack::class);
        $this->request = new Request();
        $this->request->setSession(new Session(new MockFileSessionStorage()));
        $requestStack->push($this->request);
        $this->eventDispatcher = $container->get(EventDispatcherInterface::class);
    }

    public function testPostUser(): void{

        // save success
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent('mail@mail.com', 'first name', 'last name'));
        $result = json_decode($this->request->getSession()->get('last_user_created'), true);
        unset($result['id']);
        $expected = [
            "firstName" => "first name",
            "lastName" => "last name",
            "email" => "mail@mail.com"
        ];
        $this->assertEquals($expected, $result);

        // invalid mail
        $this->expectException(ConstraintViolationException::class);
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent('mail', 'first name', 'last name'));
    }

    public function testPostUserMailAlreadyExists(): void
    {
        $this->expectException(HandlerFailedException::class);
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent('mail@mail.com', 'first name', 'last name'));
    }

    public function testPostUserMailTooLong(): void{
        $this->expectException(ConstraintViolationException::class);
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent((new Random())->size(101)->get(), 'first name', 'last name'));
    }

    public function testPostUserFirstNameTooLong(): void{
        $this->expectException(ConstraintViolationException::class);
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent('secon@mail.com', (new Random())->size(101)->get(), 'last name'));
    }

    public function testPostUserLastNameTooLong(): void{
        $this->expectException(ConstraintViolationException::class);
        $this->eventDispatcher->dispatch(new OnUserCreateRequestedEvent('secon@mail.com', 'first name', (new Random())->size(101)->get()));
    }
}