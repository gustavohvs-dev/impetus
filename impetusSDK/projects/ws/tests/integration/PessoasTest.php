<?php

use PHPUnit\Framework\TestCase;

class PessoasTest extends TestCase
{
    private $http;
    static private $pessoaId;
    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['http_errors' => false]);
    }

    public function tearDown(): void
    {
        $this->http = null;
    }

    public function testCreatePessoasSuccess()
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
            "tipoDocumento" => "CNPJ",
            "documento" => "66.936.165/0001-88",
            "nome" => "Mário e Ana Advocacia ME",
            "nomeFantasia" => null,
            "enderecoLogradouro" => null,
            "enderecoNumero" => null,
            "enderecoComplemento" => null,
            "enderecoCidade" => null,
            "enderecoEstado" => null,
            "enderecoPais" => null,
            "enderecoCep" => null
        ];
        $response = $this->http->post($systemConfig['webservicePath'] . "pessoas/create", [
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
        PessoasTest::$pessoaId = $responseData["id"];
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testCreatePessoasError()
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
            "status" => null,
            "tipoDocumento" => null,
            "documento" => null,
            "nome" => null,
            "nomeFantasia" => null,
            "enderecoLogradouro" => null,
            "enderecoNumero" => null,
            "enderecoComplemento" => null,
            "enderecoCidade" => null,
            "enderecoEstado" => null,
            "enderecoPais" => null,
            "enderecoCep" => null
        ];
        $response = $this->http->post($systemConfig['webservicePath'] . "pessoas/create", [
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

    public function testReadPessoasSuccess()
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
        $response = $this->http->get($systemConfig['webservicePath'] . "pessoas/get?id=".PessoasTest::$pessoaId, [
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

    public function testReadPessoasError()
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
        $response = $this->http->get($systemConfig['webservicePath'] . "pessoas/get?id=Text%20de%20Exemplo", [
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

    public function testUpdatePessoasSuccess()
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
            "id" => PessoasTest::$pessoaId,
            "status" => "ACTIVE",
            "tipoDocumento" => "CNPJ",
            "documento" => "66.936.165/0001-88",
            "nome" => "Mário e Ana Advocacia ME",
            "nomeFantasia" => null,
            "enderecoLogradouro" => "Rua Alameda Granada",
            "enderecoNumero" => "145",
            "enderecoComplemento" => null,
            "enderecoCidade" => "Barueri",
            "enderecoEstado" => "São Paulo",
            "enderecoPais" => "Brasil",
            "enderecoCep" => "06473-065",
            "enderecoBairro" => "Alphaville Conde II"
        ];
        $response = $this->http->put($systemConfig['webservicePath'] . "pessoas/update", [
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

    public function testUpdatePessoasError()
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
            "status" => null,
            "tipoDocumento" => null,
            "documento" => null,
            "nome" => null,
            "nomeFantasia" => null,
            "enderecoLogradouro" => null,
            "enderecoNumero" => null,
            "enderecoComplemento" => null,
            "enderecoCidade" => null,
            "enderecoEstado" => null,
            "enderecoPais" => null,
            "enderecoCep" => null,
            "enderecoBairro" => null
        ];
        $response = $this->http->put($systemConfig['webservicePath'] . "pessoas/update", [
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

    public function testDeletePessoasSuccess()
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
        $response = $this->http->delete($systemConfig['webservicePath'] . "pessoas/delete?id=" . PessoasTest::$pessoaId, [
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

    public function testDeletePessoasError()
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
        $response = $this->http->delete($systemConfig['webservicePath'] . "pessoas/delete?id=Text%20de%20Exemplo", [
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