<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsMahasiswa extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_mahasiswa" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_prodi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"nim" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"nama_mahasiswa" => [
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
		$this->forge->addKey("id_mahasiswa", true);
		$this->forge->createTable("ms_mahasiswa");
	}

	public function down()
	{
		$this->forge->dropTable('ms_mahasiswa');
	}
}
