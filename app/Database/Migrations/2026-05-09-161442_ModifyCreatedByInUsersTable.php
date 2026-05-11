<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyCreatedByInUsersTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('users', [
            'created_by' => [
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