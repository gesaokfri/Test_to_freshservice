<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranBangbajar extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_pengajaran_bangbajar" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"jenis" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"co_author" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"issn" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"tahun_semester" => [
				"type"       => "CHAR",
				"constraint" => 4,
				"null"       => false
			],
			"id_matkul" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"penerbit" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"jumlah_halaman" => [
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
		$this->forge->addKey("id_pengajaran_bangbajar", true);
		$this->forge->createTable("tr_pengajaran_bangbajar");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_bangbajar');
	}
}
