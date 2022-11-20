<?php
namespace App\Tests\Api\User;

use PragmaRX\Random\Random;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class PostUserTest extends WebTestCase
{
    public function testPostUser(): void
    {
        // valid insert
        $data = [
            "email" => "new@mail.com",
            "firstName" => "new user 1",
            "lastName" => "new user 1"
        ];

        $requestData = json_encode($data);
        $client = static::createClient();
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $this->assertResponseIsSuccessful();
        $response = $client->getResponse();
        $this->assertTrue($response->headers->contains('Content-Type', 'application/json'));
        $result = json_decode($response->getContent(),true, 512);

        unset($result['id']);
        $this->assertEquals($data, $result);


        // mail already exists
        $data = [
            "email" => "new@mail.com",
            "firstName" => "new user 1",
            "lastName" => "new user 1"
        ];
        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        // too long mail
        $data = [
            "email" => (new Random())->size(101)->get(),
            "firstName" => "new user 1",
            "lastName" => "new user 1"
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        // invalid mail
        $data = [
            "email" => "invalid-mail",
            "firstName" => "new user 1",
            "lastName" => "new user 1"
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());


        // first name too long
        $data = [
            "email" => "mail@mail.com",
            "firstName" => (new Random())->size(101)->get(),
            "lastName" => "new user 1"
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());


        // first name too short
        $data = [
            "email" => "mail@mail.com",
            "firstName" => "",
            "lastName" => "new user 1"
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());


        // last name too long
        $data = [
            "email" => "mail@mail.com",
            "firstName" => 'first name',
            "lastName" => (new Random())->size(101)->get()
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());

        // last name too short
        $data = [
            "email" => "mail@mail.com",
            "firstName" => 'first name',
            "lastName" => ""
        ];

        $requestData = json_encode($data);
        $client->request('POST', '/users', [], [], ['content_type' => 'application/json'], $requestData);
        $response = $client->getResponse();
        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->getStatusCode());
    }
}