<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianPublikarPenulis extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_penelitian_publikar" => [
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
			"urutan_penulis" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"korespondensi" => [
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
		$this->forge->addKey("id", true);
		$this->forge->createTable("tr_penelitian_publikar_penulis");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_publikar_penulis');
	}
}
