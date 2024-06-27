<?php

use app\utils\ImpetusUtils;
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

    public function testIsEmptySuccess()
    {
        $this->assertEquals(true, ImpetusUtils::isEmpty(""));
        $this->assertEquals(true, ImpetusUtils::isEmpty(null));
        $this->assertEquals(true, ImpetusUtils::isEmpty(0));
    }

    public function testIsEmptyError()
    {
        $this->assertEquals(false, ImpetusUtils::isEmpty("teste"));
        $this->assertEquals(false, ImpetusUtils::isEmpty(1));
        $this->assertEquals(false, ImpetusUtils::isEmpty(true));
    }

    public function testIsLongString()
    {
        $this->assertEquals(false, ImpetusUtils::IsLongString("tes", 5));
        $this->assertEquals(false, ImpetusUtils::IsLongString("teste", 5));
        $this->assertEquals(false, ImpetusUtils::IsLongString("teste teste teste", 17));
        $this->assertEquals(true, ImpetusUtils::IsLongString("teste", 1));
        $this->assertEquals(true, ImpetusUtils::IsLongString("teste", 4));
        $this->assertEquals(true, ImpetusUtils::IsLongString("teste teste teste", 16));
    }

    public function testIsNumber()
    {
        $this->assertEquals(true, ImpetusUtils::IsNumber(1));
        $this->assertEquals(true, ImpetusUtils::IsNumber(5.1));
        $this->assertEquals(true, ImpetusUtils::IsNumber(1.23424));
        $this->assertEquals(true, ImpetusUtils::IsNumber('1337e0'));
        $this->assertEquals(true, ImpetusUtils::IsNumber(0x539));
        $this->assertEquals(true, ImpetusUtils::IsNumber('42'));
        $this->assertEquals(false, ImpetusUtils::IsNumber("0x539"));
        $this->assertEquals(false, ImpetusUtils::IsNumber(null));
        $this->assertEquals(false, ImpetusUtils::IsNumber('0b10100111001'));
        $this->assertEquals(false, ImpetusUtils::IsNumber(array()));
        $this->assertEquals(false, ImpetusUtils::IsNumber("not numeric"));
    }

    public function testIsInt()
    {
        $this->assertEquals(true, ImpetusUtils::IsInt(1));
        $this->assertEquals(true, ImpetusUtils::IsInt('1337e0'));
        $this->assertEquals(true, ImpetusUtils::IsInt(0x539));
        $this->assertEquals(true, ImpetusUtils::IsInt('42'));
        $this->assertEquals(false, ImpetusUtils::IsInt(5.1));
        $this->assertEquals(false, ImpetusUtils::IsInt(1.23424));
        $this->assertEquals(false, ImpetusUtils::IsInt("0x539"));
        $this->assertEquals(false, ImpetusUtils::IsInt(null));
        $this->assertEquals(false, ImpetusUtils::IsInt('0b10100111001'));
        $this->assertEquals(false, ImpetusUtils::IsInt(array()));
        $this->assertEquals(false, ImpetusUtils::IsInt("not numeric"));
    }

    public function testIsBoolean()
    {
        $this->assertEquals(true, ImpetusUtils::isBoolean(true));
        $this->assertEquals(true, ImpetusUtils::isBoolean(false));
        $this->assertEquals(true, ImpetusUtils::isBoolean(1));
        $this->assertEquals(true, ImpetusUtils::isBoolean(0));
        $this->assertEquals(false, ImpetusUtils::isBoolean("1"));
        $this->assertEquals(false, ImpetusUtils::isBoolean("0"));
        $this->assertEquals(false, ImpetusUtils::isBoolean(""));
        $this->assertEquals(false, ImpetusUtils::isBoolean(null));
        $this->assertEquals(false, ImpetusUtils::isBoolean(20));
        $this->assertEquals(false, ImpetusUtils::isBoolean("20"));
    }

    public function testIsStrongPassword()
    {
        $this->assertEquals(true, ImpetusUtils::isStrongPassword("#ABC102398123"));
        $this->assertEquals(true, ImpetusUtils::isStrongPassword("120938A7319$"));
        $this->assertEquals(true, ImpetusUtils::isStrongPassword("*&@¨#*!@&#%1928371AHJSGDASJ6312873*&SÄD*!*@&¨#*^`^`^`Ç`Ç"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword("123"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword("12345678"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword("12345678ASDASDACXZ"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword("!123123123"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword("A@dgfa@das%fasd¨KAISN"));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword(null));
        $this->assertEquals(false, ImpetusUtils::isStrongPassword(""));
    }

}