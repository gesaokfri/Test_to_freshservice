<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengajaranDetaseringFile extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_pengajaran_detasering" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tipe" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"keterangan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"file" => [
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
		$this->forge->addKey("id", true);
		$this->forge->createTable("tr_pengajaran_detasering_file");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengajaran_detasering_file');
	}
}
