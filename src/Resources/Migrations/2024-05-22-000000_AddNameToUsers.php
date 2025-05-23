<?php

namespace Uspdev\SenhaunicaShield\Resources\Migrations;

use CodeIgniter\Database\Forge;
use CodeIgniter\Database\Migration;

class AddNameToUsers extends Migration
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
            'fullname' => ['type' => 'VARCHAR', 'constraint' => '255', 'null' => true],
        ];
        $this->forge->addColumn($this->tables['users'], $fields);
        $this->forge->addColumn();
    }

    public function down()
    {
        $fields = [
            'fullname',
        ];
        $this->forge->dropColumn($this->tables['users'], $fields);
    }
}
