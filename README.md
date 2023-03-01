# 🛠️ Impetus.php
Impetus.php - Framework minimalista para criação de web services RESTful utilizando a linguagem PHP.

### Proposta
- Facilitar a construção de rotas, controllers e models;
- Disponibilizar comandos CLI para criação de rotas, controllers e models de forma automatizada;
- Oferecer diversas funções prontas para tratar dados, gerenciar erros, garantir a segurança e agilizar a produção de web services em PHP.
- Agilizar a implentação de autenticação com JWT Bearer em web services.

### Lista de comandos - CLI
- php impetus.php init ProjectName DatabaseName
- php impetus.php migrate all
- php impetus.php migrate tables
- php impetus.php migrate views
- php impetus.php migrate populate
- php impetus.php build all TableName
- php impetus.php build model TableName
- php impetus.php build controller TableName
- php impetus.php build route TableName
