[![Pipeline](https://git.dds.ufvjm.edu.br/ufvjm/graphql-php-client/badges/test/pipeline.svg)](//git.dds.ufvjm.edu.br/ufvjm/graphql-php-client/pipelines)
[![Coverage](https://git.dds.ufvjm.edu.br/ufvjm/graphql-php-client/badges/test/coverage.svg)](//git.dds.ufvjm.edu.br/ufvjm/graphql-php-client)
[![Latest Stable Version](https://poser.pugx.org/ufvjm/graphql-client/v)](//packagist.org/packages/ufvjm/graphql-client)
[![Total Downloads](https://poser.pugx.org/ufvjm/graphql-client/downloads)](//packagist.org/packages/ufvjm/graphql-client)
[![Latest Unstable Version](https://poser.pugx.org/ufvjm/graphql-client/v/unstable)](//packagist.org/packages/ufvjm/graphql-client)
[![License](https://poser.pugx.org/ufvjm/graphql-client/license)](//packagist.org/packages/ufvjm/graphql-client)

# Client GraphQL - UFVJM

Uma biblioteca PHP para realizar requisições ao servidor GraphQL da UFVJM.

## Sumário

   * [Client GraphQL - UFVJM](#client-graphql---ufvjm)
      * [Sumário](#sumário)
      * [Utilização da biblioteca](#utilização-da-biblioteca)
         * [Pré-requisitos](#pré-requisitos)
         * [Adicionar biblioteca como dependência](#adicionar-biblioteca-como-dependência)
         * [Definir variáveis de ambiente](#definir-variáveis-de-ambiente)
            * [Client Id e Client Key da Aplicação](#client-id-e-client-key-da-aplicação)
            * [Nome do ambiente](#nome-do-ambiente)
         * [Autenticação na API](#autenticação-na-api)
         * [Integrando a autenticação](#integrando-a-autenticação)
         * [Exemplos de consultas](#exemplos-de-consultas)
            * [Busca por código](#busca-por-código)
            * [Busca de informações paginadas](#busca-de-informações-paginadas)
            * [Carregando relacionamentos](#carregando-relacionamentos)
      * [Contribuindo para a biblioteca](#contribuindo-para-a-biblioteca)
         * [Repositório](#repositório)
         * [Ferramentas](#ferramentas)
            * [Testes](#testes)
      * [Documentação](#documentação)
      * [Equipe Responsável](#equipe-responsável)
      * [Parceiros](#parceiros)

## Utilização da biblioteca

### Pré-requisitos

* PHP 7.1 ou superior
* [Composer](https://getcomposer.org/) instalado e configurado
* Aplicação cadastrada no [Portal do Desenvolvedor](https://portal-dev-teste.dds.ufvjm.edu.br/) da UFVJM 

### Adicionar biblioteca como dependência

Na raíz do seu projeto, execute o **composer**

* Via composer diretamente:

```bash
composer require ufvjm/graphql-client
```

* Ou via container docker:

```bash
docker run --rm --interactive --tty \
    --volume $PWD:/app \
    composer require ufvjm/graphql-client
```

### Definir variáveis de ambiente

Informar corretamente os valores abaixo para as variáveis de ambiente:

#### Client Id e Client Key da Aplicação

Lançar os valores corretos para os arquivos da integração com os microsserviços no arquivo **.env**. 

Substituir os valores de **GRAPHQL_APP_ID** e **GRAPHQL_APP_KEY** para os valores cadastrados na stack de Microsserviços DTI/DDS:

```env
GRAPHQL_APP_ID=
GRAPHQL_APP_KEY=
```

#### Nome do ambiente

Define se o seu sistema apontará para o ambiente de `testes` ou de `produção` (sistema oficial da UFVJM).

* Ambiente de Testes:

```
GRAPHQL_ENVNAME=teste
```

* Ambiente de Produção:

```
AINDA NÃO DISPONIBILIZADO
```

Após alterações no arquivo **.env**, o container web deve ser reiniciado para recarregar as alterações:

### Autenticação na API

A autenticação é controlado por 2 tokens:

* **Token de Aplicativo**: aplicativo cadastrado e autorizado no Portal da API.
* **Token de Usuário**: usuário logado em sua Conta Institucional.

![Query para gerar os tokens](docs/img/arquitetura-gerar-tokens.png)

A Autenticação do aplicativo é realizada fornecendo o appId e appKey fornecidos quando você realiza o cadastro 
do seu aplicativo no Portal da API. A autenticação do usuário é relizada fornecendo o usuário e senha da 
Conta Institucional da UFVJM.

Quando a query de Autenticação é executada, serão retornados 2 tokens:

* um token de autenticação válido por 24 horas para o aplicativo
* e um token de usuário válido por 3 horas.

Essa biblioteca armazena o token retornado na sessão PHP e, a cada nova requisição esses tokens serão utilizados. 
Antes de cada requisição a biblioteca testa a validade do token e, quando for o caso, 
realiza sua renovação através de uma requisição de renovação.

![Requisições com autenticação](docs/img/arquitetura-consulta-com-autenticacao.png)

Os tokens são do tipo JWT (JSON Web Token), o artigo 
[O que é JSON Web Token?](https://www.devmedia.com.br/como-o-jwt-funciona/40265) explica o seu funcionamento. 

### Integrando a autenticação

No início do arquivo:

```php
use GraphqlClient\GraphqlRequest\AuthGraphqlRequest;
```

Na função de autenticaçao:

```php
//recupera os dados do formulario
$containstitucional = 'nome.sobrenome';
$senha = 'sua-senha';

try {
    $request = new stdClass();

    if(is_null($containstitucional) or is_null($senha)){
        throw new \Exception('Usuário ou senha não informados');
    }

    $request->containstitucional = $containstitucional;
    $request->password = $senha;

    // Carrega a classe de autenticação
    $authGraphqlRequest = new AuthGraphqlRequest();

    // Tenta realizar o login na Conta Institucional
    $authGraphqlRequest->loginContaInstitucional($request);

    // Recupera as informações do usuário logado
    // Dados pessoais e vinculos (aluno, docente, tae, coordenador de curso, etc) com a UFVJM
    $userInfo = $authGraphqlRequest->usuarioLogadoInfo();

    // Neste ponto, a autenticação funcionou, implementar o carregamento do usuário de banco de dados
    // proprietário da conta institucuinal ($containstitucional) utilizada na autenticação, a senha já foi validada.
    // Realize o login do usuário no seu framework para que a sessão armazene o usuário logado.
} catch (\Exception $e) {
    $errorMessage = $e->getMessage();
    // A mensagem de erro foi carregada, tratar para disponibilizar na interface para o usuário do sistema
}

```

### Exemplos de consultas

Nos exemplos listados abaixo, a autenticação já foi realizado e os tokens de usuário e aplicação já estão salvos na sessão:

#### Busca por código

Buscando a disciplina de código **COM001**

```php
// Carrega a classe de disciplina
$disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

// Recupera informações de disciplina por código
$disciplina = 
    $disciplinaGraphqlRequest->queryGetById('COM001')
    ->getResults();
```

#### Busca de informações paginadas

Busca uma **lista** de **até 3 disciplinas**

```php
// Carrega a classe de disciplina
$disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

// Carrega a paginação solicitando até 3 registros
$pagination = new ForwardPaginationQuery(3);

// Recupera as informações de disciplinas
$disciplinas = 
    $disciplinaGraphqlRequest
    ->queryList($pagination)
    ->getResults();
```

#### Carregando relacionamentos

Busca a **disciplina** de código **COM001** e carrega o relacionamento **departamento**

```php
// Carrega a classe de disciplina
$disciplinaGraphqlRequest = new DisciplinaGraphqlRequest();

// Recupera informações de disciplina por código
$disciplina =
    $disciplinaGraphqlRequest
        ->addRelationDepartamento()
        ->queryGetById('COM001')
        ->getResults();
```

## Contribuindo para a biblioteca

### Repositório

O desenvolvimento é realizado na branch `dev`. Os mantenedores do respoitório levam as alterações aprovadas para a branch `master`.

### Ferramentas

Construindo a imagem, atualizando as bibliotecas e iniciando o container:

```bash
make
```

Executando as validações

```bash
make tests
```

Parando o container:

```bash
make clean
```

#### Testes

PHP Unit:

```bash
make test
```

PHP Code Sniffer:

```bash
make lint-check
```

PHP Code Beauty Fixer:

```bash
make lint-fix
```

PHP Mess Detector:

```bash
make lint-md
```

PHP Security Checker:

```bash
make security-check
```

## Documentação

Mais informações: [Portal do Desenvolvedor](https://portal-dev-teste.dds.ufvjm.edu.br/)

## Equipe Responsável

Divisão de Desenvolvimento de Sistemas <devsis@ufvjm.edu.br>

## Parceiros

* PROGRAD - Sistema de Monitoria
* PROEXC - Sistema de Bolsas
