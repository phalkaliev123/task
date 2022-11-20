<?php
namespace App\Tests\Api\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class GetUserTest extends WebTestCase
{
    public function testGetUser(): void
    {
        $client = static::createClient();

        // success
        $client->request('GET', '/user/1');
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $result = json_decode($response->getContent(),true, 512, JSON_THROW_ON_ERROR);

        $this->assertEquals([
            "id" => 1,
            "firstName" => "test first name",
            "lastName" => "test last name",
            "email" => "test@test.com"
        ], $result);

        // not exists
        $client->request('GET', '/user/99999');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_INTERNAL_SERVER_ERROR, $response->getStatusCode());

        // invalid value
        $client->request('GET', '/user/adsfsdfd');
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_NOT_FOUND, $response->getStatusCode());
    }
}