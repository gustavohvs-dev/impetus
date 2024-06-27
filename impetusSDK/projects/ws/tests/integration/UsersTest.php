<?php

use PHPUnit\Framework\TestCase;

class UsersTest extends TestCase
{
    private $http;
    private $userId;
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $data = [
            "name" => "Texto de Exemplo ".rand(),
            "email" => "example".rand()."@mail.com",
            "username" => "example".rand(),
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
        $responseData = json_decode($response->getBody(), true);
        $this->userId = $responseData["id"];
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
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

    public function testReadUsersSuccess()
    {
        var_dump($this->userId);
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $response = $this->http->get($systemConfig['webservicePath']."users/get?id=".$this->userId, [
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

    public function testReadUsersError()
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $response = $this->http->get($systemConfig['webservicePath']."users/get?id=Text%20de%20Exemplo", [
            'headers' => [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() !== 400) {
            echo 'Response code: ' . $response->getStatusCode() . "\n";
            echo 'Response body: ' . $response->getBody() . "\n";
        }
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function testEditUsersSuccess()
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $data = [
            "id" => $this->userId,
            "status" => "ACTIVE",
            "username" => "example ".rand(),
            "name" => "Texto de Exemplo ".rand(),
            "permission" => "user",
            "pessoaId" => "1",
            "password" => "@Abcd123",
            "email" => "example".rand()."@mail.com",
        ];
        $response = $this->http->put($systemConfig['webservicePath']."users/update", [
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

    public function testEditUsersError()
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $data = [
            "id" => $this->userId,
            "status" => "",
            "username" => "",
            "name" => "",
            "permission" => "",
            "pessoaId" => "",
            "password" => "",
            "email" => "",
        ];
        $response = $this->http->put($systemConfig['webservicePath']."users/update", [
            'body' => json_encode($data),
            'headers' => [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() !== 400) {
            echo 'Response code: ' . $response->getStatusCode() . "\n";
            echo 'Response body: ' . $response->getBody() . "\n";
        }
        $this->assertEquals(400, $response->getStatusCode());
    }

    /*
    public function testDeletUsersSuccess()
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $response = $this->http->delete($systemConfig['webservicePath']."delete?id=2", [
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

    public function testDeletUsersError()
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
        $this->assertEquals(200, $loginResponse->getStatusCode());
        $bearerToken = $loginData['token'];
        $response = $this->http->delete($systemConfig['webservicePath']."delete?id=Text%20de%20Exemplo", [
            'headers' => [
                'Authorization' => 'Bearer ' . $bearerToken,
                'Content-Type' => 'application/json',
            ],
        ]);
        if ($response->getStatusCode() !== 404) {
            echo 'Response code: ' . $response->getStatusCode() . "\n";
            echo 'Response body: ' . $response->getBody() . "\n";
        }
        $this->assertEquals(404, $response->getStatusCode());
    }
        */
}