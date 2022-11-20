<?php


use App\User\Domain\Entity\User;
use App\User\Infrastructure\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ManagerRegistry;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\once;

class UserRepositoryTest extends TestCase
{
    public function testSave(): void{
        $user = $this->createMock(User::class);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->expects(once())->method('persist')->with($user);
        $entityManager->expects(once())->method('flush');
        $registry = $this->createMock(ManagerRegistry::class);

        $meta = $this->createMock(ClassMetadata::class);
        $entityManager->method('getClassMetadata')->willReturn($meta);

        $registry->expects(once())->method('getManagerForClass')->with(User::class)->willReturn($entityManager);
        $repository = new UserRepository($registry);

        $repository->save($user);
    }
}