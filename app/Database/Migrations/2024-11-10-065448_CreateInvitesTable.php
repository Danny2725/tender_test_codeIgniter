<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvitesTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tender_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'supplier_email' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tender_id', 'tenders', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('invites');
    }

    public function down() {
        $this->forge->dropTable('invites');
    }
}