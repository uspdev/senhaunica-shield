# Senhaunica-shield
Biblioteca para integração da senha única USP com o framework Codeigniter Shield

## Dependência

* biblioteca league/oauth1-client
* PHP >=8.1
* codeigniter4/shield

## Instalação e configuração

```
composer require uspdev/senhaunica-shield
```

Após a instalação dos pacotes, é necessário configurar a conexão com a base de dados no arquivo .env. 

```
database.default.hostname = localhost
database.default.database = senhaunica-shield
database.default.username = root
database.default.password = sua_senha_secreta
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

Criar os arquivos de configuração e as tabelas do framework Shield com o comando

```
php spark shield:setup
```

A documentação para instalação e configuração completa do framework pode ser acessada no link https://shield.codeigniter.com/

Após a configuração do Shield, é necessário cadastrar o token da aplicação  em https://uspdigital.usp.br/adminws/oauthConsumidorAcessar e inserir as configurações no .env

```
SENHAUNICA_KEY = iau
SENHAUNICA_SECRET = sua_senha_secreta
SENHAUNICA_CALLBACK_ID = 1
```
Criar o método e rota para callback e fazer a chamada da biblioteca para autentitcação

```
$routes->get('/loginusp', 'Login::loginusp');
```

```php
public function loginusp()
    {
        SenhaunicaShield::login();
        header('Location:../');
        exit;
    }
```

## Uso

Ao realizar a autenticação no servidor oAuth USP, a biblioteca verifica se já existe na tabela de usuários do Codeigniter Shield o número USP do usuário. Em caso afirmativo, faz o logon. Caso contrário, cria o usuário na tabela, atribui ao grupo default e, por fim, faz o logon. 

A proteção de rotas e o acesso aos dados do usuário podem ser consultados na documentação oficial do framework em https://shield.codeigniter.com/

## Observações

* O Codeigniter Shield, por padrão, fornece formulários para login, criação de novos usuários, login via magic-link. Para desabilitar essas e outras funções, ou customizar as views para uso, consultar a documentação oficial. 
