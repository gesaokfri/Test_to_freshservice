<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianPublikar extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_publikar" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"media" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul_artikel" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"nama_jurnal" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"index_international_bereputasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"quartile" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"impact_factor" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"volume" => [
				"type"       => "INT",
				"constraint" => 5,
				"null"       => false
			],
			"nomor" => [
				"type"       => "INT",
				"constraint" => 5,
				"null"       => false
			],
			"halaman" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"issn_isbn" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"doi" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"tanggal_terbit" => [
				"type"       => "DATETIME",
				"null"       => false
			],
			"url" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"dokumen_artikel" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
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
		$this->forge->addKey("id_penelitian_publikar", true);
		$this->forge->createTable("tr_penelitian_publikar");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_publikar');
	}
}
