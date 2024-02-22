<?php

namespace app\models;

class Companies
{
    static function getCompanies($id)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("SELECT * FROM companies WHERE id = :ID");
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

    static function listCompanies($data)
    {
        require "app/config/config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM companies");
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
        $query = "SELECT * FROM companies ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["status"]) && !empty($data["status"])) {
            $query .= $clausule . "status = '".$data["status"]."'";
            $clausule = " AND ";
        }
        if(isset($data["corporateName"]) && !empty($data["corporateName"])) {
            $query .= $clausule . "corporateName LIKE '%".$data["corporateName"]."%'";
            $clausule = " AND ";
        }

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
            return (object)$response;
        }
    }

    static function createCompanies($data)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("INSERT INTO companies (status, corporateName, name, document) VALUES(:STATUS, :CORPORATENAME, :NAME, :DOCUMENT)");
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":CORPORATENAME", $data["corporateName"], \PDO::PARAM_STR);
		$stmt->bindParam(":NAME", $data["name"], \PDO::PARAM_STR);
		$stmt->bindParam(":DOCUMENT", $data["document"], \PDO::PARAM_STR);
		 
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
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao criar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function updateCompanies($data)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("UPDATE companies SET status = :STATUS, corporateName = :CORPORATENAME, name = :NAME, document = :DOCUMENT, updatedAt = :UPDATEDAT WHERE id = :ID");
        $stmt->bindParam(":ID", $data["id"], \PDO::PARAM_INT);
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":CORPORATENAME", $data["corporateName"], \PDO::PARAM_STR);
		$stmt->bindParam(":NAME", $data["name"], \PDO::PARAM_STR);
		$stmt->bindParam(":DOCUMENT", $data["document"], \PDO::PARAM_STR);
		$stmt->bindParam(":UPDATEDAT", $data["updatedAt"], \PDO::PARAM_STR);
		
        if ($stmt->execute()) {
            $response = [
                "status" => 1,
                "code" => 200,
                "info" => "Registro atualizado com sucesso"
            ];
        }else{
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 1,
                "code" => 500,
                "info" => "Falha ao atualizar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function deleteCompanies($id)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("DELETE FROM companies WHERE id = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        if ($stmt->execute()) {
            if($stmt->rowCount() != 0){
                $response = [
                    "status" => 1,
                    "code" => 200,
                    "info" => "Registro deletado com sucesso",
                ];
            }else{
                $response = [
                    "status" => 1,
                    "code" => 404,
                    "info" => "Falha ao deletar registro",
                    "error" => "Not found entry (".$id.") for key (id)"
                ];
            }
        } else {
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Falha ao deletar registro",
                "error" => $error
            ];  
        }
        return (object)$response;
    }

    static function selectCompanies($queryParams)
    {
        require "app/config/config.php";
        $query = "SELECT id AS id, corporateName AS text FROM companies ";
        $clausule = "WHERE ";
        if (isset($queryParams['status']) && !empty($queryParams['status'])) {
            $query .= $clausule . "status = '$queryParams[status]'";
            $clausule = " AND ";
        }
        if (isset($queryParams['corporateName']) && !empty($queryParams['corporateName'])) {
            $query .= $clausule . "corporateName LIKE '%$queryParams[corporateName]%'";
            $clausule = " AND ";
        }
        $stmt = $conn->prepare($query);
        $stmt->execute();

        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        if ($data <> null) {
            return $data;
        } else {
            $response = [
                "status" => 0,
                "error" => "Nenhum dado encontrado!"
            ];
            return $response;
        }
    }

}
