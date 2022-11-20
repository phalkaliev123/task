<?php


use App\Shared\Exception\ConstraintViolationException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationExceptionTest extends TestCase
{
    public function testViolation(): void{
        $violation = $this->createMock(ConstraintViolationListInterface::class);
        $exception = new ConstraintViolationException($violation);
        $result = $exception->getViolations();
        $this->assertInstanceOf(ConstraintViolationListInterface::class, $result);
    }
}