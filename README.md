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

<hr>

### Guia de Comandos (CLI)

Lista de comandos que podem ser utilizados via terminal para automatizar tarefas.

#### Migrate

O comando 'migrate' realiza a construção de tabelas e views no banco de dados, assim como gerencia mudanças e popula as tabelas com dados pré-definidos no desenvolvimento do software.

```shell
php impetus migrate --arg
```
Argumentos disponíveis: --up, --sync, --create.

#### Build

O comando 'build' automatiza diversas tarefas rotineiras no dia a dia do programador, leia atentamente cada uma de suas variações para saber como e quando usar.

```shell
php impetus build --arg tableName
```

Argumentos disponíveis: --webservice, --model, --controler, --route, --api, --view, --raw-view, --empty-view.

<hr>

### Guia de Referência de Funções Utilitárias

As funções utilitárias são funções que visam agilizar o desenvolvimento de aplicações web. Essas funções podem ser encontradas na pasta './build/backend/utils'. 

#### ImpetusUtils

O ImpetusUtils é uma classe que possui diversas funções comumente utilizadas no desenvolvimento de webservices.

Lista de funções:

- urlParams:
- dateToken:
- token:
- isEmpty:
- isLongString:
- isShortString:
- isNumber:
- isInt:
- enum:
- isDate:
- isDateTime:
- isEmail:
- isBoolean:
- isPassword:
- isStrongPassword:
- isGreaterThan:
- isLessThan:
- isLessThanOrEqual:
- isBetween:
- purifyString:
- validator:
- bodyCheckFields:
- base64UrlEncode:
- base64UrlDecode:
- getBearerToken:
- datetime:

#### ImpetusJWT

O ImpetusJWT é uma classe que permite a criação e validação de Json Web Tokens, uma tecnologia de autenticação em APIs muito utilizada.

Lista de funções:

- encode: Cria um JWT utilizando um código secreto informado, este JWT armazenará os dados informados em $params e possuirá um tempo de expiração em horas informado em $time;
- decode: Valida um JWT informado em $token, verificando se o código secreto utilizado na sua criação bate com o código secreto do sistema. Durante o decode é validado também o tempo de expiração do token.

#### ImpetusMaths

O ImpetusMaths é uma classe que permite a realização de certas operações matemáticas não existentes por padrão na linguagem PHP.

Lista de funções:

- factorial: Calcula o fatorial de um número;
- isPrime: Verifica se o número é primo ou não;

#### ImpetusFileManager

O ImpetusFileManager é uma classe que permite o envio e gerenciamento de arquivos para a pasta storage (local padrão de armazenamento de arquivos do framework).

Lista de funções:

- saveFile: Realiza a conversão de um base64 para arquivo (txt, pdf, planilhas, fotos e etc.) e armazena em uma pasta especificada;

- createFolders: Cria uma nova pasta dentro da pasta 'storage'.