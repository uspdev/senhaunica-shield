<?php
namespace Uspdev\SenhaunicaShield\Controllers;

use App\Controllers\BaseController;
use Uspdev\SenhaunicaShield\SenhaunicaShield;

class Loginusp extends BaseController
{
    public function loginusp()
    {
        SenhaunicaShield::login();
        header('Location:../');
        exit;
    }
}