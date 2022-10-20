<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsCoInstructor extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_co_instructor" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_co_instructor" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"id_prodi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"negara_co_instructor" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"institusi_co_instructor" => [
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
		$this->forge->addKey("id_co_instructor", true);
		$this->forge->createTable("ms_co_instructor");
	}

	public function down()
	{
		$this->forge->dropTable('ms_co_instructor');
	}
}
