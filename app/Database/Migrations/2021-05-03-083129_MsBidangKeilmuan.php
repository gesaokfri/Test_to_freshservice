<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsBidangKeilmuan extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_bidang_keilmuan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_bidang_keilmuan" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"created_at" => [
				"type" => "DATETIME",
				"null" => false
			],
			"created_by" => [
				"type"       => "VARCHAR",
				'constraint' => '55',
				"null"       => false
			],
			"updated_at" => [
				"type"      => "DATETIME",
				"null"      => true,
			],
			"updated_by" => [
				"type"       => "VARCHAR",
				'constraint' => '55',
				"null"       => true
			],
		]);
		$this->forge->addKey("id_bidang_keilmuan", true);
		$this->forge->createTable("ms_bidang_keilmuan");
	}

	public function down()
	{
		$this->forge->dropTable('ms_bidang_keilmuan');
	}
}
