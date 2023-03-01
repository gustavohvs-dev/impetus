<?php

function authSnippet(){

$snippet = 
'<?php

namespace app\middlewares;

class Auth
{
    static function login($user, $pass)
    {
        require "app/database/database.php";
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = :USER AND password = :PASS");
        $stmt->bindParam(":USER", $user, \PDO::PARAM_STR);
        $stmt->bindParam(":PASS", $pass, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($user != null){
            $response = [
                "status" => 1,
                "code" => 200,
                "data" => $user
            ];
        }else{
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao autenticar"
            ];
        }
        return $response;
    }

    static function validate($id, $user)
    {
        require "app/database/database.php";
        $stmt = $conn->prepare("SELECT id, username FROM users WHERE username = :USER AND id = :ID");
        $stmt->bindParam(":USER", $user, \PDO::PARAM_STR);
        $stmt->bindParam(":ID", $id, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($user != null){
            $response = [
                "status" => 1,
                "code" => 200,
                "data" => $user
            ];
        }else{
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao autenticar"
            ];
        }
        return $response;
    }

}
';

return $snippet;

}