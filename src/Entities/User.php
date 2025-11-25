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
     * Retorna todos os vínculos como array simples.
     *
     * @return array
     */
    public function getTiposVinculo(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'tipoVinculo') : [];
    }

    /**
     * Retorna todos os Códigos de Setor como array simples.
     *
     * @return array
     */
    public function getCodigosSetores(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'codigoSetor') : [];
    }

    /**
     * Retorna todos os Nomes Abreviados de Setor como array simples.
     *
     * @return array
     */
    public function getNomesAbreviadosSetores(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'nomeAbreviadoSetor') : [];
    }

    /**
     * Retorna todos os Nomes de Setor como array simples.
     *
     * @return array
     */
    public function getNomeSetores(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'nomeSetor') : [];
    }

    /**
     * Retorna todas os códigos das unidades como array simples.
     *
     * @return array
     */
    public function getUnidadesCodigos(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'codigoUnidade') : [];
    }
    
    /**
     * Retorna todas as siglas das unidades como array simples.
     *
     * @return array
     */
    public function getUnidadesSiglas(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'siglaUnidade') : [];
    }


    /**
     * Retorna todos os nomes das unidades como array simples.
     *
     * @return array
     */
    public function getUnidadesNomes(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'nomeUnidade') : [];
    }

    /**
     * Retorna todos os nomes dos vínculos como array simples.
     *
     * @return array
     */
    public function getVinculosNomes(): array
    {
        $vinculos = json_decode($this->attributes['vinculos'] ?? '[]', true);

        return is_array($vinculos) ? array_column($vinculos, 'nomeVinculo') : [];
    }

    /**VERIFICAÇÕES */
    /**
     * Verifica se o usuário possui determinado vínculo.
     *
     * @param string $vinculo
     * @return bool
     */
    public function hasVinculo(string $vinculo): bool
    {
        $tipos = $this->getTiposVinculo();
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
    public function hasUnidadeSigla(string $unidade): bool
    {
        $unidades = $this->getUnidadesSiglas();
        $unidadeLower = mb_strtolower($unidade);

        foreach ($unidades as $sigla) {
            if (mb_strtolower((string)$sigla) === $unidadeLower) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verifica se o usuário faz parte de determinada unidade (por código).
     *
     * @param string $unidade
     * @return bool
     */
    public function hasUnidadeCodigo(int $unidade): bool
    {
        $tipos = $this->getUnidadesCodigos();
        return in_array($unidade, $tipos, true);
    }
}
