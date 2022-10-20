<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianMandiri extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_mandiri" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"jenis_pengabdian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"skema_pengabdian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_tema_rip" => [
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
		$this->forge->addKey("id_pengabdian_mandiri", true);
		$this->forge->createTable("tr_pengabdian_mandiri");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_mandiri');
	}
}
