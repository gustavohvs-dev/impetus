# 🛠️ Impetus.php
Impetus.php - Framework minimalista para criação de web services RESTful utilizando a linguagem PHP.

### Instalação
```shell
composer require impetus/framework:dev-main
```

### Proposta
- Facilitar a construção de rotas, controllers e models, utilizando command-line interface (CLI);
- Agilizar a implentação de autenticação com Json Web Token (JWT) em web services.
- Oferecer diversas funções para tratar dados, gerenciar erros, garantir a segurança e agilizar a produção de web services em PHP.

### Lista de comandos - CLI
- <b>php impetus.php init ProjectName DatabaseName</b>
<br> -> Cria a estrutura básica do projeto.
- <b>php impetus.php migrate all</b>
<br> -> Monta toda a estrutura do banco de dados.
- <b>php impetus.php migrate tables</b>
<br> -> Cria as tabelas no banco de dados.
- <b>php impetus.php migrate views</b>
<br> -> Cria as views no banco de dados.
- <b>php impetus.php migrate data</b>
<br> -> Popula as tabelas com dados pré-definidos.
- <b>php impetus.php build all TableName</b>
<br> -> Cria toda a estrutura de model, controllers e routes com base em uma tabela.
- <b>php impetus.php build model TableName</b>
<br> -> Cria uma model com base em uma tabela.
- <b>php impetus.php build controller TableName</b>
<br> -> Cria um controller com base em uma tabela.
- <b>php impetus.php build route TableName</b>
<br> -> Cria a rota com base em uma tabela.

### Quick Start

- Utilize o comando 'php impetus.php init ProjectName DatabaseName' para criar a estrutura básica do projeto.
- Vá em seu SGDB e crie a tabela 'DatabaseName' conforme informado no comando 'init'.
- Utilize o comando 'php impetus.php migrate all' para criar a tabela de usuários e o usuário admin (username = 'admin, password = 'admin') que será utilizado na autenticação dos métodos do web service.
- Pronto! O web service já está pré-montado e pronto para ser testado.


### Testando web service

Recomendamos que utilizem alguma plataforma para teste de APIs, como por exemplo, o Postman ou o Insomnia.
Utilizando uma dessas APIs, testem os dois métodos abaixo:

#### Método 1 - LOGIN

End-point: http://localhost/impetus/login<br>
Data (Json): {"username" : "admin","password" : "admin"}

Na resposta desse método será fornecido o JWT a ser utilizado como bearer token nos demais métodos.

#### Método 2 - TEST

Agora vamos testar se o JWT está funcionando.

End-point: http://localhost/impetus/test<br>
Auth: Bearer token informado no método anterior (login)

Na resposta desse método será informado se a autenticação foi bem sucedida.

### Observação importante para criação de tabelas

É de suma importância que as tabelas possuam essas três colunas obrigatórias.

id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,<br>
... (inserir demais campos aqui) ... <br>
createdAt TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),<br>
updatedAt DATETIME<br>

A coluna 'id' servirá de chave primária em todas as tabelas, a coluna 'createdAt' registra o momento exato em que o registro foi criado, enquanto a coluna 'updatedAt' irá registrar o momento em que esse dado for alterado. 