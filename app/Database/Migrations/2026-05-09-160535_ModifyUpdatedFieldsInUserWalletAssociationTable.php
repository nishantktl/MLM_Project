<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyUpdatedFieldsInUserWalletAssociationTable extends Migration
{
    public function up()
    {
        $this->forge->modifyColumn('user_wallet_association', [
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