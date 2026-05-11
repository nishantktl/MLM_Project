<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateParentIdToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'parent_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('users', $fields);
    }

    public function down()
    {
        $fields = [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('users', $fields);
    }
}