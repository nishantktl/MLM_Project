<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvestmentTransactionsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'BIGINT',
                'constraint'     => 20,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            // User who transferred the amount
            'from_user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            // Member who received the investment
            'to_user_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
            ],

            // Amount transferred
            'investment_amount' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],

            // Sender balances
            'from_balance_before' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'from_balance_after' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],

            // Receiver balances
            'to_balance_before' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],
            'to_balance_after' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
            ],

            // Transaction type
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['initial_topup', 're_topup'],
                'default'    => 'initial_topup',
            ],
            // Transaction status
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['success', 'failed'],
                'default'    => 'success',
            ],

            // Optional remarks
            'remarks' => [
                'type' => 'TEXT',
                'null' => true,
            ],

            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],

            'created_by' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addKey('from_user_id');
        $this->forge->addKey('to_user_id');

        $this->forge->createTable('investment_transactions');
    }

    public function down()
    {
        $this->forge->dropTable('investment_transactions');
    }
}