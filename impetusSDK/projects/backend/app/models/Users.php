<?php

namespace app\models;

class Users
{
    static function getUsers($id)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("SELECT * FROM vw_users WHERE id = :ID");
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

    static function listUsers($data)
    {
        require "app/config/config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM vw_users");
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
        $query = "SELECT * FROM vw_users ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["status"]) && !empty($data["status"])) {
            $query .= $clausule . "status = '".$data["status"]."'";
            $clausule = " AND ";
        }
        if(isset($data["name"]) && !empty($data["name"])) {
            $query .= $clausule . "name LIKE '%".$data["name"]."%'";
            $clausule = " AND ";
        }
        if(isset($data["companyId"]) && !empty($data["companyId"])) {
            $query .= $clausule . "companyId = ". $data["companyId"];
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
            return $response;
        }
    }

    static function createUsers($data)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("INSERT INTO users (status, name, email, username, password, permission, isConfirmedEmail, companyId) VALUES(:STATUS, :NAME, :EMAIL, :USERNAME, :PASSWORD, :PERMISSION, :ISCONFIRMEDEMAIL, :COMPANYID)");
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":NAME", $data["name"], \PDO::PARAM_STR);
		$stmt->bindParam(":EMAIL", $data["email"], \PDO::PARAM_STR);
		$stmt->bindParam(":USERNAME", $data["username"], \PDO::PARAM_STR);
		$stmt->bindParam(":PASSWORD", $data["password"], \PDO::PARAM_STR);
		$stmt->bindParam(":PERMISSION", $data["permission"], \PDO::PARAM_STR);
		$stmt->bindParam(":ISCONFIRMEDEMAIL", $data["isConfirmedEmail"], \PDO::PARAM_STR);
        $stmt->bindParam(":COMPANYID", $data["companyId"], \PDO::PARAM_INT);
		 
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

    static function updateUsers($data)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("UPDATE users SET status = :STATUS, name = :NAME, email = :EMAIL, username = :USERNAME, password = :PASSWORD, permission = :PERMISSION, isConfirmedEmail = :ISCONFIRMEDEMAIL, updatedAt = :UPDATEDAT, companyId = :COMPANYID WHERE id = :ID");
        $stmt->bindParam(":ID", $data["id"], \PDO::PARAM_INT);
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":NAME", $data["name"], \PDO::PARAM_STR);
		$stmt->bindParam(":EMAIL", $data["email"], \PDO::PARAM_STR);
		$stmt->bindParam(":USERNAME", $data["username"], \PDO::PARAM_STR);
		$stmt->bindParam(":PASSWORD", $data["password"], \PDO::PARAM_STR);
		$stmt->bindParam(":PERMISSION", $data["permission"], \PDO::PARAM_STR);
		$stmt->bindParam(":ISCONFIRMEDEMAIL", $data["isConfirmedEmail"], \PDO::PARAM_STR);
		$stmt->bindParam(":UPDATEDAT", $data["updatedAt"], \PDO::PARAM_STR);
        $stmt->bindParam(":COMPANYID", $data["companyId"], \PDO::PARAM_INT);
		
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

    static function deleteUsers($id)
    {
        require "app/config/config.php";
        $stmt = $conn->prepare("DELETE FROM users WHERE id = :ID");
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

}
