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

O framework exige a utilização de banco de dados. Após a instalação dos pacotes, é necessário configurar a conexão com a base de dados no arquivo .env. 

```
database.default.hostname = localhost
database.default.database = senhaunica-shield
database.default.username = root
database.default.password = sua_senha_secreta
database.default.DBDriver = MySQLi
database.default.DBPrefix =
database.default.port = 3306
```

Criar os arquivos de configuração e as tabelas do banco de dados do framework Shield com o comando

```
php spark shield:setup
```

A documentação para instalação e configuração completa do framework pode ser acessada no link https://shield.codeigniter.com/

Importante: verificar se a proteção csrf está configurada para session no arquivo `app/Config/Security.php`

```
public string $csrfProtection = 'session';
```

Em seguida, executar o comando para adicionar o campo fullname na tabela users e rota para login USP. 

```
php spark auth:senhaunica-setup
```

Após a configuração do Shield, é necessário cadastrar o token da aplicação  em https://uspdigital.usp.br/adminws/oauthConsumidorAcessar e inserir as configurações no .env

```
SENHAUNICA_KEY = iau
SENHAUNICA_SECRET = sua_senha_secreta
SENHAUNICA_CALLBACK_ID = 1
```

Também no .env, configurar a linguagem do sistema, apontar para os models e views customizados da biblioteca e inserir superadmins, se necessário (nº USP separados por vírgula)

```
# Linguagem
app.defaultLocale = pt-BR

## Shield configurações
auth.views.register = \Uspdev\SenhaunicaShield\Views\register
auth.views.login = \Uspdev\SenhaunicaShield\Views\login
auth.userProvider = \Uspdev\SenhaunicaShield\Models\UserModel
auth.allowRegistration = true
auth.superadmin=111111,222222
```

Caso deseje customizar as Views, alterar esses campos. Lembre-se de utilizar o formulário da biblioteca como modelo para não esquecer dos campos da tabela. 

O Shield permite habilitar ou não novos registros, login por URL, etc. Verificar na documentação. 

No caso do auth.allowRegistration, se definido como false, o sistema não permitirá a criação automática de usuário, mesmo com senha única USP, a não ser dos usuários definidos como superadmin em auth.superadmin. Essa diretiva é útil para sistemas em que apenas um número restrito de usuários deve acessá-los. Pode-se criar um painel administrativo para inserção manual dos usuários, definindo-se o número USP como username. Dessa forma, ao tentar logar, o sistema reconhecerá o usuário como cadastrado e automaticamente atualizará seus dados pessoais como fullname e vinculos.

# Atualização para versão 2.0.0
A versão 2.0.0 da biblioteca traz mudanças importantes na estrutura da tabela users e na forma de manipulação dos vínculos dos usuários. Essas alterações podem causar problemas de compatibilidade em sistemas que utilizam a versão 1.2.0, especialmente em consultas, exibições e verificações que dependiam dos seguintes campos, agora removidos:

* tipoVinculo
* codigoSetor
* nomeAbreviadoSetor
* nomeSetor
* codigoUnidade
* siglaUnidade
* nomeUnidade
* nomeVinculo
* nomeAbreviadoFuncao
* tipoFuncao

## Novos campos
* **vinculos:** armazena todos os dados de vínculos do usuário em formato JSON (array)

* **tipoUser**: indica a origem do usuário. 
   * USP → usuário autenticado via Senha Única USP.
   * EXTERNO → usuário criado localmente no sistema, sem autenticação pela USP.

* **observacao**: campo livre para observações adicionais sobre o usuário.

## Novos métodos auxiliares
Para facilitar consultas e verificações sobre vínculos e unidades, foram criados métodos na User Entity. Esses métodos encapsulam a lógica de leitura do campo vinculos e permitem verificações diretas via auth()->user()->metodo().

## Métodos disponíveis na User Entity
Método   | Parâmetros | Retorno | Descrição
--------- | ------ | --------- | ------
getVinculos() | – | array | Retorna todos os vínculos completos em formato de array associativo.
getTiposVinculo() | – | array | Retorna apenas os tipos de vínculo (ex.: ['SERVIDOR','ALUNO']).
getCodigosSetores() | – | array | Retorna todos os códigos de setor associados aos vínculos.
getNomesAbreviadosSetores() | – | array | Retorna os nomes abreviados dos setores.
getNomeSetores() | – | array | Retorna os nomes completos dos setores.
getUnidadesCodigos() | – | array | Retorna os códigos das unidades.
getUnidadesSiglas() | – | array | Retorna as siglas das unidades.
getUnidadesNomes() | – | array | Retorna os nomes completos das unidades.
getVinculosNomes() | – | array | Retorna os nomes dos vínculos (ex.: “Aluno”, “Servidor”).
getNomesAbreviadosFuncao() | – | array | Retorna os nomes abreviados da função do usuário
getTipoFuncao() | – | array | Retorna os tipos de função do usuário
hasVinculo($vinculo) | string $vinculo | bool | Verifica se o usuário possui determinado vínculo (case-insensitive).
hasUnidadeSigla($unidade) | string $unidade | bool | Verifica se o usuário pertence a uma unidade pela sigla (case-insensitive).
hasUnidadeCodigo($unidade) | int $unidade | bool | Verifica se o usuário pertence a uma unidade pelo código.
hasFuncao($funcao) | string $funcao | bool | Verifica se o usuário possui determinada função (case-insensitive). OBS: Alguns docentes possuem tipoVinculo 'SERVIIDOR' e tipoFuncao 'docente'.

## Observações importantes
1. Sistemas que dependiam dos campos removidos devem ser adaptados para usar os novos métodos.

2. Após atualizar para a versão 2.0.0, é necessário rodar as migrations do pacote:

```
php spark migrate --all
```

## Atualização para 2.0.0

Para atualizar o pacote:
```bash
composer update uspdev/senhaunica-shield
php spark migrate --all
```

## Workflow

Ao realizar a autenticação no servidor oAuth USP, a biblioteca verifica se já existe na tabela de usuários do Codeigniter Shield o número USP do usuário. Em caso afirmativo, faz o logon. Caso contrário, cria o usuário na tabela, atribui ao grupo default e, por fim, faz o logon. 

A proteção de rotas e o acesso aos dados do usuário podem ser consultados na documentação oficial do framework em https://shield.codeigniter.com/

## Exemplo de uso

Após configuração, as seguintes rotas estarão disponíveis:

| URI | Descrição | OBS |
|--------|-----------|----------|
| /login | Formulário de Login com botão para login USP | - |
| /loginusp | Redireciona para o sistema de login USP | Lembrar de configurar o token de autenticação |
| /register | Exibe formulário para cadastro de usuário | Pode ser desabilitado no .env com auth.allowRegistration = false |
| /logout | Faz o logout do usuário | - |


# Guia rápido de métodos do CodeIgniter Shield

| Método | Descrição | Como Usar |
|--------|-----------|----------|
| `auth()->attempt($credentials)` | Tenta autenticar um usuário com credenciais fornecidas. | ```php auth()->attempt(['email' => 'user@example.com', 'password' => 'senha123']); ``` |
| `auth()->login($user)` | Faz login de um usuário específico. | ```php $user = model(UserModel::class)->find(1); auth()->login($user); ``` |
| `auth()->logout()` | Encerra a sessão do usuário autenticado. | ```php auth()->logout(); ``` |
| `auth()->user()` | Obtém o usuário autenticado. Retorna `null` se não houver um usuário logado. | ```php $user = auth()->user(); echo $user->email; ``` |
| `auth()->loggedIn()` | Verifica se há um usuário autenticado. | ```php if (auth()->loggedIn()) { echo "Usuário autenticado"; } ``` |
| `auth()->id()` | Retorna o ID do usuário autenticado. | ```php $userId = auth()->id(); ``` |
| `auth()->register($credentials, $activate = false)` | Registra um novo usuário. O segundo parâmetro define se a conta será ativada automaticamente. | ```php auth()->register(['email' => 'newuser@example.com', 'password' => 'senha123'], true); ``` |
| `auth()->forgotPassword($email)` | Inicia o processo de redefinição de senha. | ```php auth()->forgotPassword('user@example.com'); ``` |
| `auth()->resetPassword($token, $newPassword)` | Redefine a senha de um usuário com um token de recuperação. | ```php auth()->resetPassword($token, 'novaSenha123'); ``` |
| `auth()->activate($user)` | Ativa manualmente um usuário. | ```php $user = model(UserModel::class)->find(1); auth()->activate($user); ``` |
| `auth()->throttle()->check($ipAddress, $identifier)` | Verifica se um usuário excedeu as tentativas de login permitidas. | ```php if (auth()->throttle()->check($ip, $email)) { echo "Muitas tentativas de login!"; } ``` |

- Para mais detalhes, consultar a [documentação oficial](https://github.com/codeigniter4/shield).

# Controle de Rotas com CodeIgniter Shield

O **CodeIgniter Shield** fornece filtros para controlar o acesso às rotas com base na sessão do usuário, permissões e grupos.  
Este guia explica resume como configurar e aplicar esses filtros.

---

## 1. Configuração do Arquivo `Filters.php`

Para utilizar os filtros de autenticação do Shield, é necessário adicioná-los ao arquivo de configuração de filtros localizado em:

`app/Config/Filters.php`

### Exemplo de Configuração:

Abra `app/Config/Filters.php` e adicione os filtros do **Shield**:

```php
namespace Config;

use CodeIgniter\Config\BaseConfig;

class Filters extends BaseConfig
{
    public array $aliases = [
        ...
        'session'    => \CodeIgniter\Shield\Filters\SessionAuth::class, // Verifica se o usuário está autenticado
        'group'      => \CodeIgniter\Shield\Filters\GroupFilter::class, // Restringe acesso por grupo
        'permission' => \CodeIgniter\Shield\Filters\PermissionFilter::class, // Restringe acesso por permissão
    ];

    ...
}
```

## 2. Protegendo Rotas no Arquivo Routes.php

No arquivo `app/Config/Routes.php`, podemos aplicar os filtros nas rotas.

### Protegendo Rotas com session

```php
$routes->group('dashboard', ['filter' => 'session'], function ($routes) {
    $routes->get('/', 'DashboardController::index');
});
```
Usuários não autenticados serão redirecionados para a tela de login.

### Protegendo Rotas com session

```php
$routes->group('admin', ['filter' => 'group:admin'], function ($routes) {
    $routes->get('/', 'AdminController::index');
    $routes->get('users', 'AdminController::users');
});
```
Apenas usuários que pertencem ao grupo admin terão acesso

### Protegendo Rotas com session

```php
$routes->group('reports', ['filter' => 'permission:view-reports'], function ($routes) {
    $routes->get('/', 'ReportsController::index');
});
```
Apenas usuários com a permissão view-reports poderão acessar