<?php

namespace app\middlewares;

class Auth{

    public static function auth($user, $pass){

      require "app/config/config.php";

      //Caso não tenha sessão iniciada, iniciar sessão
      if(!isset($_SESSION)){
        session_start();
      }

      //Body Json
      $body = [
        "username" => $user,
        "password" => $pass,
      ];
      $bodyJson = json_encode($body);

      //Request de login
      $curl = curl_init();
      curl_setopt_array($curl, [
          CURLOPT_URL => $systemConfig['endPoint'] . "login",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POST => true,
          CURLOPT_POSTFIELDS => $bodyJson,
          CURLOPT_HTTPHEADER => ['Content-Type: application/json']                                                                  
        ]
      );

      $response = curl_exec($curl);
      $err = curl_error($curl);

      curl_close($curl);

      if ($err) {
        //Erro ao comunicar com servidor
        $response = [
          "status" => 0,
          "info" => "Falha ao comunicar com o servidor."
        ];
        return $response;
      } else {
        $response = json_decode($response);
        if($response->status==0){
          //Erro ao autenticar
          $response = [
            "status" => 0,
            "info" => "Usuário ou senha incorretos, por favor verifique as informações digitadas."
          ];
          return $response;
        }else{
          //Sucesso
          $_SESSION['sessionToken'] = $response->token;
          $response = [
            "status" => 1,
            "info" => "Login realizado com sucesso!"
          ];
          return $response;
        }
      }

    }

    public static function validateSession(array $permissionList, bool $destroySession = true){

      require "app/config/config.php";

      //Caso não tenha sessão iniciada, iniciar sessão
      if(!isset($_SESSION)){
        session_start();
      }

      //Caso não tenha o userId e o sessionToken direciona o usuário para tela de login novamente
      if (!isset($_SESSION['sessionToken'])) {
        if($destroySession == true){
          Auth::destroySession();
        }
      }else{
        //Buscando informações do usuário
        $curl = curl_init();
        curl_setopt_array($curl, [
          CURLOPT_URL => $systemConfig['endPoint'] . "validate",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_HTTPHEADER => ["Content-Type: application/json", "Authorization: Bearer " . $_SESSION['sessionToken']]                                                                  
        ]
        );
        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);

        if ($err && $destroySession == true) {
          //Erro ao comunicar com servidor
          Auth::destroySession();
        } else {
          
          $response = json_decode($response);
          if($response->status==0 && $destroySession == true){
            //Erro ao autenticar
            Auth::destroySession();
          }else{
            //Verificar se o usuário pode acessar a página verificando a $permissionList passada
            $allowAcess = false;
            foreach($permissionList as $permissionVerify){
              if($permissionVerify == $response->data->permission){
                $allowAcess = true;
              }
            }
            if($allowAcess == false){
              if($destroySession == true){
                Auth::destroySession();
              }else{
                return null;
              }
            }else{
              $userData = $response->data;
              return $userData;
            }
          }
        }
      }

    }

    public static function destroySession(){
      session_destroy();
      session_start();
      session_regenerate_id();
      session_unset();
      header('Location: login');
    }

}