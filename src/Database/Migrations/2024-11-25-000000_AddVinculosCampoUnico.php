<?php

namespace Uspdev\SenhaunicaShield\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddVinculosCampoUnico extends Migration
{
    public function __construct(?Forge $forge = null)
    {
        parent::__construct($forge);

        /** @var \Config\Auth $authConfig */
        $authConfig   = config('Auth');
        $this->tables = $authConfig->tables;
    }

    public function up()
    {
        $fieldsRemove = [
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
        $this->forge->dropColumn('users', $fieldsRemove);
        
        $fieldsAdd = [
            'tipoUser' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'observacao' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'vinculos' => [
                'type'       => 'TEXT',
                'null'       => true,
            ]
        ];
        $this->forge->addColumn($this->tables['users'], $fieldsAdd);
    }

    public function down()
    {
        $fieldsRemove = [
            'tipoUser',
            'observacao',
            'vinculos',
        ];
        $this->forge->dropColumn($this->tables['users'], $fieldsRemove);

        $fieldsAdd = [
            'tipoVinculo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'codigoSetor' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'nomeAbreviadoSetor' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'nomeSetor' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'codigoUnidade' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'siglaUnidade' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'nomeUnidade' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'nomeVinculo' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'nomeAbreviadoFuncao' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
            'tipoFuncao' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
        ];
        $this->forge->addColumn($this->tables['users'], $fieldsAdd);
    }
}
