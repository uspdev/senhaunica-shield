<?php

declare(strict_types=1);

namespace Uspdev\SenhaunicaShield\Entities;

use CodeIgniter\Shield\Entities\User as ShieldUser;

class User extends ShieldUser
{
    /**
     * Retorna vínculos do usuário em formato de array
     * 
     * @return array
     */
    public function getVinculos(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);
        return is_array($vinculos) ? $vinculos : [];
    }

    /**
     * Verifica se o usuário possui determinado vínculo.
     *
     * @param string $vinculo
     * @return bool
     */
    public function hasVinculo(string $vinculo): bool
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        if (!is_array($vinculos)) {
            return false;
        }

        $tipos = array_column($vinculos, 'tipoVinculo');

        return in_array($vinculo, $tipos, true);
    }

    /**
     * Retorna todos os vínculos como array simples.
     *
     * @return array
     */
    public function getTiposVinculo(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'tipoVinculo') : [];
    }
}
