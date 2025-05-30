<?php

namespace Uspdev\SenhaunicaShield;

use CodeIgniter\Shield\Entities\User;

class Login
{
    public static function authenticate($userDetails)
    {
        $users = auth()->getProvider();
        $user = $users->findByCredentials(['username' => $userDetails['loginUsuario']]);

        if (!$user) {
            $user = self::registerUser($users, $userDetails);
            self::assignGroup($users, $user, $userDetails['loginUsuario']);
        } else {
            $user = self::updateUserIfNeeded($users, $user, $userDetails);
        }

        auth()->login($user);
    }

    private static function registerUser($users, $details)
    {
        $user = new User([
            'username' => $details['loginUsuario'],
            'email'    => $details['emailPrincipalUsuario'],
            'fullname' => $details['nomeUsuario'],
            'tipoVinculo' => $details['vinculo'][0]['tipoVinculo'],
            'codigoSetor' => $details['vinculo'][0]['codigoSetor'],
            'nomeAbreviadoSetor' => $details['vinculo'][0]['nomeAbreviadoSetor'],
            'nomeSetor' => $details['vinculo'][0]['nomeSetor'],
            'codigoUnidade' => $details['vinculo'][0]['codigoUnidade'],
            'siglaUnidade' => $details['vinculo'][0]['siglaUnidade'],
            'nomeUnidade' => $details['vinculo'][0]['nomeUnidade'],
            'nomeVinculo' => $details['vinculo'][0]['nomeVinculo'],
            'nomeAbreviadoFuncao' => $details['vinculo'][0]['nomeAbreviadoFuncao'],
            'tipoFuncao' => $details['vinculo'][0]['tipoFuncao'],
        ]);

        $users->save($user);
        return $users->findById($users->getInsertID());
    }

    private static function assignGroup($users, $user, $username)
    {
        $superadmins = array_map('trim', explode(',', getenv('auth.superadmin') ?? ''));
        if (in_array($username, $superadmins)) {
            $user->addGroup('superadmin');
        } else {
            $users->addToDefaultGroup($user);
        }
    }

    private static function updateUserIfNeeded($users, $user, $details)
    {
        $updated = false;

        $map = [
            'email' => $details['emailPrincipalUsuario'],
            'fullname' => $details['nomeUsuario'],
            'tipoVinculo' => $details['vinculo'][0]['tipoVinculo'],
            'codigoSetor' => $details['vinculo'][0]['codigoSetor'],
            'nomeAbreviadoSetor' => $details['vinculo'][0]['nomeAbreviadoSetor'],
            'nomeSetor' => $details['vinculo'][0]['nomeSetor'],
            'codigoUnidade' => $details['vinculo'][0]['codigoUnidade'],
            'siglaUnidade' => $details['vinculo'][0]['siglaUnidade'],
            'nomeUnidade' => $details['vinculo'][0]['nomeUnidade'],
            'nomeVinculo' => $details['vinculo'][0]['nomeVinculo'],
            'nomeAbreviadoFuncao' => $details['vinculo'][0]['nomeAbreviadoFuncao'],
            'tipoFuncao' => $details['vinculo'][0]['tipoFuncao'],
        ];

        foreach ($map as $field => $value) {
            if (empty($user->$field) || $user->$field !== $value) {
                $user->$field = $value;
                $updated = true;
            }
        }

        if ($updated) {
            $users->save($user);
        }

        return $user;
    }
}
