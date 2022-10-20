<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsEksternal extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_eksternal" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_eksternal" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"institusi_eksternal" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"negara_eksternal" => [
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
		$this->forge->addKey("id_eksternal", true);
		$this->forge->createTable("ms_eksternal");
	}

	public function down()
	{
		$this->forge->dropTable('ms_eksternal');
	}
}
