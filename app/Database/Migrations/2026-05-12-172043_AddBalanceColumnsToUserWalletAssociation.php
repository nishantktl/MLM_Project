<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBalanceColumnsToUserWalletAssociation extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user_wallet_association', [
            'current_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'null'       => false,
                'after'      => 'withdrawal_balance', // Change this to the column after which you want to place it
            ],
            'investment_balance' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'null'       => false,
                'after'      => 'current_balance',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn(
            'user_wallet_association',
            ['current_balance', 'investment_balance']
        );
    }
}