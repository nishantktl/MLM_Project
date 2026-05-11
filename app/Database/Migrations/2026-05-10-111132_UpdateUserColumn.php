<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateUserColumn extends Migration
{
     public function up()
    {
        $this->forge->addColumn('users', [
            'dob' => [
                'type'       => 'VARCHAR',
                'constraint' => 20, 
                'null'       => true,
                'after'      => 'email'
            ],
            
            'gender' => [
                'type'       => 'VARCHAR',
                'constraint' => 10, 
                'null'       => true,
                'after'      => 'dob'
            ],
            
            'address' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'gender'
            ],

            'city' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'address'
            ],

            'state' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
                'after'      => 'city'
            ],

            'pincode' => [
                'type'       => 'VARCHAR',
                'constraint' => 15,
                'null'       => true,
                'after'      => 'state'
            ]
        ]);

        echo "Migration executed successfully: Columns added.";
    }

    public function down()
    {
        // Remove these columns if we rollback
        $this->forge->dropColumn('users', [
            'dob', 
            'gender', 
            'address', 
            'city', 
            'state', 
            'pincode'
        ]);
        
        echo "Rollback successful: Columns removed.";
    }
}
