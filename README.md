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
     * [Integrando a autenticação](#integrando-a-autenticação)
  * [Contribuindo para a biblioteca](#contribuindo-para-a-biblioteca)
     * [Repositório](#repositório)
     * [Ferramentas de lint](#ferramentas-de-lint)
  * [Documentação](#documentação)
  * [Equipe Responsável](#equipe-responsável)
  * [Parceiros](#parceiros)

## Utilização da biblioteca

### Pré-requisitos

* PHP 7.0 ou superior
* [Composer](https://getcomposer.org/) instalado e configurado

### Adicionar biblioteca como dependência

No arquivo `composer.json` do seu projeto, adicione os seguintes itens:

Na entrada `repositories`

```json
"repositories": [
    {
        "type": "vcs",
        "url": "https://git.dds.ufvjm.edu.br/micro/graphql-client.git"
    }
],
```

Na entrada `require`

```json
"require": {
    "micro/graphql-client": "dev-master",
},
```

### Definir variáveis de ambiente

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

## Contribuindo para a biblioteca

### Repositório

O desenvolvimento é realizado na branch `dev`. Os mantenedores do respoitório levam as alterações aprovadas para a branch `master`.

### Ferramentas de lint

Construindo a imagem

```bash
./build.sh
```

Iniciando o container

```bash
./run.sh
```

Executando as validações

```bash
# Entrando no container
docker exec -it graphql-client bash

# Testando padronizacao
./phpcs.sh

# Tentar corrigir automaticamente
./phpcbf.sh

# Procurando sujeira de código
./phpmd.sh
```

## Documentação

Mais informações: [Portal do Desenvolvedor](https://portal-dev-teste.dds.ufvjm.edu.br/)

## Equipe Responsável

Divisão de Desenvolvimento de Sistemas <devsis@ufvjm.edu.br>

## Parceiros

* PROGRAD - Sistema de Monitoria
* PROEXC - Sistema de Bolsas
