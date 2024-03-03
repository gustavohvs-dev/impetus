# ImpetusPHP
ImpetusPHP - Framework de Desenvolvimento de Aplicações Web

### Sobre
O ImpetusPHP é um framework que permite a criação de aplicações web no padrão MVC, proporcionando maior velocidade no processo de desenvolvimento e garantindo a qualidade e a segurança do software. O framework conta com funções que permitem a automatização da criação de web services e interfaces do usuário, além de disponibilizar funções utilitárias comumente utilizadas no dia a dia do programador.

### Guia de Instalação
Primeiramente realize a instação do PHP, MySQL/MariaDB e Composer. Após a instalação destes softwares, prossiga com o clone do framework pelo Github ou Composer.

```shell
composer require impetus/impetus
```
ou

```shell
git clone https://github.com/gustavohvs-dev/impetus
```

Após realizar o clone do projeto, execute um dos comandos abaixo para gerar os arquivos iniciais de sua aplicação web.

Caso queria construir uma aplicação completa com web service (backend) e interface de usuário (UI/Frontend), utilize a opção abaixo:

```shell
php impetus init
```
Caso queira construir apenas um web service ou microserviço, utilize a opção abaixo:

```shell
php impetus init --backend
```
Caso queria construir apenas a interface de usuário, utilize a opção abaixo:

```shell
php impetus init --frontend
```

Por fim, acesse as pastas "./build/backend" e "./build/frontend" instale as dependências do composer utilizando o comando abaixo:

```shell
composer install
```

<hr>

### Guia de Comandos (CLI)

Lista de comandos que podem ser utilizados via terminal para automatizar tarefas.

#### Migrate

O comando 'migrate' realiza a construção de tabelas e views no banco de dados, assim como gerencia mudanças e popula as tabelas com dados pré-definidos no desenvolvimento do software.

```shell
php impetus migrate --all
```
Argumentos disponíveis: --all, --tables, --views, --data.

#### Build

O comando 'build' automatiza diversas tarefas rotineiras no dia a dia do programador, leia atentamente cada uma de suas variações para saber como e quando usar.

```shell
php impetus build --arg tableName
```

Argumentos disponíveis: --all, --model, --controler, --route, --api, --view, --raw-view, --empty-view.

<hr>

### Guia de Referência de Funções Utilitárias

As funções utilitárias são funções que visam agilizar o desenvolvimento de aplicações web. Essas funções podem ser encontradas na pasta './build/backend/utils'. 

#### ImpetusFileManager

...

#### ImpetusJWT

...

#### ImpetusUtils

...

#### ImpetusMaths

...