# ImpetusPHP
ImpetusPHP - Framework de Desenvolvimento de Aplicações Web

### Sobre
O ImpetusPHP é um framework que permite a criação de aplicações web na arquitetura MVC, proporcionando maior velocidade no processo de desenvolvimento e garantindo a qualidade e a segurança do software. O framework conta com funções que permitem a automatização da criação de web services e interfaces do usuário, além de disponibilizar funções utilitárias comumente utilizadas no dia a dia do programador.

### Guia de Instalação

#### Dependências
Antes de realizar a instação do framework, é necessário instalar a seguintes dependências:

- Apache 2.4^;
- PHP 7.^;
- MariaDB 10.4.27^;
- Composer 2.6.6^;

Para facilitar a instalação das dependências, recomendamos a instalação do Xampp 7.^. O XAMPP é um pacote com os principais servidores de código aberto do mercado, nele já está incluído o Apache, MariaDB e o PHP. Para fazer o download clique no link abaixo:

https://sourceforge.net/projects/xampp/files/

Por fim faça o download do gerenciador de dependências Composer utilizando o link abaixo:

https://getcomposer.org/

#### Instalação do framework
Com todas as dependências instaladas, realize o clone do projeto.

Observação: Caso esteja usando o Xampp, faça o clone do projeto em 'xampp/htdocs'. Esta é a pasta raiz de todos os projetos no Xampp.

```shell
git clone https://github.com/gustavohvs-dev/impetus
```

Após realizar o clone do projeto, execute o comando abaixo para gerar os arquivos iniciais de sua aplicação web. O comando irá gerar um web service (backend) e uma interface de usuário (UI/frontend).

```shell
php impetus init
```

Após rodar o comando init, crie um banco de dados para a aplicação e configure o arquivo config.php (situado em ./build/backend/config) utilizando as credenciais e caminho de acesso ao banco de dados. Segue abaixo um exemplo dos campos que você irá alterar do seu arquivo config.php:

```shell
"database" => [
        "type" => "mariadb",
        "server" => "localhost",
        "username" => "usuarioDoDatabase",
        "password" => "senhaDoDatabase",
        "database" => "nomeDoDatabase"
        ],
```

Observação: Caso esteja utilizando o Xampp, abra o painel de controle do Xampp (localizado em xampp/xampp-control.exe) e inicie o módulo Apache e MySQL. Feito isso, abra seu navegador e acesse http://localhost/phpMyAdmin, será aberto o phpMyAdmin, uma interface gráfica para a criação e gerenciamento de bancos de dados.

Feito a configuração do banco de dados execute o comando abaixo para criar as tabelas do sistema.

```shell
php impetus migrate --up
```

Após esses passos sua aplicação já estará disponível para utilização. Caso esteja utilizando o Xampp, acesse no seu navegador http://localhost/impetus/frontend e faça login no sistema utilizando o usuário padrão.

#### Credenciais do usuário padrão

Usuário: admin <br>
Senha: admin

<hr>

### Guia de Comandos (CLI)

Lista de comandos que podem ser utilizados via terminal para automatizar tarefas.

#### Init

O comando 'init' inicia uma nova aplicação web com todos os recursos e funcionalidades que framework oferece.

```shell
php impetus init --arg
```
Argumentos disponíveis: --backend, --frontend.

- --backend: Cria apenas o web service do projeto, recomendado para projetos onde não será necessário uma interface de usuário.
- --frontend: Cria apenas a interface gráfica da aplicação, recomendado para projetos que irão se conectar a um web service externo.

Caso não seja informado nenhum argumento, o framework irá criar tanto o backend quanto o frontend da aplicação.

#### Migrate

O comando 'migrate' realiza a construção de tabelas e views no banco de dados, assim como gerencia mudanças e popula as tabelas com dados pré-definidos no desenvolvimento do software.

```shell
php impetus migrate --arg
```
Argumentos disponíveis: --up, --sync, --create.

- --up ou --sync: Sincroniza todas as migrations localizadas em './build/backend/database/migrations' com o seu banco de dados local;
- --create: Cria uma nova migration na pasta './build/backend/database/migrations', essa migration vem com uma série de exemplos de como incluir tabelas e views no banco de dados, fazer alterações na estrutura de tabela e inserir dados.

#### Build

O comando 'build' realiza a leitura de uma tabela no banco de dados e automatiza a criação de códigos frequentemente desenvolvidos no dia a dia do programador, leia atentamente cada uma de suas variações para saber como e quando usar.

```shell
php impetus build --arg tableName
```

Argumentos disponíveis: --webservice, --model, --controllers, --routes, --api, --view, --raw-view, --empty-view.

- --webservice: Utizando uma tabela no banco de dados definida em "tableName", o ImpetusPHP irá criar todos os arquivos necessários para disponilizar uma série de funcionalidades ao web service da aplicação. O framework cria a Model, Controllers e rotas disponibilizando os seguintes métodos: Criar, atualizar, buscar, listar, selecionar e deletar registros da tabela;

- --model: Cria uma model tendo como base a tabela informada em "tableName";

- --controllers: Cria os controllers tendo como base a tabela informada em "tableName";

- --routes: Cria as rotas tendo como base a tabela informada em "tableName";

- --view: Utilizando uma tabela no banco de dados definida em "tableName", o framework cria uma página inteiramente funcional com a possibilidade de criar, atualizar, buscar, listar, selecionar e deletar dados da tabela. Recomendamos utilizar o --view juntamente ao --webservice, para garantir o funcionamento completo da página, sem precisar de adaptações manuais no código;

#### Template

O comando build cria templates de códigos recorentemente utilizados no desenvolvimento web.

```shell
php impetus template --arg param
```
Argumentos disponíveis: --api, --raw-view, --empty-view.

- --api: Gera um arquivo contendo o boilerplate de uma API Client, o nome desse arquivo é definido em "param";

- --empty-view: Cria uma página vazia no sistema com o nome definido em "param", ela possui apenas os componentes básicos do sistema, tais como sidebar, topbar, footer e etc, porém não possui nenhuma funcionalidade pré-definida;

- --raw-view: Cria uma página HTML vazia com o nome definido em "param";

<hr>

### Guia de Referência de Funções Utilitárias

As funções utilitárias são funções que visam agilizar o desenvolvimento de aplicações web. Essas funções podem ser encontradas na pasta './build/backend/utils'. 

#### ImpetusUtils

O ImpetusUtils é uma classe que possui diversas funções comumente utilizadas no desenvolvimento de webservices.

Lista de funções:

- urlParams: Coleta parâmetros de uma URL;
- dateToken: Cria um token único com base na data e hora do servidor;
- token: Cria um token com base nos parâmetros informados;
- isEmpty: Verifica se uma variável está vazia;
- isLongString: Verifica se uma variável é longa com base no limite informado;
- isShortString: Verifica se uma variável é curta com base no limite informado;
- isNumber: Verifica se a variável é um número;
- isInt: Verifica se a variável é um número inteiro;
- enum: Verifica se a variável informada está presente em uma lista de opções disponíveis;
- isDate: Verifica se a variável é uma data;
- isDateTime: Verifica se a variável é um datetime (data e hora);
- isEmail: Verifica se a variável é um email;
- isBoolean: Verifica se a variável é um booleano;
- isPassword: Verifica se a variável é uma senha segura;
- isStrongPassword: Verifica se a variável é uma senha muito segura;
- isGreaterThan: Verifica se a variável numérica é maior que outra variável numérica;
- isGreaterThanOrEqual: Verifica se a variável numérica é maior ou igual que outra variável numérica;
- isLessThan: Verifica se a variável númerica é menor que outra variável numérica;
- isLessThanOrEqual: Verifica se a variável númerica é menor ou igual que outra variável numérica;
- isBetween: Verifica se a variável númerica está entre duas outras variáveis numéricas;
- purifyString: Realiza a limpeza de uma variável, substituindo caracteres especiais, removendo símbolos e outros tipos de caracteres de acordo com os parâmetros informados;
- validator: Valida uma variável, utilizando diversos critérios definidos nos parâmetros, essa função foi feita para validar dados informados no corpo JSON de requisições feitas por APIs externas.
- bodyCheckFields: Realiza a verificação de todos os dados informados no corpo JSON de requisições feitas por APIs externas, verificando de cada campo corresponde aos requisitos estabecidos.
- base64UrlEncode: Criptografa um arquivo na base64;
- base64UrlDecode: Descriptografa um arquivo em base64;
- getBearerToken: Coleta o Bearer token de uma requisição;
- datetime: Retorna a data e hora no fuso horário e formato solicitado.

#### ImpetusJWT

O ImpetusJWT é uma classe que permite a criação e validação de Json Web Tokens, uma tecnologia de autenticação em APIs muito utilizada.

Lista de funções:

- encode: Cria um JWT utilizando um código secreto informado, este JWT armazenará os dados informados em $params e possuirá um tempo de expiração em horas informado em $time;
- decode: Valida um JWT informado em $token, verificando se o código secreto utilizado na sua criação bate com o código secreto do sistema. Durante o decode é validado também o tempo de expiração do token.

#### ImpetusFileManager

O ImpetusFileManager é uma classe que permite o envio e gerenciamento de arquivos para a pasta storage (local padrão de armazenamento de arquivos do framework).

Lista de funções:

- saveFile: Realiza a conversão de um base64 para arquivo (txt, pdf, planilhas, fotos e etc.) e armazena em uma pasta especificada;

- createFolders: Cria uma nova pasta dentro da pasta 'storage'.