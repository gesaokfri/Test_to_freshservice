<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianPengjuril extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_pengjuril" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_jurnal" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"institusi_penerbit" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"negara_penerbit" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"laman_jurnal" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"issn" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"status_akreditasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"url_scimago" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"peran" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tahun_penugasan" => [
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
			],
		]);
		$this->forge->addKey("id_pengabdian_pengjuril", true);
		$this->forge->createTable("tr_pengabdian_pengjuril");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_pengjuril');
	}
}
