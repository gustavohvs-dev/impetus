# Documentação de API - Arquivos

## Autenticação

A autenticação ocorre atráves de JSON Web Token. Faça a autenticação em "/login" e informe o bearer token no header da requisição.

## (GET) Buscar registro

#### Endpoint

```shell
/arquivos/get?id=1
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
/arquivos/list
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
/arquivos/create
```
#### Body

```json
{
    "status" : "some string data",
    "entidade" : "some string data",
    "entidadeId" : "23",
    "nome" : "some string data",
    "path" : "some string data",
    "usuarioId" : "23"
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
/arquivos/update
```
#### Body

```json
{
    "id" : 1,
    "status" : "some string data",
    "entidade" : "some string data",
    "entidadeId" : "23",
    "nome" : "some string data",
    "path" : "some string data",
    "usuarioId" : "23"
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
/arquivos/delete
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro deletado com sucesso",
}
```

