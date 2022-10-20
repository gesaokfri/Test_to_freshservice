<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsTemaRip extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_tema_rip" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_fakultas" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_tema" => [
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
		$this->forge->addKey("id_tema_rip", true);
		$this->forge->createTable("ms_tema_rip");
	}

	public function down()
	{
		$this->forge->dropTable('ms_tema_rip');
	}
}
