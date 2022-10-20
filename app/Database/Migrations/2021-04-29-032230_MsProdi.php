<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsProdi extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_prodi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_fakultas" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_prodi" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"jenjang_prodi" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
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
		$this->forge->addKey("id_prodi", true);
		$this->forge->createTable("ms_prodi");
	}

	public function down()
	{
		$this->forge->dropTable('ms_prodi');
	}
}
