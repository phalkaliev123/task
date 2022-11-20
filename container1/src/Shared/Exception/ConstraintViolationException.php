<?php

namespace App\Shared\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolationException extends HttpException
{
    private ConstraintViolationListInterface $violations;

    public function __construct(
        ConstraintViolationListInterface $violations,
        string $message = 'ConstraintViolations',
        int $code = Response::HTTP_BAD_REQUEST
    ) {
        $this->violations = $violations;
        parent::__construct($code, $message, null, [], $code);
    }

    public function getViolations(): ConstraintViolationListInterface
    {
        return $this->violations;
    }
}
