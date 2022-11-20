<?php


use App\User\Application\Model\FindUserQuery;
use App\User\Application\Service\UserFinderHandler;
use App\User\Domain\Entity\User;
use App\User\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\SerializerInterface;
use function PHPUnit\Framework\once;

class UserFinderHandlerTest extends TestCase
{
    private UserFinderHandler $handler;
    private MockObject $userRepository;
    private MockObject $serializer;

    private const ID = 1;
    protected function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->createMock(UserRepositoryInterface::class);
        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->handler = new UserFinderHandler($this->userRepository,  $this->serializer);
    }

    public function testUserNotFound(): void{
        $findUserQuery = new FindUserQuery(self::ID);
        $this->userRepository->expects(once())->method('find')->with(self::ID)->willReturn(null);
        $this->expectException(InvalidArgumentException::class);
        $this->handler->__invoke($findUserQuery);
    }

    public function testUser(): void{
        $findUserQuery = new FindUserQuery(self::ID);
        $user = $this->createMock(User::class);
        $this->userRepository->expects(once())->method('find')->with(self::ID)->willReturn($user);
        $result = $this->handler->__invoke($findUserQuery);
        $this->assertEquals($user, $result);
    }
}