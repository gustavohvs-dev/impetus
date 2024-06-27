<?php

use app\utils\ImpetusJWT;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{

    public function test_create_and_validate_token()
    {
        $ImpetusJWT = new ImpetusJWT;
        $jwt = $ImpetusJWT->encode("1", "username", ["id" => 1, "username" => "username"], 24, "#SECRETKey12318209");
        $decode = $ImpetusJWT->decode($jwt, "#SECRETKey12318209");
        $this->assertEquals("1", $decode->payload->id);
        $this->assertEquals("username", $decode->payload->username);
    }

    public function test_invalid_token()
    {
        $ImpetusJWT = new ImpetusJWT;
        $decode = $ImpetusJWT->decode("invalid_token", "#SECRETKey12318209");
        $this->assertEquals("0", $decode->status);
    }

    public function test_expired_token()
    {
        $ImpetusJWT = new ImpetusJWT;
        $jwt = $ImpetusJWT->encode("1", "username", ["id" => 1, "username" => "username"], -1, "#SECRETKey12318209");
        $decode = $ImpetusJWT->decode($jwt, "#SECRETKey12318209");
        $this->assertEquals("0", $decode->status);
    }

    public function test_wrong_secret_token()
    {
        $ImpetusJWT = new ImpetusJWT;
        $jwt = $ImpetusJWT->encode("1", "username", ["id" => 1, "username" => "username"], -1, "#SECRETKey12318209");
        $decode = $ImpetusJWT->decode($jwt, "@SUPERSecretKey1093824$$#@!@#@#!%$!");
        $this->assertEquals("0", $decode->status);
    }

}