<?php

namespace app\models;

class Pessoas
{
    static function getPessoas($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("SELECT * FROM pessoas WHERE id = :ID");
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

    static function listPessoas($data)
    {
        require "../config.php";

        //Quantidade de dados
        $stmt = $conn->prepare("SELECT COUNT(id) count FROM pessoas");
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
        $query = "SELECT * FROM pessoas ";

        //Filtros
        $clausule = "WHERE ";
        if(isset($data["id"]) && !empty($data["id"])) {
            $query .= $clausule . "id = '".$data["id"]."'";
            $clausule = " AND ";
        }
        if(isset($data["nome"]) && !empty($data["nome"])) {
            $query .= $clausule . "nome LIKE '%".$data["nome"]."%'";
            $clausule = " AND ";
        }
        if(isset($data["categoria"]) && !empty($data["categoria"])) {
            $query .= $clausule . "categoria LIKE '%".$data["categoria"]."%'";
            $clausule = " AND ";
        }
        if(isset($data["categoriaFornecedor"]) && !empty($data["categoriaFornecedor"])) {
            $query .= $clausule . "categoriaFornecedor LIKE '%".$data["categoriaFornecedor"]."%'";
            $clausule = " AND ";
        }
        if(isset($data["tipoDocumento"]) && !empty($data["tipoDocumento"])) {
            $query .= $clausule . "tipoDocumento LIKE '%".$data["tipoDocumento"]."%'";
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

    static function createPessoas($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("INSERT INTO pessoas (status, tipoDocumento, documento, nome, nomeFantasia, enderecoLogradouro, enderecoNumero, enderecoComplemento, enderecoCidade, enderecoEstado, enderecoPais, enderecoCep) VALUES(:STATUS, :TIPODOCUMENTO, :DOCUMENTO, :NOME, :NOMEFANTASIA, :ENDERECOLOGRADOURO, :ENDERECONUMERO, :ENDERECOCOMPLEMENTO, :ENDERECOCIDADE, :ENDERECOESTADO, :ENDERECOPAIS, :ENDERECOCEP)");
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":TIPODOCUMENTO", $data["tipoDocumento"], \PDO::PARAM_STR);
		$stmt->bindParam(":DOCUMENTO", $data["documento"], \PDO::PARAM_STR);
		$stmt->bindParam(":NOME", $data["nome"], \PDO::PARAM_STR);
		$stmt->bindParam(":NOMEFANTASIA", $data["nomeFantasia"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOLOGRADOURO", $data["enderecoLogradouro"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECONUMERO", $data["enderecoNumero"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCOMPLEMENTO", $data["enderecoComplemento"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCIDADE", $data["enderecoCidade"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOESTADO", $data["enderecoEstado"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOPAIS", $data["enderecoPais"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCEP", $data["enderecoCep"], \PDO::PARAM_STR);
		 
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

    static function updatePessoas($data)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE pessoas SET status = :STATUS, tipoDocumento = :TIPODOCUMENTO, documento = :DOCUMENTO, nome = :NOME, nomeFantasia = :NOMEFANTASIA, enderecoLogradouro = :ENDERECOLOGRADOURO, enderecoNumero = :ENDERECONUMERO, enderecoComplemento = :ENDERECOCOMPLEMENTO, enderecoCidade = :ENDERECOCIDADE, enderecoEstado = :ENDERECOESTADO, enderecoPais = :ENDERECOPAIS, enderecoCep = :ENDERECOCEP, enderecoBairro = :enderecoBairro, updatedAt = :UPDATEDAT WHERE id = :ID");
        $stmt->bindParam(":ID", $data["id"], \PDO::PARAM_INT);
        $stmt->bindParam(":STATUS", $data["status"], \PDO::PARAM_STR);
		$stmt->bindParam(":TIPODOCUMENTO", $data["tipoDocumento"], \PDO::PARAM_STR);
		$stmt->bindParam(":DOCUMENTO", $data["documento"], \PDO::PARAM_STR);
		$stmt->bindParam(":NOME", $data["nome"], \PDO::PARAM_STR);
		$stmt->bindParam(":NOMEFANTASIA", $data["nomeFantasia"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOLOGRADOURO", $data["enderecoLogradouro"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECONUMERO", $data["enderecoNumero"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCOMPLEMENTO", $data["enderecoComplemento"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCIDADE", $data["enderecoCidade"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOESTADO", $data["enderecoEstado"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOPAIS", $data["enderecoPais"], \PDO::PARAM_STR);
		$stmt->bindParam(":ENDERECOCEP", $data["enderecoCep"], \PDO::PARAM_STR);
        $stmt->bindParam(":enderecoBairro", $data["enderecoBairro"], \PDO::PARAM_STR);
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

    static function deletePessoas($id)
    {
        require "../config.php";
        $stmt = $conn->prepare("UPDATE pessoas SET status = 'INACTIVE' WHERE id = :ID");
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

    static function selectPessoas($queryParams)
    {
        require "../config.php";
        $searchField = 'nome';
        $query = "SELECT id AS id, ".$searchField." AS text FROM pessoas ";
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
