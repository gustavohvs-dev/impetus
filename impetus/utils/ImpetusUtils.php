<?php 

namespace app\models\impetus;

class ImpetusUtils
{
    /**
     * urlParams
     */
    static public function urlParams()
    {
        $urlComponents = parse_url($_SERVER['REQUEST_URI']);
        if(isset($urlComponents['query'])){
            parse_str($urlComponents['query'], $urlQuery);
            return $urlQuery;
        }else{
            return null;
        }
    }

    /**
     * token
     */
    static public function token($size = 10, $config = null)
    {
        $prefix = '';
        $uppercase = false;
        $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
        if($config != null){
            $prefix = isset($config['prefix']) ? $config['prefix'] : '';
            $uppercase = isset($config['uppercase']) ? $config['uppercase'] : false;
            if(isset($config['charType'])){
                if($config['charType'] == "numbers"){
                    $chars = '0123456789';
                }elseif($config['charType'] == "letters"){
                    $chars = 'abcdefghijklmnopqrstuvwxyz';
                }elseif($config['charType'] == "default"){
                    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
                }elseif($config['charType'] == "special"){
                    $chars = 'abcdefghijklmnopqrstuvwxyz0123456789{}[]+=@#()_';
                }
            }
        }
        $characters = $prefix . $chars;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $size; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if ($uppercase === true) {
            return strtoupper($prefix . $randomString);
        } else {
            return $prefix . $randomString;
        }
    }

    /**
     * isEmpty
     */
    static public function isEmpty($string)
    {
        $string = trim($string);
        if ($string <> null && !empty($string)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * isLongString
     */
    static public function isLongString($string, $limit)
    {
        if (strlen($string) > $limit) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * isShortString
     */
    static function isShortString($string, $limit)
    {
        if (strlen($string) < $limit) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * isNumber
     */
    static public function isNumber($number)
    {
        if (is_numeric($number)) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
    }

    /**
     * isInt
     */
    static public function isInt($number)
    {
        $isNumber = ImpetusUtils::isNumber($number);
        if($isNumber == false){
            return false;
        }
        $intNumber = (int)$number;
        if($number != $intNumber){
            return false;
        }
        return true;
    }

    /**
     * enum
     */
    static function enum($string, $list)
    {
        $found = false;
        foreach($list as $item){
            if($item == $string){
                $found = true;
            }
        }
        return $found;
    }

    /**
     * isDate
     */
    static function isDate($string)
    {
        $isDate = false;
        $data = \DateTime::createFromFormat('d/m/Y', $string);
        if($data && $data->format('d/m/Y') === $string){
           $isDate = true;
        }
        return $isDate;
    }

    /**
     * isDateTime
     */
    static function isDateTime($string)
    {
        $isDate = false;
        $data = \DateTime::createFromFormat('d/m/Y H:i:s', $string);
        if($data && $data->format('d/m/Y H:i:s') === $string){
           $isDate = true;
        }
        return $isDate;
    }

    /**
     * isEmail
     */
    static function isEmail($string)
    {
        if(filter_var($string, FILTER_VALIDATE_EMAIL)) {
            $isEmail = true;
        }else{
            $isEmail = false;
        }
        return $isEmail;
    }

    /**
     * isBoolean
     */
    static function isBoolean($string)
    {
        if($string === 1 || $string === 0 || $string === true || $string === false) {
            $isBoolean = true;
        }else{
            $isBoolean = false;
        }
        return $isBoolean;
    }

    /**
     * isPassword
     */
    static public function isPassword($string) 
    {
        $isPassword = true;

        if (strlen($string) < 8) {
            $isPassword = false;
        }
        if (!preg_match("#[0-9]+#", $string)) {
            $isPassword = false;
        }
        if (!preg_match("#[a-zA-Z]+#", $string)) {
            $isPassword = false;
        }     
    
        return $isPassword;
    }

    /**
     * isStrongPassword
     */
    static public function isStrongPassword($string) 
    {
        $isStrongPassword = true;

        if (strlen($string) < 8) {
            $isStrongPassword = false;
        }
        if (!preg_match("#[0-9]+#", $string)) {
            $isStrongPassword = false;
        }
        if (!preg_match("#[a-zA-Z]+#", $string)) {
            $isStrongPassword = false;
        }   
        if (preg_match('/^[a-zA-Z0-9]+/', $string)) {
            $isStrongPassword = false;
        }  
    
        return $isStrongPassword;
    }

    /**
     * isGreaterThan
     */
    static public function isGreaterThan($n1, $n2)
    {
        return $n1 > $n2 ? true : false;
    }

    /**
     * isGreaterThanOrEqual
     */
    static public function isGreaterThanOrEqual($n1, $n2)
    {
        return $n1 >= $n2 ? true : false;
    }

    /**
     * isLessThan
     */
    static public function isLessThan($n1, $n2)
    {
        return $n1 < $n2 ? true : false;
    }

    /**
     * isLessThanOrEqual
     */
    static public function isLessThanOrEqual($n1, $n2)
    {
        return $n1 <= $n2 ? true : false;
    }

    /**
     * isBetween
     */
    static public function isBetween($number, $low, $high)
    {
        return ($number>=$low && $number<=$high) ? true : false;
    }

    /**
     * purifyString 
     */
    static public function purifyString($string, $config = null)
    {
        $string = trim($string);
        if ($config <> null) {
        if (isset($config['accentuation'])) {
            if ($config['accentuation'] == false) {
                $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
            }
        }
        if (isset($config['specialChars'])) {
            if ($config['specialChars'] == false) {
                $string = preg_replace('/[^a-z0-9 ]/i', '', $string);
            }
        }
        if (isset($config['lowerCase'])) {
            if ($config['lowerCase'] == false) {
                $string = strtoupper($string);
            }
        }
        $string = trim($string);
        }
        return $string;
    }

    /**
     * validator
     */
    static function validator($name, $request, $config)
    {
        //config [type(number, date, int, email, boolean, datetime, password, strongPassword, string), lenght(15), enum(list), specialChar, uppercase, nullable]

        //Parâmetros
        $typeParam = false;
        $nullableParam = false;
        $lengthParam = false;
        $enumParam = false;
        $specialCharParam = false;
        $uppercaseParam = false;
        $lowercaseParam = false;
        $greaterThan = false;
        $greaterThanOrEqual = false;
        $lessThan = false;
        $lessThanOrEqual = false;
        $between = false;

        //Verificar parâmetros passados
        foreach($config as $param){
            $paramString = explode("(", $param);
            $paramName = $paramString[0];
            if(isset($paramString[1])){
                $paramString = explode(")", $paramString[1]);
                $paramValue = $paramString[0];
            }else{
                $paramValue = null;
            }
            if($paramName == "type"){
                $typeParam = true;
                $typeParamValue = $paramValue;
            }
            if($paramName == "nullable"){
                $nullableParam = true;
            }
            if($paramName == "length"){
                $lengthParam = true;
                $lengthParamValue = explode("-", $paramValue);
                if(isset($lengthParamValue[1])){
                    $lengthParamMinValue = $lengthParamValue[0];
                    $lengthParamMaxValue = $lengthParamValue[1];
                }else{
                    $lengthParamMinValue = 0;
                    $lengthParamMaxValue = $lengthParamValue[0];
                }
            }
            if($paramName == "enum"){
                $enumParam = true;
                $enumParamValue = $paramValue;
            }
            if($paramName == "specialChar"){
                $specialCharParam = true;
            }
            if($paramName == "uppercase"){
                $uppercaseParam = true;
            }
            if($paramName == "lowercase"){
                $lowercaseParam = true;
            }
            if($paramName == "greaterThan"){
                $greaterThan = true;
                $greaterThanParamValue = $paramValue;
            }
            if($paramName == "greaterThanOrEqual"){
                $greaterThanOrEqual = true;
                $greaterThanOrEqualParamValue = $paramValue;
            }
            if($paramName == "lessThan"){
                $lessThan = true;
                $lessThanParamValue = $paramValue;
            }
            if($paramName == "lessThanOrEqual"){
                $lessThanOrEqual = true;
                $lessThanOrEqualParamValue = $paramValue;
            }
            if($paramName == "between"){
                $between = true;
                $betweenParamValue = explode("-", $paramValue);
                if(isset($betweenParamValue[1])){
                    $betweenParamMinValue = $betweenParamValue[0];
                    $betweenParamMaxValue = $betweenParamValue[1];
                }else{
                    $betweenParamMinValue = $betweenParamValue[0];
                    $betweenParamMaxValue = $betweenParamValue[0];
                }
            }
        }

        //Tratar variável
        if($typeParamValue == "string"){
            $string = trim($request);
            $string = stripcslashes($string);
            $string = htmlspecialchars($string);
        }else{
            $string = $request;
        }

        //Realizar verificações
        if($nullableParam == false && ($typeParamValue != 'boolean' && $typeParamValue != 'number' && $typeParamValue != 'int')){
            $validate = ImpetusUtils::isEmpty($string);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' não pode ser vazio."
                ];
            }
        }

        if($lengthParam == true){
            $validate = ImpetusUtils::isLongString($string, $lengthParamMaxValue);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' excede quantidade máxima de caracteres (" . $lengthParamMaxValue . ")"
                ];
            }
            $validate = ImpetusUtils::isShortString($string, $lengthParamMinValue);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' não possui caracteres suficientes (" . $lengthParamMinValue . ")"
                ];
            }
        }

        if($typeParam == true){
            if($typeParamValue == "number"){
                $validate = ImpetusUtils::isNumber($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é um número válido."
                    ];
                }
            }elseif($typeParamValue == "date"){
                $validate = ImpetusUtils::isDate($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é uma data válida."
                    ];
                }
            }elseif($typeParamValue == "int"){
                $validate = ImpetusUtils::isInt($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é um número inteiro."
                    ];
                }
            }elseif($typeParamValue == "email"){
                $validate = ImpetusUtils::isEmail($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é um email válido."
                    ];
                }
            }elseif($typeParamValue == "boolean"){
                $validate = ImpetusUtils::isBoolean($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é um valor booleano."
                    ];
                }
            }elseif($typeParamValue == "datetime"){
                $validate = ImpetusUtils::isDateTime($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é um valor datetime (d/m/Y H:i:s)."
                    ];
                }
            }elseif($typeParamValue == "password"){
                $validate = ImpetusUtils::isPassword($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é uma senha segura. Deve conter mais que 8 caracteres, ao menos uma letra e um número."
                    ];
                }
            }elseif($typeParamValue == "strongPassword"){
                $validate = ImpetusUtils::isStrongPassword($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' não é uma senha segura. Deve conter mais que 8 caracteres, ao menos uma letra, um número e um caracter especial."
                    ];
                }
            }elseif($typeParamValue == "string"){
                //Do nothing
            }else{
                return [
                    "status" => 0,
                    "info" => "Tipo de variável não identificado"
                ];
            }
        }

        if($specialCharParam == false && $typeParamValue=="string"){
            $string = preg_replace(array("/(á|à|ã|â|ä)/", "/(Á|À|Ã|Â|Ä)/", "/(é|è|ê|ë)/", "/(É|È|Ê|Ë)/", "/(í|ì|î|ï)/", "/(Í|Ì|Î|Ï)/", "/(ó|ò|õ|ô|ö)/", "/(Ó|Ò|Õ|Ô|Ö)/", "/(ú|ù|û|ü)/", "/(Ú|Ù|Û|Ü)/", "/(ñ)/", "/(Ñ)/"), explode(" ", "a A e E i I o O u U n N"), $string);
            $string = preg_replace('/[^a-z0-9 ]/i', '', $string);
        }

        if($uppercaseParam == true && $typeParamValue=="string"){
            $string = strtoupper($string);
        }

        if($lowercaseParam == true && $typeParamValue=="string"){
            $string = strtolower($string);
        }

        if($enumParam == true){
            $validate = false;
            $list = explode("|", $enumParamValue);
            $itens = "";
            $separador = "";
            foreach($list as $item){
                $itens .= $separador . $item;
                $separador = "|";
                if($item == $string){
                    $validate = true;
                }
            }
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Valor '" . $name . "' não disponível nas opções (Opções disponíveis: " . $itens . ")"
                ];
            }
        }

        if($greaterThan == true && ($typeParamValue=="number" || $typeParamValue=="int")){
            $validate = ImpetusUtils::isGreaterThan($string, $greaterThanParamValue);
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' deve ser maior que (" . $greaterThanParamValue . ")."
                ];
            }
        }

        if($greaterThanOrEqual == true && ($typeParamValue=="number" || $typeParamValue=="int")){
            $validate = ImpetusUtils::isGreaterThanOrEqual($string, $greaterThanOrEqualParamValue);
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' deve ser maior ou igual a (" . $greaterThanOrEqualParamValue . ")."
                ];
            }
        }

        if($lessThan == true && ($typeParamValue=="number" || $typeParamValue=="int")){
            $validate = ImpetusUtils::isLessThan($string, $lessThanParamValue);
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' deve ser menor que (" . $lessThanParamValue . ")."
                ];
            }
        }

        if($lessThanOrEqual == true && ($typeParamValue=="number" || $typeParamValue=="int")){
            $validate = ImpetusUtils::isLessThanOrEqual($string, $lessThanOrEqualParamValue);
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' deve ser menor ou igual a (" . $lessThanOrEqualParamValue . ")."
                ];
            }
        }

        if($between == true && ($typeParamValue=="number" || $typeParamValue=="int")){
            $validate = ImpetusUtils::isBetween($string, $betweenParamMinValue, $betweenParamMaxValue);
            if($validate == false){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' deve estar entre (" . $betweenParamMinValue . ") e (" . $betweenParamMaxValue . ")."
                ];
            }
        }

        //Retornar string editada
        return [
            "status" => 1,
            "info" => "Todos as regras validadas com sucesso",
            "data" => $string
        ];

    }


    /**
     * base64UrlEncode
     */
    static public function base64UrlEncode($data)
    {
        return str_replace(['+','/','='], ['-', '_', ''], base64_encode($data));
    }
    
    /**
     * base64UrlDecode
     */
    static public function base64UrlDecode($data)
    {
        return str_replace(['-', '_', ''], ['+','/','='], base64_decode($data));
    }

    /**
     * getBearerToken
     */
    static public function getBearerToken() {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        }
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                return $matches[1];
            }
        }
        return null;
    }

    /**
     * datetime
     */
    static public function datetime($format = 'Y-m-d H:i:s', $timezone = 'America/Sao_Paulo') {
        date_default_timezone_set($timezone);
        $datetime = date($format, time());
        return $datetime;
    }

}