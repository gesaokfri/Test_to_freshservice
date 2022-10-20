<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianBangdikpen extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_bangdikpen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_kegiatan" => [
				"type"       => "TEXT",
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
		$this->forge->addKey("id_pengabdian_bangdikpen", true);
		$this->forge->createTable("tr_pengabdian_bangdikpen");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_bangdikpen');
	}
}
