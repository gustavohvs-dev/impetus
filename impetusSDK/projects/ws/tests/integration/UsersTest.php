<?php

use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    private $http;
    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['http_errors' => false]);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testCreateUsersSuccess()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $this->http = new GuzzleHttp\Client(['http_errors' => false]);
        $loginResponse = $this->http->post($systemConfig['webservicePath']."login", [
            'body' => json_encode([
                'username' => "admin",
                'password' => 'admin'
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        $loginData = json_decode($loginResponse->getBody(), true);
        $bearerToken = $loginData['token'];
        $data = [
            "name" => "Texto de Exemplo",
            "email" => "example@mail.com",
            "username" => "example",
            "password" => "@Abcd123",
            "permission" => "user",
            "pessoaId" => 1,
            "status" => "ACTIVE"
        ];
        $response = $this->http->post($systemConfig['webservicePath']."users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() !== 200) {
            echo 'Response code: ' . $response->getStatusCode() . "\n";
            echo 'Response body: ' . $response->getBody() . "\n";
        }
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateUsersError()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $this->http = new GuzzleHttp\Client(['http_errors' => false]);
        $loginResponse = $this->http->post($systemConfig['webservicePath']."login", [
            'body' => json_encode([
                'username' => "admin",
                'password' => 'admin'
            ]),
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
        $loginData = json_decode($loginResponse->getBody(), true);
        $bearerToken = $loginData['token'];
        $data = [
            "name" => "",
            "email" => "",
            "username" => "",
            "password" => "",
            "permission" => null,
            "pessoaId" => "",
            "status" => "ACTIVE"
        ];
        $response = $this->http->post($systemConfig['webservicePath']."users/create", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        $this->assertEquals(400, $response->getStatusCode());
    }  
}