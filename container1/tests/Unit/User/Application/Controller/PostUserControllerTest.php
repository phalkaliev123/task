<?php

declare(strict_types=1);

namespace App\User\Application\Controller;

use App\User\Application\Event\OnUserCreateRequestedEvent;
use App\User\Application\Model\FindUserQuery;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;


class PostUserControllerTest extends WebTestCase
{
    public function testPostUser()
    {
        $json = '{
            "email" : "mail@mail.com",
            "firstName" : "first name",
            "lastName" : "Last name"
        }';

        $id = 1;

        $handledStamp = $this->createMock(HandledStamp::class);
        $handledStamp->method('getHandlerName')->willReturn('name');
        $handledStamp->method('getResult')->willReturn($json);

        $envelope = $this->createMock(Envelope::class);
        $envelope->method('all')->with(HandledStamp::class)->willReturn([$handledStamp]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->method('dispatch')->with(new FindUserQuery($id))->willReturn($envelope);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->with('serializer')->willReturn(false);

        $eventDispatcher = $this->createMock(EventDispatcherInterface::class);

        $session = $this->createMock(SessionInterface::class);
        $session->method('get')->with('last_user_created')->willReturn($json);

        $request = $this->createMock(Request::class);
        $request->method('getContent')->willReturn($json);
        $request->method('getSession')->willReturn($session);

        $controller = new PostUserController($eventDispatcher);
        $controller->setContainer($container);
        $result = $controller->__invoke($request);

        $event = new OnUserCreateRequestedEvent('mail@mail.com', 'first name', 'Last name');
        $eventDispatcher->method('dispatch')->with($event)->willReturn(new \stdClass());

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals($json, $result->getContent());
    }
}
