<?php

namespace app\models;

class Notificacoes
{
    static function getNotificacoes($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("SELECT * FROM notificacoes WHERE id = :ID");
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

    static function listNotificacoes($data, $userId)
    {
        require "../config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM notificacoes");
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
        $query = "SELECT * FROM notificacoes ";

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
        $query .= $clausule . "userId = ".$userId;
        $clausule = " AND ";

        if (isset($data["currentPage"]) && !empty($data["currentPage"]) && $data["currentPage"]>0) {
            $query .= " ORDER BY id DESC LIMIT ".($data["currentPage"]-1)*$rowsPerPage.", " . $rowsPerPage;
            $currentPage = $data["currentPage"];
        }else{
            $query .= " ORDER BY id DESC LIMIT 0, " . $rowsPerPage;
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

    static function createNotificacoes($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("INSERT INTO notificacoes (status, titulo, mensagem, cor, icone, userId) VALUES(:STATUS, :TITULO, :MENSAGEM, :COR, :ICONE, :USERID)");
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":TITULO", $data["titulo"], \PDO::PARAM_STR);
		$stmt->bindParam(":MENSAGEM", $data["mensagem"], \PDO::PARAM_STR);
		$stmt->bindParam(":COR", $data["cor"], \PDO::PARAM_STR);
		$stmt->bindParam(":ICONE", $data["icone"], \PDO::PARAM_STR);
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

    static function createNotificacoesForAllUsers($data)
    {

        require "../config.php";
        $stmt = $conn->prepare("SELECT id FROM users");
        $stmt->bindParam(":ID", $id, \PDO::PARAM_INT);
        $stmt->execute();
        $users = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach($users as $user)
        {
            $userId = $user['id'];
            $stmt = $conn->prepare("INSERT INTO notificacoes (status, titulo, mensagem, cor, icone, userId) VALUES(:STATUS, :TITULO, :MENSAGEM, :COR, :ICONE, :USERID)");
            $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
            $stmt->bindParam(":TITULO", $data["titulo"], \PDO::PARAM_STR);
            $stmt->bindParam(":MENSAGEM", $data["mensagem"], \PDO::PARAM_STR);
            $stmt->bindParam(":COR", $data["cor"], \PDO::PARAM_STR);
            $stmt->bindParam(":ICONE", $data["icone"], \PDO::PARAM_STR);
            $stmt->bindParam(":USERID", $userId, \PDO::PARAM_INT);
            $stmt->execute();
        }

        $response = [
            "status" => 1,
            "code" => 200,
            "info" => "Notificações criadas"
        ];
        return (object)$response;
    }

    static function updateNotificacoes($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE notificacoes SET status = :STATUS, titulo = :TITULO, mensagem = :MENSAGEM, cor = :COR, icone = :ICONE, userId = :USERID, updatedAt = :UPDATEDAT WHERE id = :ID");
        $stmt->bindParam(":ID", $data["id"], \PDO::PARAM_INT);
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":TITULO", $data["titulo"], \PDO::PARAM_STR);
		$stmt->bindParam(":MENSAGEM", $data["mensagem"], \PDO::PARAM_STR);
		$stmt->bindParam(":COR", $data["cor"], \PDO::PARAM_STR);
		$stmt->bindParam(":ICONE", $data["icone"], \PDO::PARAM_STR);
		$stmt->bindParam(":USERID", $data["userId"], \PDO::PARAM_INT);
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

    static function deleteNotificacoes($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE notificacoes SET status = 'INACTIVE' WHERE id = :ID");
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

    static function destroyNotificacoes($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("DELETE FROM notificacoes WHERE id = :ID");
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

    static function selectNotificacoes($queryParams)
    {
        require "../config.php";
        $searchField = 'name';
        $query = "SELECT id AS id, ".$searchField." AS text FROM notificacoes ";
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
