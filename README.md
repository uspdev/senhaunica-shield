# Senhaunica
Biblioteca genérica para integrar senha única em PHP

## Dependência

biblioteca zorrodg/oauth-php  
biblioteca ext-curl

## Instalação

Se seu projeto não usa composer ainda, é uma boa idéia começar a usá-lo.

```
composer init
composer require uspdev/senhaunica
composer install
```

## Uso

Esta biblioteca foi testada em debian 10, debian 9, ubuntu 20.04, ubuntu 18.04 e ubuntu 16.04.

O token pode ser usado para várias aplicações por meio do callback_id cadastrado em https://dev.uspdigital.usp.br/adminws/

Deve-se criar uma rota (/loginusp por exemplo) com o seguinte código:

```php
require_once __DIR__.'/vendor/autoload.php';
session_start();

use Uspdev\Senhaunica\Senhaunica;

putenv('SENHAUNICA_KEY=');
putenv('SENHAUNICA_SECRET=');
putenv('SENHAUNICA_CALLBACK_ID=');

# se for usar ambiente de testes use
# putenv('SENHAUNICA_DEV=');

$auth = new Senhaunica();
$res = $auth->login();

echo '<pre>';
print_r($res);
echo '</pre>';

header('Location:/alguma_rota');

```

Se você quiser, por exemplo, validar o vínculo do login, use o código abaixo. Ele irá retornar o primeiro vínculo que encontrar dentro da lista fornecida. Ao invés de usar `tipoVinculo` você pode usar qualquer variável dentro do array de vínculos.

```php
$vinculo = $auth->obterVinculo('tipoVinculo', ['SERVIDOR','OUTRO_VINCULO', '...']);
```

OBS: 7/8/2020 - atualizado para utilizar variáveis de ambiente com os parâmetros do oauth,
tornado obsoleto o uso de array de configuração no contrutor. 
