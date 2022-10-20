<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianKekayaanint extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_kekayaanint" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tahun" => [
				"type"       => "CHAR",
				"constraint" => 4,
				"null"       => false
			],
			"judul" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"jenis_kekayaanint" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen_pencipta" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"kontribusi_dosen_pencipta" => [
				"type"       => "INT",
				"null"       => false
			],
			"id_mahasiswa_pencipta" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"kontribusi_mahasiswa_pencipta" => [
				"type"       => "INT",
				"null"       => false
			],
			"id_eksternal_pencipta" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"kontribusi_eksternal_pencipta" => [
				"type"       => "INT",
				"null"       => false
			],
			"status" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"url" => [
				"type"       => "TEXT",
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
		$this->forge->addKey("id_penelitian_kekayaanint", true);
		$this->forge->createTable("tr_penelitian_kekayaanint");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_kekayaanint');
	}
}
