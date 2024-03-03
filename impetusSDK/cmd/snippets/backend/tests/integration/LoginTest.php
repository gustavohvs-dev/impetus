<?php

use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
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

    public function testLoginSuccess()
    {
        require dirname(__FILE__, 3) . "/app/config/config.php";
        $data = [
            'username' => "admin",
            'password' => 'admin'
        ];
        $response = $this->http->post($systemConfig['path']."login", [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testLoginErrorWrongCredentials()
    {
        require dirname(__FILE__, 3) . "/app/config/config.php";
        $data = [
            'username' => '#non-existUsername',
            'password' => 'FHA*(!&#asdf7819'
        ];
        $response = $this->http->post($systemConfig['path']."login", [
            'body' => json_encode($data)
        ]);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function testLoginErrorWithoutCredentials()
    {
        require dirname(__FILE__, 3) . "/app/config/config.php";
        $response = $this->http->post($systemConfig['path']."login");
        $this->assertEquals(401, $response->getStatusCode());
    }

}