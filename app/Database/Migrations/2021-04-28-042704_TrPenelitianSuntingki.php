<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianSuntingki extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_suntingki" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tahun_semester" => [
				"type"       => "CHAR",
				"constraint" => 4,
				"null"       => false
			],
			"judul_buku" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"issn_buku" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"url_buku" => [
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
		$this->forge->addKey("id_penelitian_suntingki", true);
		$this->forge->createTable("tr_penelitian_suntingki");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_suntingki');
	}
}
