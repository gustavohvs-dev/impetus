<?php

namespace app\routes;

class Router
{
	public static function ImpetusRouter($routes){
		if (isset($_GET["url"])) {
			Router::ImpetusRoute($_GET["url"], $routes);
		}else{
			Router::get("app/views/index/index.php");
		}
	}

	protected static function ImpetusRoute($uri, $routes){
		$validated = false;
		foreach($routes as $targetUri => $targetPath) {
			if($uri == $targetUri) {
				$validated = true;
				$targetPath();
			}
		}
		if($validated == false){
			Router::get("app/views/errors/404.php");
		}
	}

	protected static function verifyPath($path){
		if(file_exists($path)){
			require_once $path;
		}else{
			Router::get("app/views/errors/404.php");
		}
	}

	public static function get($path){
		if($_SERVER["REQUEST_METHOD"] != "GET"){
			$response = [
				"status" => 0,
				"code" => 404,
				"info" => "Método não encontrado",
			];
			header("HTTP/1.1 404 Not Found");
			header("Content-Type: application/json");
			echo json_encode($response);
		}else{
			Router::verifyPath($path);
		}
	}

	public static function post($path){
		if($_SERVER["REQUEST_METHOD"] != "POST"){
			$response = [
				"status" => 0,
				"code" => 404,
				"info" => "Método não encontrado",
			];
			header("HTTP/1.1 404 Not Found");
			header("Content-Type: application/json");
			echo json_encode($response);
		}else{
			if(file_exists($path)){
				require_once $path;
			}else{
				Router::verifyPath($path);
			}
		}
	}

	public static function put($path){
		if($_SERVER["REQUEST_METHOD"] != "PUT"){
			$response = [
				"status" => 0,
				"code" => 404,
				"info" => "Método não encontrado",
			];
			header("HTTP/1.1 404 Not Found");
			header("Content-Type: application/json");
			echo json_encode($response);
		}else{
			if(file_exists($path)){
				require_once $path;
			}else{
				Router::verifyPath($path);
			}
		}
	}

	public static function patch($path){
		if($_SERVER["REQUEST_METHOD"] != "PATCH"){
			$response = [
				"status" => 0,
				"code" => 404,
				"info" => "Método não encontrado",
			];
			header("HTTP/1.1 404 Not Found");
			header("Content-Type: application/json");
			echo json_encode($response);
		}else{
			Router::verifyPath($path);
		}
	}

	public static function delete($path){
		if($_SERVER["REQUEST_METHOD"] != "DELETE"){
			$response = [
				"status" => 0,
				"code" => 404,
				"info" => "Método não encontrado",
			];
			header("HTTP/1.1 404 Not Found");
			header("Content-Type: application/json");
			echo json_encode($response);
		}else{
			Router::verifyPath($path);
		}
	}
}