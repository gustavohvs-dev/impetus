<?php

namespace app\controllers;

include_once "app/middlewares/Auth.php";
use app\middlewares\Auth;

//Caso já possua uma sessão iniciada, o usuário é direcionado diretamente para tela de login
if(!isset($_SESSION)){
    session_start();
}
if (isset($_SESSION['userId']) && isset($_SESSION['sessionToken'])) {
    header('Location: index');
}

$loginAlert = '<div class="alert alert-info mt-3" role="alert">Digite o seu usuário e senha para acessar o sistema.</div>';

if(isset($_POST['username']) && isset($_POST['password'])){
    $responseAuth = Auth::auth($_POST['username'], $_POST['password']);
    if($responseAuth['status']==0){
        header("Location: login?error=Auth-Failed");
    }else{
        header("Location: index");
    }
}