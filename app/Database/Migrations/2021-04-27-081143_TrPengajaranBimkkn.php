<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranBimkkn extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_pengajaran_bimkkn" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_matkul" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"program" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"kelas" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"jumlah_mhs" => [
				"type"       => "INT",
				"constraint" => 5,
				"null"       => false
			],
			"sks" => [
				"type"       => "INT",
				"constraint" => 3,
				"null"       => false
			],
			"jumlah_pertemuan_ajar" => [
				"type"       => "INT",
				"constraint" => 3,
				"null"       => false
			],
			"jumlah_pertemuan" => [
				"type"       => "INT",
				"constraint" => 3,
				"null"       => false
			],
			"tahun_semester" => [
				"type"       => "CHAR",
				"constraint" => 4,
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
		$this->forge->addKey("id_pengajaran_bimkkn", true);
		$this->forge->createTable("tr_pengajaran_bimkkn");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_bimkkn');
	}
}
