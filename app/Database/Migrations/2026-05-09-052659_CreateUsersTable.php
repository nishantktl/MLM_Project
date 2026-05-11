<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUsersTable extends Migration
{
    public function up()
{
    $this->forge->addField([
        'id' => [
            'type'           => 'INT',
            'constraint'     => 5,
            'unsigned'       => true,
            'auto_increment' => true,
        ],
        'user_id' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
            'unique'     => true,
        ],
        'username' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
        ],
        'phone' => [
            'type'       => 'VARCHAR',
            'constraint' => '20',
        ],
        'email' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
            'unique'     => true,
        ],
        'password' => [
            'type'       => 'VARCHAR',
            'constraint' => '255',
        ],
        'parent_id' => [
            'type'       => 'VARCHAR',
            'constraint' => '100',
            'null'       => true,
        ],
        'status' => [
            'type'       => 'VARCHAR',
            'constraint' => '20',
            'default'    => 'PENDING',
        ],
        'txn_pin' => [
            'type'       => 'VARCHAR',
            'constraint' => '10',
        ],
        'created_at' => [
            'type' => 'DATETIME',
            'null' => true,
        ],
        'created_by' => [
            'type' => 'INT',
            'constraint' => 5,
            'unsigned' => true,
            'null' => true,
        ],
        'updated_at' => [
            'type' => 'VARCHAR',
            'constraint' => '255',
            'null' => true,
        ],
        'updated_by' => [
            'type' => 'INT',
            'constraint' => 5,
            'unsigned' => true,
            'null' => true,
        ]
    ]);
    $this->forge->addKey('id', true);
    $this->forge->createTable('users');
}


    public function down()
    {
        //
    }
}
