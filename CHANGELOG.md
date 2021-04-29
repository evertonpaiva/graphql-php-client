## [0.0.13] - 2021-04-29

### Adicionado
- Adicionando filtro por Tipo de Curso e Modalidade de Curso na consulta de listagem por cursos
- Criando constantes para enumeração dos valores de Tipo de Curso
- Criando constantes para enumeração dos valores de Modalidade de Curso

## [0.0.12] - 2021-04-27

### Adicionado
- Adicionando entidade EnsinoTipoCurso
- Adicionando entidade EnsinoModalidade
- Adicionando relacionamento EnsinoCurso com EnsinoTipoCurso e EnsinoModalidade
- Adicionando filtro por nome na consulta de listagem por cursos

### Corrigido
- Testes de CommonPessoa

## [0.0.11] - 2021-01-18

### Adicionado
- Adicionando campo cpf_limpo na entidade CommonPessoa

## [0.0.10] - 2020-09-01

### Adicionado
- Adicionando informações pessoais na função usuarioLogadoInfo

## [0.0.9] - 2020-08-28

### Adicionado
- Adicionando filtro de busca de servidor por SIAPE na entidade RhServidor

## [0.0.8] - 2020-08-07

### Adicionado
- Adicionando telefone e celular na entidade CommonPessoa

## [0.0.7] - 2020-08-07

### Adicionado
- Adicionando entidade EnsinoSituacao
- Adicionando relacionamento EnsinoAluno com EnsinoSituacao
- Adicionando situacao de RhServidor

## [0.0.6] - 2020-08-05

### Adicionado
- Adicionando relacionamento EnsinoPessoa com RhServidor

## [0.0.5] - 2020-08-05

### Adicionado
- Adicionando entidade EnsinoPrograma
- Adicionando entidade RhServidor
- Adicionando entidade AdministracaoSetor

## [0.0.4] - 2020-08-03

### Adicionado
- Configuração do ambiente de produção
- Adicionando entidade EnsinoAluno

## [0.0.3] - 2020-07-08

### Corrigido
- URL do ambiente de testes, forçando https
- Corrigindo chave pública do ambient de testes
- Atualizando pacote gmostafa/php-graphql-client

### Adicionado

- Entidade AlmoxarifadoMaterial
- Entidade EnsinoCurso

## [0.0.2] - 2020-06-14

### Adicionado

- Enviando biblioteca para o Packagist

## [0.0.1] - 2020-06-14

### Adicionado

- Autenticação na API
- Renovação de Token automática
- Entidade EnsinoDocente 
- Entidade EnsinoDisciplina
- Entidade EnsinoDepartamento
