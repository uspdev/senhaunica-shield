# Senhaunica-ci4
Adaptação da Biblioteca genérica Senha Única para uso no CodeIgniter 4

## Dependência

* biblioteca league/oauth1-client
* PHP >=7.1 | >=8.0

## Instalação

```
composer require uspdev/senhaunica-ci4
```

## Uso

Esta biblioteca foi testada no Ubuntu 22.04. 

Ela é simplesmente uma adaptação da Biblioteca Senha Única, https://github.com/uspdev/senhaunica, que faz uso de Sessão PHP para armazenar os dados do usuário após login. A alteração foi feita para que todo o trabalho com Sessions sejam realizados com a Library Sessions do CodeIgniter 4. 

Os dados do usuário autenticado podem ser resgatados utilizando a chamada 

```
session()->get('oauth_user')
```

ou 

```
$user = Uspdev\SenhaunicaCI4\SenhaunicaCI4::getUserDetail();
```

Ambos retornam um array com todos os dados obtidos do oauth. Exemplo:

    [loginUsuario] => 111111
    [nomeUsuario] => Jose Maria da Silva
    [tipoUsuario] => I
    [emailPrincipalUsuario] => email@usp.br
    [emailAlternativoUsuario] => email-alternativo@gmail.com
    [emailUspUsuario] => outro-email@usp.br
    [numeroTelefoneFormatado] => (0xx16)1234-5678 - ramal USP: 345678
    [wsuserid] => Iasdkughacsdghçalekhagsghaegawe
    [vinculo] => Array
        (
            [0] => Array
                (
                    [tipoVinculo] => SERVIDOR
                    [codigoSetor] => 000
                    [nomeAbreviadoSetor] => ABC
                    [nomeSetor] => Meu setor
                    [codigoUnidade] => 18
                    [siglaUnidade] => EESC
                    [nomeUnidade] => Escola de Engenharia de São Carlos
                    [nomeVinculo] => Servidor
                    [nomeAbreviadoFuncao] => Minha função
                    [tipoFuncao] => Informática
                )

        )


As informações a seguir foram reescritas com base na biblioteca original, apenas alterando os dados pertinentes:

O token pode ser usado para várias aplicações por meio do callback_id cadastrado em https://uspdigital.usp.br/adminws/oauthConsumidorAcessar

Deve-se criar uma rota (/loginusp por exemplo) com o seguinte código:

```php
require_once __DIR__.'/vendor/autoload.php';

use Uspdev\SenhaunicaCI4\SenhaunicaCI4;

$clientCredentials = [
    'identifier' => 'identificacao',
    'secret' => 'chave-secreta',
    'callback_id' => 0,
];

SenhaunicaCI4::login($clientCredentials);

header('Location:../');
exit;
```

Opcionalmente você pode passar os parâmetros via `env`:

```php
require_once __DIR__.'/vendor/autoload.php';

use Uspdev\SenhaunicaCI4\SenhaunicaCI4;

putenv('SENHAUNICA_KEY=');
putenv('SENHAUNICA_SECRET=');
putenv('SENHAUNICA_CALLBACK_ID=');

SenhaunicaCI4::login();

header('Location:../');
exit;
```
