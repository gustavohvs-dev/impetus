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
    static public function token($tamanho = 10, $id = "", $up = false)
    {
        $characters = $id . 'abcdefghijklmnopqrstuvwxyz0123456789';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $tamanho; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        if ($up === true) {
        return strtoupper($id . $randomString);
        } else {
        return $id . $randomString;
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
        if (is_int($number)) {
            $response = true;
        } else {
            $response = false;
        }
        return $response;
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
     * isSecurePassword
     */
    static public function isSecurePassword($string) 
    {
        $isSecurePassword = true;

        if (strlen($string) < 8) {
            $isSecurePassword = false;
        }
        if (!preg_match("#[0-9]+#", $string)) {
            $isSecurePassword = false;
        }
        if (!preg_match("#[a-zA-Z]+#", $string)) {
            $isSecurePassword = false;
        }     
    
        return $isSecurePassword;
    }

    /**
     * isStrongPassword
     */
    static public function isStrongPassword($string) 
    {
        $isSecurePassword = true;

        if (strlen($string) < 8) {
            $isSecurePassword = false;
        }
        if (!preg_match("#[0-9]+#", $string)) {
            $isSecurePassword = false;
        }
        if (!preg_match("#[a-zA-Z]+#", $string)) {
            $isSecurePassword = false;
        }   
        if (preg_match('/^[a-zA-Z0-9]+/', $string)) {
            $isSecurePassword = false;
        }  
    
        return $isSecurePassword;
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
            $string = preg_replace(array("/(??|??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??)/", "/(??)/"), explode(" ", "a A e E i I o O u U n N"), $string);
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
        //config [type(date), lenght(15), enum(list), specialChar, uppercase, nullable]

        //Par??metros
        $typeParam = false;
        $nullableParam = false;
        $lengthParam = false;
        $enumParam = false;
        $specialCharParam = false;
        $uppercaseParam = false;
        $lowercaseParam = false;

        //Verificar par??metros passados
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
        }

        //Tratar vari??vel
        if($typeParamValue == "string"){
            $string = trim($request);
            $string = stripcslashes($string);
            $string = htmlspecialchars($string);
        }else{
            $string = $request;
        }

        //Realizar verifica????es
        if($nullableParam == false && ($typeParamValue != 'boolean' && $typeParamValue != 'number' && $typeParamValue != 'int')){
            $validate = ImpetusUtils::isEmpty($string);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' n??o pode ser vazio."
                ];
            }
        }

        if($lengthParam == true){
            $validate = ImpetusUtils::isLongString($string, $lengthParamMaxValue);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' excede quantidade m??xima de caracteres (" . $lengthParamMaxValue . ")"
                ];
            }
            $validate = ImpetusUtils::isShortString($string, $lengthParamMinValue);
            if($validate == true){
                return [
                    "status" => 0,
                    "info" => "Campo '" . $name . "' n??o possui caracteres suficientes (" . $lengthParamMinValue . ")"
                ];
            }
        }

        if($typeParam == true){
            if($typeParamValue == "number"){
                $validate = ImpetusUtils::isNumber($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? um n??mero v??lido."
                    ];
                }
            }elseif($typeParamValue == "date"){
                $validate = ImpetusUtils::isDate($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? uma data v??lida."
                    ];
                }
            }elseif($typeParamValue == "int"){
                $validate = ImpetusUtils::isInt($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? um n??mero inteiro."
                    ];
                }
            }elseif($typeParamValue == "email"){
                $validate = ImpetusUtils::isEmail($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? um email v??lido."
                    ];
                }
            }elseif($typeParamValue == "boolean"){
                $validate = ImpetusUtils::isBoolean($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? um valor booleano."
                    ];
                }
            }elseif($typeParamValue == "datetime"){
                $validate = ImpetusUtils::isDateTime($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? um valor datetime (d/m/Y H:i:s)."
                    ];
                }
            }elseif($typeParamValue == "password"){
                $validate = ImpetusUtils::isSecurePassword($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? uma senha segura. Deve conter mais que 8 caracteres, ao menos uma letra e um n??mero."
                    ];
                }
            }elseif($typeParamValue == "strongPassword"){
                $validate = ImpetusUtils::isStrongPassword($string);
                if($validate == false){
                    return [
                        "status" => 0,
                        "info" => "Campo '" . $name . "' n??o ?? uma senha segura. Deve conter mais que 8 caracteres, ao menos uma letra, um n??mero e um caracter especial."
                    ];
                }
            }elseif($typeParamValue == "string"){
                //Do nothing
            }else{
                return [
                    "status" => 0,
                    "info" => "Tipo de vari??vel n??o identificado"
                ];
            }
        }

        if($specialCharParam == false && $typeParamValue=="string"){
            $string = preg_replace(array("/(??|??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??|??)/", "/(??|??|??|??)/", "/(??|??|??|??)/", "/(??)/", "/(??)/"), explode(" ", "a A e E i I o O u U n N"), $string);
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
                    "info" => "Valor '" . $name . "' n??o dispon??vel nas op????es (Op????es dispon??veis: " . $itens . ")"
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

}

//$validate = ImpetusUtils::validator("var", "@Ab123456", ['type(strongPassword)']);
//var_dump($validate);