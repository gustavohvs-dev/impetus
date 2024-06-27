<?php

use app\utils\ImpetusJWT;
use PHPUnit\Framework\TestCase;

class JWTTest extends TestCase
{

    public function testCreateAndValidateToken()
    {
        $ImpetusJWT = new ImpetusJWT;
        $jwt = $ImpetusJWT->encode("1", "username", ["id" => 1, "username" => "username"], 24, "#SECRETKey12318209");
        $decode = $ImpetusJWT->decode($jwt, "#SECRETKey12318209");
        $this->assertEquals("1", $decode->payload->id);
        $this->assertEquals("username", $decode->payload->username);
    }

}