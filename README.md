# 🛠️ Impetus.php
Impetus.php - Framework minimalista para criação de web services RESTful utilizando a linguagem PHP.

### Proposta
- Facilitar a construção de rotas, controllers e models, utilizando command-line interface (CLI);
- Agilizar a implentação de autenticação com Json Web Token (JWT) em web services.
- Oferecer diversas funções para tratar dados, gerenciar erros, garantir a segurança e agilizar a produção de web services em PHP.

### Instalação
Após a instalação do composer, execute os seguintes comandos:

```shell
composer create-project impetus/framework appName
```
```shell
composer install
```

### Lista de comandos - CLI
#### Migrate
```shell
php impetusy migrate --all
```
Monta toda a estrutura do banco de dados.<br>
Opções disponíveis: --all, --tables, --views, --data.

#### Build
```shell
php impetusy build tableName --all
```
Cria toda a estrutura de model, controllers e routes com base em uma tabela.<br>
Opções disponíveis: --all, --model, --controler, --route.