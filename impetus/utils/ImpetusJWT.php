<?php 

namespace app\models\impetus;

include_once "app/models/impetus/ImpetusUtils.php";

class ImpetusJWT extends ImpetusUtils
{
    /**
     * Cria um JWT (Json Web Token)
     */
    static public function encode($sub, $name, $params, $time, $secret)
    {
        /**
         * Palavras reservadas
         * sub: ID de sessão ou do usuário
         * name: Nome do usuário
         * iat: Momento em que foi gerado o token (time())
         * exp: Momento em que o token será expirado (time() * (24 * 60 * 60)), um dia por exemplo
         * time: Parâmetro que define quantas horas o token irá durar
         * secret: Chave única utilizada para criptografar
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
     * Valida e exibe informações contidas em um JWT
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
