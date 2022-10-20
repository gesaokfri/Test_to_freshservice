<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianDiseminasi extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_diseminasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul_presentasi" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"nama_forum" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"skala_forum" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"penyelenggara" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"tanggal" => [
				"type"       => "DATETIME",
				"null"       => false
			],
			"tempat" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"presentasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
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
			"sumber_artikel" => [
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
			],
		]);
		$this->forge->addKey("id_penelitian_diseminasi", true);
		$this->forge->createTable("tr_penelitian_diseminasi");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_diseminasi');
	}
}
