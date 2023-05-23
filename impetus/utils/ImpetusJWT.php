<?php 

namespace app\models\impetus;

include_once "app/models/impetus/ImpetusUtils.php";

class ImpetusJWT extends ImpetusUtils
{
    /**
     * Creates a JWT (Json Web Token)
     */
    static public function encode($sub, $name, $params, $time, $secret)
    {
        /**
         * Reserved words
         * sub: Session or user ID
         * name: User's name
         * iat: Token generation time (time())
         * exp: Token expiration time (time() * (24 * 60 * 60)), for example, one day
         * time: Parameter that defines how many hours the token will last
         * secret: Unique key used for encryption
         */
        $data = "";
        $operator = "";
        foreach ($params as $key => $value) {
            $data .= $operator.'"'.$key.'": "'.$value.'"';
            $operator = ",";
        }
        $exp = time() + ($time * 60 * 60);
        $header = ImpetusJWT::base64UrlEncode('{"alg": "HS256", "typ": "JWT"}');
        $payload = ImpetusJWT::base64UrlEncode('{"sub": "'.$sub.'", "name": "'.$name.'", "iat": '.time().', "exp": '.$exp.','.$data.'}');
        $signature = ImpetusJWT::base64UrlEncode(
            hash_hmac('sha256', $header.'.'.$payload, $secret, true)
        );
        return $header.'.'.$payload.'.'.$signature;
    }

    /**
     * Validates and displays information contained in a JWT (JSON Web Token).
     */
    static public function decode($token, $secret)
    {
        $data = explode(".", $token);
        $header = $data[0];
        $payload = $data[1];
        $signature = $data[2];
        $validateSignature = ImpetusJWT::base64UrlEncode(
            hash_hmac('sha256', $header.'.'.$payload, $secret, true)
        );
        if($signature == $validateSignature){
            $payload = json_decode(ImpetusJWT::base64Urldecode($payload));
            if($payload->exp < time()){
                $response = [
                    "status" => 0,
                    "error" => "JWT expirado!"
                ];
            }else{
                $response = [
                    "status" => 1,
                    "header" => json_decode(ImpetusJWT::base64Urldecode($header)),
                    "payload" => $payload
                ];
            }
        }else{
            $response = [
                "status" => 0,
                "error" => "Falha ao autenticar assinatura"
            ];
        }
        return (object)$response;
    }

}
