<?php

use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
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

    /**
     * Verify if the server is working normally
     */
    public function testStatusServer()
    {
        require "config.php";
        $response = $this->http->request('GET', $config['path']);
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getBody()->getContents());
        $this->assertEquals(200, $body->code);
    }

}