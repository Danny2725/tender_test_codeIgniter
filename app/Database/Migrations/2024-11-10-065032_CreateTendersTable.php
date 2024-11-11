<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTendersTable extends Migration {
    public function up() {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 5,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'visibility' => [
                'type'       => 'ENUM',
                'constraint' => ['public', 'private'],
                'default'    => 'public',
            ],
            'creator_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'created_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
            ],
            'updated_at' => [
                'type'    => 'TIMESTAMP',
                'default' => 'CURRENT_TIMESTAMP',
                'on_update' => 'CURRENT_TIMESTAMP',
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('creator_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('tenders');
    }

    public function down() {
        $this->forge->dropTable('tenders');
    }
}