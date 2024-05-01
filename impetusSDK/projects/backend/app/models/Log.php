<?php

namespace app\models;

class Log
{
    static function getLog($id)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("SELECT * FROM vw_log WHERE id = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        if ($result <> null) {
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro encontrado",
                "data" => $result
            ];
        } else {
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Nenhum resultado encontrado"
            ];  
        }
        return (object)$response;
    }

    static function listLog($data)
    {
        require "app/config/config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM vw_log");
        $stmt->execute();
        $rowCount = $stmt->fetch(\PDO::FETCH_ASSOC);

        //Quantidade de páginas
        if (isset($data["dataPerPage"]) && !empty($data["dataPerPage"])){
            $rowsPerPage = $data["dataPerPage"];
        }else{
            $rowsPerPage = 10;
        }
        $numberOfPages = ceil($rowCount["count"]/$rowsPerPage);
        
        //Requisição
        $query = "SELECT id, tag, description, username FROM vw_log ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["id"]) && !empty($data["id"])) {
            $query .= $clausule . "id = '".$data["id"]."'";
            $clausule = " AND ";
        }
        /**if(isset($data["name"]) && !empty($data["name"])) {
            $query .= $clausule . "name LIKE '%".$data["name"]."%'";
            $clausule = " AND ";
        }*/

        if (isset($data["currentPage"]) && !empty($data["currentPage"]) && $data["currentPage"]>0) {
            $query .= " ORDER BY id LIMIT ".($data["currentPage"]-1)*$rowsPerPage.", " . $rowsPerPage;
            $currentPage = $data["currentPage"];
        }else{
            $query .= " ORDER BY id LIMIT 0, " . $rowsPerPage;
            $currentPage = 1;
        }

        $stmt = $conn->prepare($query);
        $stmt->execute();

        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($results <> null) {
            $response = [
                "status" => 1,
                "code" => 200,
                "currentPage" => (INT)$currentPage,
                "numberOfPages" => (INT)$numberOfPages,
                "dataPerPage" => (INT)$rowsPerPage,
                "data" => $results
            ];
            return $response;
        } else {
            $response = [
                "status" => 0,
                "code" => 404,
                "currentPage" => (INT)$currentPage,
                "numberOfPages" => (INT)$numberOfPages,
                "dataPerPage" => (INT)$rowsPerPage,
                "info" => "Nenhum resultado encontrado"
            ];
            return $response;
        }
    }

    static function createLog($data)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("INSERT INTO log (tag, endpoint, method, request, response, description, userId) VALUES(:TAG, :ENDPOINT, :METHOD, :REQUEST, :RESPONSE, :DESCRIPTION, :USERID)");
        $stmt->bindParam(":TAG", $data["tag"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDPOINT", $data["endpoint"], \PDO::PARAM_STR);
		$stmt->bindParam(":METHOD", $data["method"], \PDO::PARAM_STR);
		$stmt->bindParam(":REQUEST", $data["request"], \PDO::PARAM_STR);
		$stmt->bindParam(":RESPONSE", $data["response"], \PDO::PARAM_STR);
		$stmt->bindParam(":DESCRIPTION", $data["description"], \PDO::PARAM_STR);
		$stmt->bindParam(":USERID", $data["userId"], \PDO::PARAM_INT);
		 
        if ($stmt->execute()) {
            $response = [
                "status" => 1,
                "code" => 200,
                "id" => (int)$conn->lastInsertId(),
                "info" => "Registro criado com sucesso"
            ];
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao criar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

}
