# Documentação de API - Log

## Autenticação

A autenticação ocorre atráves de JSON Web Token. Faça a autenticação em "/login" e informe o bearer token no header da requisição.

## (GET) Buscar registro

#### Endpoint

```shell
/log/get?id=1
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
/log/list
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
/log/create
```
#### Body

```json
{
    "tag" : "some string data",
    "endpoint" : "lorem ipsum dolor sit amet consectetur adipiscing elit",
    "method" : "some string data",
    "request" : "lorem ipsum dolor sit amet consectetur adipiscing elit",
    "response" : "lorem ipsum dolor sit amet consectetur adipiscing elit",
    "description" : "lorem ipsum dolor sit amet consectetur adipiscing elit",
    "userId" : "23"
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

