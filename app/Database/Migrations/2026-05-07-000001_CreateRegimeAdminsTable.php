<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRegimeAdminsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'nom' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'email' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'password' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('email');
        $this->forge->createTable('regime_admins', true);

        $this->db->table('regime_admins')->ignore(true)->insert([
            'nom' => 'Administrateur',
            'email' => 'admin@regime.local',
            'password' => '$2y$12$fJTt6mN8HEp6wo6FIBZao.XQKbg6y2UEqC/n4YyXx2IJMeWVabsvO',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('regime_admins', true);
    }
}
