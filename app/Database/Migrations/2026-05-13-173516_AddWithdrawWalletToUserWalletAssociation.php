<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddWithdrawWalletToUserWalletAssociation extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user_wallet_association', [
            'withdraw_wallet' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0.00,
                'after'      => 'deposit_balance',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('user_wallet_association', 'withdraw_wallet');
    }
}