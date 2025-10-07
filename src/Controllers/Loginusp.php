<?php
namespace Uspdev\SenhaunicaShield\Controllers;

use App\Controllers\BaseController;
use Uspdev\SenhaunicaShield\SenhaunicaShield;

class Loginusp extends BaseController
{
    public function loginusp()
    {
        $key = getenv('SENHAUNICA_KEY');
        $secret = getenv('SENHAUNICA_SECRET');
        $callback = getenv('SENHAUNICA_CALLBACK_ID');

        if (!$key || !$secret || !$callback) {
            return redirect()->to('login')->with('error', 'Falta configurar as credenciais para a conexão com o auth USP');
        }

        SenhaunicaShield::login();
        header('Location:../');
        exit;
    }
}