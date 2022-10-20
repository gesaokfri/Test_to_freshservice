<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianPublikasiPenulis extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_pengabdian_publikasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tipe" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_penulis" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"posisi_dosen_penulis" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"korespondensi" => [
				"type"       => "CHAR",
				"constraint" => 1,
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
		$this->forge->addKey("id", true);
		$this->forge->createTable("tr_pengabdian_publikasi_penulis");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_publikasi_penulis');
	}
}
