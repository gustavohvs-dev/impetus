<?php 

class UtilsFWS
{
    /**
     * urlParams
     */
    public function urlParams()
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
    public function token($tamanho = 10, $id = "", $up = false)
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
    public function isEmpty($string)
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
    public function isLongString($string, $limit)
    {
        if (strlen($string) > $limit) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * isNumber
     */
    public function isNumber($number)
    {
        if (is_numeric($number)) {
        $response = true;
        } else {
        $response = false;
        }
        return $response;
    }

    /**
     * purifyString 
     */
    public function purifyString($string, $config = null)
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