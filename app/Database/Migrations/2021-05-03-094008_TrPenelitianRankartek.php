<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianRankartek extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_penelitian_rankartek" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul_kegiatan" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"kegiatan" => [
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
		$this->forge->addKey("id_penelitian_rankartek", true);
		$this->forge->createTable("tr_penelitian_rankartek");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_rankartek');
	}
}
