# Senhaunica
Biblioteca genérica para integrar senha única em PHP

## Dependência

biblioteca zorrodg/oauth-php

## Instalação

Se seu projeto não usa composer ainda, é uma boa idéia começar a usá-lo.

```
composer init
composer require uspdev\senhaunica
composer install
```

## Uso
Deve-se criar uma rota (/loginusp por exemplo) com o seguinte código:

```php
require_once('../vendor/autoload.php');

$auth = new Uspdev\Senhaunica\Senhaunica([
    'consumer_key' => 'aaaa',
    'consumer_secret' => 'sdkjfcsdkfhsdkfhsdkfhsdhkf',
    'callback_id' => 1, // callback_id é o sequencial no servidor
    'amb' => 1,// 1=teste, 2=producao
]);

$res = $auth->login();

echo '<pre>';
print_r($res);
echo '</pre>';

header('Location:/alguma_rota');

```
