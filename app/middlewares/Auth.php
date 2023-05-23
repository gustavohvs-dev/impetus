<?php

namespace app\middlewares;

class Auth
{
    static function login($user, $pass)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :USER");
        $stmt->bindParam(":USER", $user, \PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($user != null){
            if(password_verify($pass, $user["password"])){
                $response = [
                    "status" => 1,
                    "code" => 200,
                    "data" => [
                        "id" => $user["id"],
                        "username" => $user["username"]
                    ]
                ];
            }else{
                $response = [
                    "status" => 0,
                    "code" => 500,
                    "info" => "Falha ao autenticar",
                ];
            }
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2]==null ? "Mysql returned *null* as response, probably the subject was not found" : $error[2];
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao autenticar",
                "error" => $error,
            ];
        }
        return (object)$response;
    }

    static function validate($id, $user)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("SELECT id, username, permission FROM users WHERE username = :USER AND id = :ID");
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
        return (object)$response;
    }

}

