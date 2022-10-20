<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianPelayanan extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_pelayanan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_kegiatan" => [
				"type"       => "TEXT",
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
		$this->forge->addKey("id_pengabdian_pelayanan", true);
		$this->forge->createTable("tr_pengabdian_pelayanan");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_pelayanan');
	}
}
