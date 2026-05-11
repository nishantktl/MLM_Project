<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddParentIdToUsersTable extends Migration
{
    public function up()
    {
        $fields = [
            'parent_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id', // place after the id column
            ],
        ];

        $this->forge->addColumn('users', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'parent_id');
    }
}
