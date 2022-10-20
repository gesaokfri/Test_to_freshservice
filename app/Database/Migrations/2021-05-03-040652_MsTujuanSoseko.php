<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsTujuanSoseko extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_tujuan_soseko" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_bidang_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_tujuan_soseko" => [
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
		$this->forge->addKey("id_tujuan_soseko", true);
		$this->forge->createTable("ms_tujuan_soseko");
	}

	public function down()
	{
		$this->forge->dropTable('ms_tujuan_soseko');
	}
}
