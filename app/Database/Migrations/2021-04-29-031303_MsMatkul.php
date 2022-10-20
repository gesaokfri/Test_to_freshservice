<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsMatkul extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_matkul" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"kode_matkul" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_matkul" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"id_bidang_keilmuan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
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
		$this->forge->addKey("id_matkul", true);
		$this->forge->createTable("ms_matkul");
	}

	public function down()
	{
		$this->forge->dropTable('ms_matkul');
	}
}
