# UFVJM - Client GraphQL

## Definir variáveis de ambiente

### Client Id e Client Key da Aplicação

Lançar os valores corretos para os arquivos da integração com os microsserviços no arquivo **.env**. 

Substituir os valores de **GRAPHQL_APP_ID** e **GRAPHQL_APP_KEY** para os valores cadastrados na stack de Microsserviços DTI/DDS:

```env
GRAPHQL_APP_ID=
GRAPHQL_APP_KEY=
```

* Ambiente de Testes:

```
GRAPHQL_URL=https://micro-teste.dds.ufvjm.edu.br/
```

Após alterações no arquivo **.env**, o container web deve ser reiniciado para recarregar as alterações:

## Documentação

Mais informações: [Portal do Desenvolvedor](https://portal-dev-teste.dds.ufvjm.edu.br/)

## Equipe Responsável

Divisão de Desenvolvimento de Sistemas <devsis@ufvjm.edu.br>

## Parceiros

* PROGRAD - Sistema de Monitoria
* PROEXC - Sistema de Bolsas
