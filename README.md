# 🛠️ Impetus.php
Impetus.php - Framework minimalista para criação de web services RESTful utilizando a linguagem PHP.

### Proposta
- Facilitar a construção de rotas, controllers e models;
- Disponibilizar comandos CLI para criação de rotas, controllers e models de forma automatizada;
- Oferecer diversas funções prontas para tratar dados, gerenciar erros, garantir a segurança e agilizar a produção de web services em PHP.
- Agilizar a implentação de autenticação com JWT Bearer em web services.

### Lista de comandos - CLI
- php impetus.php init ProjectName DatabaseName
<br> -> Cria a estrutura básica do projeto.
- php impetus.php migrate all
<br> -> Monta toda a estrutura do banco de dados.
- php impetus.php migrate tables
<br> -> Cria as tabelas no banco de dados.
- php impetus.php migrate views
<br> -> Cria as views no banco de dados.
- php impetus.php migrate populate
<br> -> Popula as tabelas com dados pré-definidos.
- php impetus.php build all TableName
<br> -> Cria toda a estrutura de model, controllers e routes com base em uma tabela.
- php impetus.php build model TableName
<br> -> Cria uma model com base em uma tabela.
- php impetus.php build controller TableName
<br> -> Cria um controller com base em uma tabela.
- php impetus.php build route TableName
<br> -> Cria as rotas com base em uma tabela.

### Quick Start

...

### Observação importante para criação de tabelas

...
