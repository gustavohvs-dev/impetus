<?php

use PHPUnit\Framework\TestCase;

class ObservacoesTest extends TestCase
{
    private $http;
    static private $observacaoId;
    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['http_errors' => false]);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testCreateobservacoesSuccess()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
            "status" => "ACTIVE",
            "usuarioId" => "1",
            "entidade" => "pessoas",
            "entidadeId" => "1",
            "texto" => "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum."
        ];
        $response = $this->http->post($systemConfig['webservicePath'] . "observacoes/create", [
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
        ObservacoesTest::$observacaoId = $responseData["id"];
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreateobservacoesError()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
            "entidade" => null,
            "entidadeId" => null,
            "texto" => null
        ];
        $response = $this->http->post($systemConfig['webservicePath'] . "observacoes/create", [
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

    public function testReadobservacoesSuccess()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
        $response = $this->http->get($systemConfig['webservicePath'] . "observacoes/get?id=".ObservacoesTest::$observacaoId, [
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

    public function testReadobservacoesError()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
        $response = $this->http->get($systemConfig['webservicePath'] . "observacoes/get?id=Text%20de%20Exemplo", [
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

    public function testUpdateobservacoesSuccess()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
            "id" => ObservacoesTest::$observacaoId,
            "status" => "ACTIVE",
            "usuarioId" => "1",
            "entidade" => "pessoas",
            "entidadeId" => "1",
            "texto" => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using 'Content here, content here', making it look like readable English. Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text, and a search for 'lorem ipsum' will uncover many web sites still in their infancy. Various versions have evolved over the years, sometimes by accident, sometimes on purpose (injected humour and the like)."
        ];
        $response = $this->http->put($systemConfig['webservicePath'] . "observacoes/update", [
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

    public function testUpdateobservacoesError()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
            "id" => null,
            "entidade" => null,
            "entidadeId" => null,
            "texto" => null
        ];
        $response = $this->http->put($systemConfig['webservicePath'] . "observacoes/update", [
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

    public function testDeleteobservacoesSuccess()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
        $response = $this->http->delete($systemConfig['webservicePath'] . "observacoes/delete?id=" . ObservacoesTest::$observacaoId, [
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

    public function testDeleteobservacoesError()
    {
        require dirname(__FILE__, 4) . "/config.php";
        $loginResponse = $this->http->post($systemConfig['webservicePath'] . "login", [
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
        $response = $this->http->delete($systemConfig['webservicePath'] . "observacoes/delete?id=Text%20de%20Exemplo", [
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
}