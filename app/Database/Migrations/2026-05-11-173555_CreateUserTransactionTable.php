<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserTransactionTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],
            'txn_amt' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2', // Perfect for currency (e.g. 99999999.99)
            ],
            'utr' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'unique'     => true,   // 🛑 Prevents submitting the same UTR twice
            ],
            'request_dt' => [
                'type'       => 'DATETIME',
                'null'       => true,
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'approved', 'rejected'],
                'default'    => 'pending',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('user_id'); // Speeds up queries searching by user_id
        
        $this->forge->createTable('user_transaction');
    }

    public function down()
    {
        $this->forge->dropTable('user_transaction');
    }
}