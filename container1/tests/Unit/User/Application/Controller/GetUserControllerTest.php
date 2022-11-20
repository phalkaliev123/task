<?php

declare(strict_types=1);

namespace App\User\Application\Controller;

use App\User\Application\Model\FindUserQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;


class GetUserControllerTest extends WebTestCase
{
    public function testGetUser()
    {
        $expected = [
            'some key' => 'some data'
        ];
        $id = 1;

        $handledStamp = $this->createMock(HandledStamp::class);
        $handledStamp->method('getHandlerName')->willReturn('name');
        $handledStamp->method('getResult')->willReturn($expected);

        $envelope = $this->createMock(Envelope::class);
        $envelope->method('all')->with(HandledStamp::class)->willReturn([$handledStamp]);

        $messageBus = $this->createMock(MessageBusInterface::class);
        $messageBus->method('dispatch')->with(new FindUserQuery($id))->willReturn($envelope);

        $container = $this->createMock(ContainerInterface::class);
        $container->method('has')->with('serializer')->willReturn(false);
        $controller = new GetUserController($messageBus);
        $controller->setContainer($container);
        $result = $controller->__invoke($id);

        $this->assertInstanceOf(JsonResponse::class, $result);
        $this->assertEquals('{"some key":"some data"}', $result->getContent());
    }

}
