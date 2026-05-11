<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUpdatedFieldsInUsersTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'updated_at' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
            'updated_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'default'    => null,
            ],
        ]);
    }

    public function down()
    {
    }
}