<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianJabatan extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_jabatan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_jabatan" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"institusi" => [
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
		$this->forge->addKey("id_pengabdian_jabatan", true);
		$this->forge->createTable("tr_pengabdian_jabatan");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_jabatan');
	}
}
