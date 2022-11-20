<?php

use App\Shared\EventListener\ApiExceptionListener;
use App\Shared\Exception\ConstraintViolationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use function PHPUnit\Framework\once;

class ApiExceptionListenerTest extends TestCase
{
    private const ERROR_JSON = '{
       "type" : "https:\/\/symfony.com\/errors\/validation",
       "title" : "Validation Failed",
       "detail":"email: This value is not a valid email address.",
       "violations":[
          {
             "propertyPath":"email",
             "title":"This value is not a valid email address.",
             "parameters":{
                "{{ value }}":"\"tess2sdfsdf13bv.bg\""
             },
             "type":"urn:uuid:bd79c0ab-ddba-46cc-a703-a7a4b08de310"
           }
       ]
    }';

    private const ERROR_JSON_RESPONSE = '{"code":400,"message":"ConstraintViolations","errors":{"type":"https:\/\/symfony.com\/errors\/validation","title":"Validation Failed","detail":"email: This value is not a valid email address.","violations":[{"propertyPath":"email","title":"This value is not a valid email address.","parameters":{"{{ value }}":"\u0022tess2sdfsdf13bv.bg\u0022"},"type":"urn:uuid:bd79c0ab-ddba-46cc-a703-a7a4b08de310"}]}}';

    public function testViolation(): void{
         $kernel = $this->createMock(KernelInterface::class);
         $serializer = $this->createMock(SerializerInterface::class);
         $listener = new ApiExceptionListener($kernel, $serializer);
         $constraint = $this->createMock(ConstraintViolationList::class);
         $constraint->method('count')->willReturn(1);
         $exception = new ConstraintViolationException($constraint);
         $kernel = $this->createMock(HttpKernelInterface::class);
         $event = new ExceptionEvent($kernel, new Request(), 1, $exception );

         $serializer->expects(once())->method('serialize')->with($constraint, 'json')->willReturn(self::ERROR_JSON);
         $listener->onKernelException($event);
         $this->assertInstanceOf(JsonResponse::class, $event->getResponse());
         $this->assertEquals(self::ERROR_JSON_RESPONSE, $event->getResponse()->getContent());
    }
}