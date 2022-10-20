<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranOrasi extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_pengajaran_orasi" => [
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
			"tempat" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"tanggal" => [
				"type"       => "DATETIME",
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
		$this->forge->addKey("id_pengajaran_orasi", true);
		$this->forge->createTable("tr_pengajaran_orasi");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_orasi');
	}
}
