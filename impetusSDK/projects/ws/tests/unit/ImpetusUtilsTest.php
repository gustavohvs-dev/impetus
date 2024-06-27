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

    public function testIsShortString()
    {
        $this->assertEquals(true, ImpetusUtils::IsShortString("tes", 5));
        $this->assertEquals(true, ImpetusUtils::IsShortString("teste", 6));
        $this->assertEquals(true, ImpetusUtils::IsShortString("teste teste teste", 18));
        $this->assertEquals(false, ImpetusUtils::IsShortString("teste", 1));
        $this->assertEquals(false, ImpetusUtils::IsShortString("teste", 4));
        $this->assertEquals(false, ImpetusUtils::IsShortString("teste teste teste", 12));
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

    public function testIsPassword()
    {
        $this->assertEquals(true, ImpetusUtils::isPassword("#ABC102398123"));
        $this->assertEquals(true, ImpetusUtils::isPassword("120938A7319$"));
        $this->assertEquals(true, ImpetusUtils::isPassword("*&@¨#*!@&#%1928371AHJSGDASJ6312873*&SÄD*!*@&¨#*^`^`^`Ç`Ç"));
        $this->assertEquals(true, ImpetusUtils::isPassword("12345678ASDASDACXZ"));
        $this->assertEquals(false, ImpetusUtils::isPassword("123"));
        $this->assertEquals(false, ImpetusUtils::isPassword("12345678"));
        $this->assertEquals(false, ImpetusUtils::isPassword("!123123123"));
        $this->assertEquals(false, ImpetusUtils::isPassword(null));
        $this->assertEquals(false, ImpetusUtils::isPassword(""));
    }

    public function testEnum()
    {
        $this->assertEquals(true, ImpetusUtils::enum("teste", ["teste","teste1","teste2"]));
        $this->assertEquals(false, ImpetusUtils::enum("teste5", ["teste","teste1","teste2"]));
    }

    public function testIsDate()
    {
        $this->assertEquals(true, ImpetusUtils::isDate("2024-01-01"));
        $this->assertEquals(false, ImpetusUtils::isDate("hoje"));
    }

    public function testBodyCheckFields()
    {
        $bodyCheckField = ImpetusUtils::bodyCheckFields([
            ["status", "ACTIVE", ['type(string)', 'uppercase', 'length(256)']],
            ["name", "TESTE", ['type(string)', 'uppercase', 'length(2048)']],
            ["email", "teste@gmail.com", ['type(string)', 'uppercase', 'length(1024)']],
            ["username", "teste", ['type(string)', 'uppercase', 'length(256)']],
            ["password", "#129837129ADSA", ['type(strongPassword)', 'uppercase', 'length(256)']],
            ["permission", "admin", ['type(string)', 'enum(admin|user)']],
            ["pessoaId", 1, ['type(int)']],
            ["teste", "", ['type(int)', 'nullable', 'specialChar', 'lowercase']],
            ["number", 1, ['type(number)', 'between(0-5)']],
            ["number2", 1, ['type(number)', 'between(1)']]
        ]);
        $this->assertEquals(1, $bodyCheckField['status']);
    }

    public function testBodyCheckFieldsFailed()
    {
        $bodyCheckField = ImpetusUtils::bodyCheckFields([
            ["number", 6, ['type(number)', 'between(0-5)']],
        ]);
        $this->assertEquals(0, $bodyCheckField['status']);
    }

    public function test_validator_long_text_failed()
    {
        $validator = ImpetusUtils::validator("field", "long_text", ['type(string)', 'length(1)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_nullable_failed()
    {
        $validator = ImpetusUtils::validator("field", null, ['type(string)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_min_value_failed()
    {
        $validator = ImpetusUtils::validator("field", "123", ['type(string)', 'length(5-10)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_a_date_failed()
    {
        $validator = ImpetusUtils::validator("field", "12341234", ['type(date)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_a_password_failed()
    {
        $validator = ImpetusUtils::validator("field", "12341234", ['type(password)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_an_email_failed()
    {
        $validator = ImpetusUtils::validator("field", "12341234", ['type(email)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_a_boolean_failed()
    {
        $validator = ImpetusUtils::validator("field", "not_a_boolean", ['type(boolean)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_a_valid_format_failed()
    {
        $validator = ImpetusUtils::validator("field", "what?", ['type(something)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_an_option_failed()
    {
        $validator = ImpetusUtils::validator("field", "teste3", ['type(string)','enum(teste|teste2)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_greater_than_failed()
    {
        $validator = ImpetusUtils::validator("field", 1, ['type(int)','greaterThan(2)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_greater_or_equal_than_failed()
    {
        $validator = ImpetusUtils::validator("field", 1, ['type(int)','greaterThanOrEqual(2)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_less_than_failed()
    {
        $validator = ImpetusUtils::validator("field", 3, ['type(int)','lessThan(2)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_less_or_equal_than_failed()
    {
        $validator = ImpetusUtils::validator("field", 4, ['type(int)','lessThanOrEqual(2)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_validator_is_not_between_failed()
    {
        $validator = ImpetusUtils::validator("field", 6, ['type(int)','between(1-5)']);
        $this->assertEquals(0, $validator['status']);
    }

    public function test_sanitize_var()
    {
        $sanitize = ImpetusUtils::sanitizeArray(["teste" => "<script>teste</script>"]);
        $this->assertEquals("&lt;script&gt;teste&lt;/script&gt;", $sanitize['teste']);
    }

    public function test_is_datetime()
    {
        $datetime = ImpetusUtils::datetime();
        $check = ImpetusUtils::isDateTime($datetime);
        $this->assertEquals(true, $check);
    }

    public function test_is_datetime2()
    {
        $check = ImpetusUtils::isDateTime("2024-01-01T00:00");
        $this->assertEquals(true, $check);
    }

    public function test_is_not_datetime()
    {
        $check = ImpetusUtils::isDateTime("2023-01-01");
        $this->assertEquals(false, $check);
    }

}