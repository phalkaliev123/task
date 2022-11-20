<?php


use App\User\Application\Model\FindUserQuery;
use PHPUnit\Framework\TestCase;

class FindUserQueryTest extends TestCase
{
    public function testCommand(): void
    {
        $id = 1;
        $query = new FindUserQuery($id);
        $this->assertEquals($id, $query->getId());
    }
}