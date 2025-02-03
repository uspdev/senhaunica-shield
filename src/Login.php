<?php

namespace Uspdev\SenhaunicaShield;

use CodeIgniter\Shield\Entities\User;

class Login
{
    public static function authenticate($userDetails)
    {
        // Cria instancia users para registrar, buscar e logar o usuário
        $users = auth()->getProvider();

        // Busca se usuário já existe pelo número USP
        $user = $users->findByCredentials(['username' => $userDetails['loginUsuario']]);

        // Se não existe, faz o registro
        if (!$user) {
            $user = new User([
                'username' => $userDetails['loginUsuario'],
                'email'    => $userDetails['emailPrincipalUsuario'],
            ]);
            $users->save($user);

            // Recebe o usuário que acaba de ser registrado
            $user = $users->findById($users->getInsertID());

            // Adiciona o usuário ao grupo default
            $users->addToDefaultGroup($user);
        }

        auth()->login($user);
    }
}
