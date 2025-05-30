<?php

declare(strict_types=1);

namespace Uspdev\SenhaunicaShield\Models;

use CodeIgniter\Shield\Models\UserModel as ShieldUserModel;

class UserModel extends ShieldUserModel
{
    protected function initialize(): void
    {
        parent::initialize();

        $this->allowedFields = [
            ...$this->allowedFields,
            'fullname',
            'tipoVinculo',
            'codigoSetor',
            'nomeAbreviadoSetor',
            'nomeSetor',
            'codigoUnidade',
            'siglaUnidade',
            'nomeUnidade',
            'nomeVinculo',
            'nomeAbreviadoFuncao',
            'tipoFuncao',
        ];
    }
}
