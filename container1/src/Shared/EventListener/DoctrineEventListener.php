<?php

namespace App\Shared\EventListener;

use App\Shared\Exception\ConstraintViolationException;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DoctrineEventListener
{
    public function __construct(private ValidatorInterface $validator){}

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $this->validateEntity($entity);
    }
    
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getEntity();
        $this->validateEntity($entity);
    }

    private function validateEntity(Object $entity): void{
        $errors = $this->validator->validate($entity);
        if (count($errors) > 0) {
            throw new ConstraintViolationException($errors);
        }
    }
}
