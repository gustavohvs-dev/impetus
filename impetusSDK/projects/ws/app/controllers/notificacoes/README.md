# Documentação de API - Notificacoes

## Autenticação

A autenticação ocorre atráves de JSON Web Token. Faça a autenticação em "/login" e informe o bearer token no header da requisição.

## (GET) Buscar registro

#### Endpoint

```shell
/notificacoes/get?id=1
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
/notificacoes/list
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
/notificacoes/create
```
#### Body

```json
{
    "status" : "PENDENTE",
    "titulo" : "some string data",
    "mensagem" : "some string data",
    "cor" : "some string data",
    "icone" : "some string data",
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

## (PUT) Atualizar registro

#### Endpoint

```shell
/notificacoes/update
```
#### Body

```json
{
    "id" : 1,
    "status" : "PENDENTE",
    "titulo" : "some string data",
    "mensagem" : "some string data",
    "cor" : "some string data",
    "icone" : "some string data",
    "userId" : "23"
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
/notificacoes/delete
```

#### Response

```json
{
    "status": 1,
    "code": 200,
    "info": "Registro deletado com sucesso",
}
```

