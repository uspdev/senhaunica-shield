<?php

namespace Uspdev\SenhaunicaShield\Database\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddVinculo extends Migration
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
        $fields = [
             'vinculos' => [
                'type'       => 'TEXT',
                'null'       => true,
            ]
        ];
        $this->forge->addColumn($this->tables['users'], $fields);
    }

    public function down()
    {
        $fields = [
            'vinculos',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
