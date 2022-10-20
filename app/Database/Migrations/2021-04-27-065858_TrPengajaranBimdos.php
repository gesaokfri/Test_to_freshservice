<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranBimdos extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_pengajaran_bimdos" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen_pembimbing" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tahun_semester" => [
				"type"       => "CHAR",
				"constraint" => 4,
				"null"       => false
			],
			"id_dosen_terbimbing" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false,
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
		$this->forge->addKey("id_pengajaran_bimdos", true);
		$this->forge->createTable("tr_pengajaran_bimdos");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_bimdos');
	}
}
