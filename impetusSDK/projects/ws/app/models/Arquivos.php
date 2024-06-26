<?php

namespace app\models;

class Arquivos
{
    static function getArquivos($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("SELECT * FROM arquivos WHERE id = :ID");
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

    static function listArquivos($data)
    {
        require "../config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM vw_arquivos");
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
        $query = "SELECT * FROM vw_arquivos ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["id"]) && !empty($data["id"])) {
            $query .= $clausule . "id = '".$data["id"]."'";
            $clausule = " AND ";
        }
        if(isset($data["status"]) && !empty($data["status"])) {
            $query .= $clausule . "status = '".$data["status"]."'";
            $clausule = " AND ";
        }
        if(isset($data["entidade"]) && !empty($data["entidade"])) {
            $query .= $clausule . "entidade = '".$data["entidade"]."'";
            $clausule = " AND ";
        }
        if(isset($data["entidadeId"]) && !empty($data["entidadeId"])) {
            $query .= $clausule . "entidadeId = '".$data["entidadeId"]."'";
            $clausule = " AND ";
        }
        if(isset($data["tipo"]) && !empty($data["tipo"])) {
            $query .= $clausule . "tipo = '".$data["tipo"]."'";
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

    static function createArquivos($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("INSERT INTO arquivos (status, entidade, entidadeId, nome, path, usuarioId, tipo, vencimento) VALUES(:STATUS, :ENTIDADE, :ENTIDADEID, :NOME, :PATH, :USUARIOID, :TIPO, :VENCIMENTO)");
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENTIDADE", $data["entidade"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENTIDADEID", $data["entidadeId"], \PDO::PARAM_INT);
		$stmt->bindParam(":NOME", $data["nome"], \PDO::PARAM_STR);
		$stmt->bindParam(":PATH", $data["path"], \PDO::PARAM_STR);
		$stmt->bindParam(":USUARIOID", $data["usuarioId"], \PDO::PARAM_INT);
        $stmt->bindParam(":TIPO", $data["tipo"], \PDO::PARAM_STR);
        $stmt->bindParam(":VENCIMENTO", $data["vencimento"], \PDO::PARAM_STR);
		 
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

    static function updateArquivos($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE arquivos SET status = :STATUS, entidade = :ENTIDADE, entidadeId = :ENTIDADEID, nome = :NOME, path = :PATH, usuarioId = :USUARIOID, updatedAt = :UPDATEDAT WHERE id = :ID");
        $stmt->bindParam(":ID", $data["id"], \PDO::PARAM_INT);
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENTIDADE", $data["entidade"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENTIDADEID", $data["entidadeId"], \PDO::PARAM_INT);
		$stmt->bindParam(":NOME", $data["nome"], \PDO::PARAM_STR);
		$stmt->bindParam(":PATH", $data["path"], \PDO::PARAM_STR);
		$stmt->bindParam(":USUARIOID", $data["usuarioId"], \PDO::PARAM_INT);
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
                "status" => 0,
                "code" => 500,
                "info" => "Falha ao atualizar registro",
                "error" => $error
            ];
        }
        return (object)$response;
    }

    static function deleteArquivos($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE arquivos SET status = 'INACTIVE' WHERE id = :ID");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        if ($stmt->execute()) {
            if($stmt->rowCount() != 0){
                $response = [
                    "status" => 1,
                    "code" => 200,
                    "info" => "Registro inativado com sucesso",
                ];
            }else{
                $response = [
                    "status" => 0,
                    "code" => 404,
                    "info" => "Falha ao inativar registro",
                    "error" => "Not found entry (".$id.") for key (id)"
                ];
            }
        } else {
            $error = $stmt->errorInfo();
            $error = $error[2];
            $response = [
                "status" => 0,
                "code" => 404,
                "info" => "Falha ao inativar registro",
                "error" => $error
            ];  
        }
        return (object)$response;
    }

    static function inactiveArquivos($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("DELETE FROM arquivos WHERE id = :ID");
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
                    "status" => 0,
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

    static function selectArquivos($queryParams)
    {
        require "../config.php";
        $searchField = 'name';
        $query = "SELECT id AS id, ".$searchField." AS text FROM arquivos ";
        $clausule = "WHERE ";
        if (isset($queryParams[$searchField]) && !empty($queryParams[$searchField])) {
            $query .= $clausule . $searchField . " LIKE '%$queryParams[$searchField]%'";
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
