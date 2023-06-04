<?php

use Impetus\Framework\ImpetusUtils;
use PHPUnit\Framework\TestCase;

class UtilsTest extends TestCase
{

    public function testIsEmailSuccess()
    {
        $this->assertEquals(true, ImpetusUtils::isEmail("impetus@email.com"));
        $this->assertEquals(true, ImpetusUtils::isEmail("test@email.com.br"));
        $this->assertEquals(true, ImpetusUtils::isEmail("test2@email.eu"));
    }

    public function testIsEmailError()
    {
        $this->assertEquals(false, ImpetusUtils::isEmail("impetus@email"));
        $this->assertEquals(false, ImpetusUtils::isEmail("teste.com.br"));
        $this->assertEquals(false, ImpetusUtils::isEmail("email"));
    }

}