<?php

namespace Uspdev\SenhaunicaShield;

use CodeIgniter\Shield\Entities\User;

class Login
{
    public static function authenticate($userDetails)
    {
        // Cria instância do provider (usuários)
        $users = auth()->getProvider();

        // Busca o usuário existente pelo login
        $user = $users->findByCredentials(['username' => $userDetails['loginUsuario']]);

        // Se o usuário não existir, registra
        if (!$user) {
            $user = new User([
                'username' => $userDetails['loginUsuario'],
                'email'    => $userDetails['emailPrincipalUsuario'],
                'fullname' => $userDetails['nomeUsuario'],
            ]);
            $users->save($user);

            // Busca o usuário recém-criado
            $user = $users->findById($users->getInsertID());

            // Verifica se está na lista de superadmins do .env
            $superadmins = explode(',', getenv('auth.superadmin') ?? '');
            $superadmins = array_map('trim', $superadmins);

            if (in_array($userDetails['loginUsuario'], $superadmins)) {
                $user->addGroup('superadmin');
            } else {
                $users->addToDefaultGroup($user);
            }
        } else {
            // Atualiza email e nome completo se estiverem diferentes
            $updated = false;

            if (empty($user->email) || $user->email !== $userDetails['emailPrincipalUsuario']) {
                $user->email = $userDetails['emailPrincipalUsuario'];
                $updated = true;
            }

            if (empty($user->fullname) || $user->fullname !== $userDetails['nomeUsuario']) {
                $user->fullname = $userDetails['nomeUsuario'];
                $updated = true;
            }

            if ($updated) {
                $users->save($user);
            }
        }

        // Realiza o login
        auth()->login($user);
    }
}
