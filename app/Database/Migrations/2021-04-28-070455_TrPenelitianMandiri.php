<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianMandiri extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_mandiri" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"jenis_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"skema_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"bidang_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tujuan_soseko" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tema_rip" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"abstrak" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"keywords" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"luaran" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"file" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"anggaran" => [
				"type"       => "INT",
				"null"       => false
			],
			"institusi_pendana" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
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
		$this->forge->addKey("id_penelitian_mandiri", true);
		$this->forge->createTable("tr_penelitian_mandiri");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_mandiri');
	}
}
