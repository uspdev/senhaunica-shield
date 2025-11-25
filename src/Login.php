<?php

namespace Uspdev\SenhaunicaShield;

use CodeIgniter\Shield\Entities\User;

class Login
{
    public static function authenticate($userDetails)
    {
        $users = auth()->getProvider();
        $user = $users->findByCredentials(['username' => $userDetails['loginUsuario']]);
        $authConfig = config('Auth');

        if (!$user) {
            if ($authConfig->allowRegistration) {
                $user = self::registerUser($users, $userDetails);
                self::assignGroup($users, $user, $userDetails['loginUsuario']);
            } else {
                return false;
            }
        } else {
            $user = self::updateUserIfNeeded($users, $user, $userDetails);
        }

        auth()->login($user);
        return true;
    }

    private static function registerUser($users, $details)
    {
        $user = new User([
            'username' => $details['loginUsuario'],
            'email'    => $details['emailPrincipalUsuario'],
            'fullname' => $details['nomeUsuario'],
            'vinculos' => json_encode($details['vinculo'])
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
            'vinculos' => json_encode($details['vinculo'])
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
