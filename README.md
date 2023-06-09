# Teste prático - Backend

## Descrição

API RESTful utilizando Laravel e MYSQL.

Aplicação apresenta uma organização bem simples, na arquitetura monolítica que inteliga todos os componentes em um único programa dentro de uma única plataforma.
Como também, foi utilizado o conceito do 'Repository Pattern' que permite um encapsulamento da lógica de acesso a dados, impulsionando o uso da injeção de dependencia (DI) e proporcionando uma visão mais orientada a objetos das interações com a DAL. Na minha opinião, esse pattern é bem intessante, pois conseguimos abstrair o acesso direto com banco. Além disso, é um bom Pattern para entender e estudar definições importantes.

## Documentação OpenAPI/Swagger

Nesse projeto, configuerei o swagger para documentar os endpoints. Sem necessidade de um software terceiro para testá los.

Acessar documentação [Swagger](http://localhost:8000/api/documentation)

Página que irá abrir:

![Swagger](https://gitlab.com/MLopesG/teste-back-end/-/raw/main/imagens/Documentation%20.png)

## Observação:

Não se esqueça de fazer o login para utilizar os endpoints:

1º Passo(Autenticar com seu usuário e senha):

![Swagger](https://gitlab.com/MLopesG/teste-back-end/-/raw/main/imagens/Login.png)

Copie o token gerado:

*Exemplo de retorno:*

```bash
{
  "status": true,
  "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0OjgwMDAvYXBpL3YxL2dlcmVudGVzL2xvZ2luIiwiaWF0IjoxNjY5NTg4NDQ1LCJleHAiOjE2Njk1OTIwNDUsIm5iZiI6MTY2OTU4ODQ0NSwianRpIjoiTWgzUG5INlpuOTVwZDc2VSIsInN1YiI6IjEiLCJwcnYiOiIxNWJlNDhiNjdjNmE4YmM4ZjI1MjFlYzdlNzQ0MGM2MzliNjhlNjE5In0.BdB28RgwEAllB1NO6xd_s-86x3TAMtOTSd8x5AeBpl0",
  "usuario": {
    "id": 1,
    "nome": "Marcos",
    "email": "marcoslopesg7@gmail.com",
    "created_at": "2022-11-27T21:12:21.000000Z",
    "updated_at": "2022-11-27T21:12:21.000000Z"
  }
}

```

2º Passo(Cole o token para autorizar acesso):

![Swagger](https://gitlab.com/MLopesG/teste-back-end/-/raw/main/imagens/Auth.png)

## Iniciando Aplicação

### Baixar projeto:

GitHub

```bash
$ git clone https://gitlab.com/MLopesG/teste-back-end
```

### Instalar dependências:

Dentro do diretório, execute o comando:

```bash
$ composer install 
```

### Criando o banco de dados:

 - Configure a conexão com o banco no arquivo ".env". 

Exemplo:

```bash
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=lista_tarefas
DB_USERNAME=root
DB_PASSWORD=
```

 - Crie um database "lista_tarefas" no SGBD MYSQL e execute os seguintes comandos:

```bash

- Comando para criar estrutura(banco):

$  php artisan migrate

- Comando para criar registros primários:

$  php artisan db:seed  

```

Por fim, só subir o serviço da aplicação:

```bash
$  php artisan serve
```

## Testando Aplicação

Nessa aplicação, contém dois tipos de testes(Unitário, Integração).

Comando para executar os testes:

```bash
$  php artisan test
```

Exemplo:

![Swagger](https://gitlab.com/MLopesG/teste-back-end/-/raw/main/imagens/Teste.png)

### Observação: 

Caso você queira criar um novo database para não interferir no banco de produção, fique à vontade.

 - Configure a conexão com o banco ".env.testing". 

Exemplo:

```bash
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=lista_tarefas_test
DB_USERNAME=root
DB_PASSWORD=
```

Execute os comando:

```bash

- Comando para criar estrutura(banco):

$  php artisan migrate

- Comando para criar registros primários:

$  php artisan db:seed  

```