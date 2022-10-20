<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranBimstd extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_pengajaran_bimstd" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_mahasiswa" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"jenis" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tahun_semester" => [
				"type"       => "CHAR",
				"constraint" => 4,
				"null"       => false
			],
			"status" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
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
			]
		]);
		$this->forge->addKey("id_pengajaran_bimstd", true);
		$this->forge->createTable("tr_pengajaran_bimstd");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_bimstd');
	}
}
