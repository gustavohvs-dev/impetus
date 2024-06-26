# Documentação de API - Pessoas

## Autenticação

A autenticação ocorre atráves de JSON Web Token. Faça a autenticação em "/login" e informe o bearer token no header da requisição.

## (GET) Buscar registro

#### Endpoint

```shell
/pessoas/get?id=1
```

#### Response

```json
{
"status": 1,
"code": 200,
"info": "Registro encontrado",
"data": {
    "id": "1",
    "field": "test",
    "createdAt": "2024-03-03 14:12:39",
    "updatedAt": null
    }
}
```

## (GET) Listar registros

#### Endpoint

```shell
/pessoas/list
```

#### Parâmetros de URL (opcionais)
- currentPage = (int) Define a página atual da paginação;
- dataPerPage = (int) Define a quantidade de dados a serem retornados por página. (Default: 10)

#### Response

```json
{
	"status": 1,
	"code": 200,
	"currentPage": 1,
	"numberOfPages": 1,
	"dataPerPage": 10,
	"data": [
		{
			"id": "1",
			"field": "test",
			"createdAt": "2024-03-07 11:26:16",
			"updatedAt": "2024-03-07 11:27:28"
		},
		{
			"id": "2",
			"field": "test",
			"createdAt": "2024-03-03 13:55:25",
			"updatedAt": null
		}
	]
}
```

## (POST) Criar novo registro

#### Endpoint

```shell
/pessoas/create
```
#### Body

```json
{
    "status" : "some string data",
    "tipoDocumento" : "CPF",
    "documento" : "some string data",
    "nome" : "some string data",
    "nomeFantasia" : "some string data",
    "enderecoLogradouro" : "some string data",
    "enderecoNumero" : "some string data",
    "enderecoComplemento" : "some string data",
    "enderecoCidade" : "some string data",
    "enderecoEstado" : "Acre",
    "enderecoPais" : "some string data",
    "enderecoCep" : "some string data",
    "fonte" : "WEBSITE",
    "homologado" : "HOMOLOGADO",
    "categoria" : "COMPRADORES",
    "categoriaFornecedor" : "FORNECEDOR DE INSUMOS INTERNOS"
}
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "id" : 2,
    "info": "Registro criado com sucesso",
}
```

## (PUT) Atualizar registro

#### Endpoint

```shell
/pessoas/update
```
#### Body

```json
{
    "id" : 1,
    "status" : "some string data",
    "tipoDocumento" : "CPF",
    "documento" : "some string data",
    "nome" : "some string data",
    "nomeFantasia" : "some string data",
    "enderecoLogradouro" : "some string data",
    "enderecoNumero" : "some string data",
    "enderecoComplemento" : "some string data",
    "enderecoCidade" : "some string data",
    "enderecoEstado" : "Acre",
    "enderecoPais" : "some string data",
    "enderecoCep" : "some string data",
    "fonte" : "WEBSITE",
    "homologado" : "HOMOLOGADO",
    "categoria" : "COMPRADORES",
    "categoriaFornecedor" : "FORNECEDOR DE INSUMOS INTERNOS"
}
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro atualizado com sucesso",
}
```

## (DELETE) Deletar registro

#### Endpoint

```shell
/pessoas/delete
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro deletado com sucesso",
}
```

