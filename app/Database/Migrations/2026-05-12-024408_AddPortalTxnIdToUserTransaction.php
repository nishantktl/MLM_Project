<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPortalTxnIdToUserTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'portal_txn_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true, // 🛑 Ensures no duplicate portal IDs
                'after'      => 'id'  // Places it nicely right after the primary key
            ],
        ];

        $this->forge->addColumn('user_transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('user_transaction', 'portal_txn_id');
    }
}