<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTxnPinToUsersTable extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'txn_pin' => [
                'type'       => 'VARCHAR',
                'constraint' => '4',
                'null'       => false,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'txn_pin');
    }
}
