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
        $vinculoLower = mb_strtolower($vinculo);

        foreach ($tipos as $tipo) {
            if (mb_strtolower((string)$tipo) === $vinculoLower) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário faz parte de determinada unidade (por sigla).
     *
     * @param string $unidade
     * @return bool
     */
    public function hasUnidade(string $unidade): bool
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        if (!is_array($vinculos)) {
            return false;
        }

        $unidades = array_column($vinculos, 'siglaUnidade');
        $unidadeLower = mb_strtolower($unidade);

        foreach ($unidades as $sigla) {
            if (mb_strtolower((string)$sigla) === $unidadeLower) {
                return true;
            }
        }

        return false;
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
