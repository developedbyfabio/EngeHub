<?php

namespace App\Providers;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use App\Models\User;
use App\Models\SystemUser;

class CustomUserProvider extends EloquentUserProvider
{
    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        if (empty($credentials) ||
            (count($credentials) === 1 &&
             array_key_exists('password', $credentials))) {
            return;
        }

        // Primeiro, tentar encontrar na tabela system_users
        if (isset($credentials['username'])) {
            $systemUser = SystemUser::where('username', $credentials['username'])->first();
            if ($systemUser) {
                return $systemUser;
            }
        }

        // Se não encontrar, tentar na tabela users (admin)
        if (isset($credentials['email'])) {
            $user = User::where('email', $credentials['email'])->first();
            if ($user) {
                return $user;
            }
        }

        // Se não encontrar, tentar username na tabela users também
        if (isset($credentials['username'])) {
            $user = User::where('username', $credentials['username'])->first();
            if ($user) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Validate a user's credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials)
    {
        $plain = $credentials['password'];

        // Se for um SystemUser, verificar se está ativo
        if ($user instanceof SystemUser) {
            if (!$user->is_active) {
                return false;
            }
        }

        return $this->hasher->check($plain, $user->getAuthPassword());
    }
}
