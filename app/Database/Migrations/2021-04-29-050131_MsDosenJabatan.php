<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsDosenJabatan extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"dinas_tetap" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"tgl_dinas_tetap" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"jabatan_akademik" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"jabatan_akademik_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"jabatan_akademik_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"jabatan_struktural_1" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"jabatan_struktural_1_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"jabatan_struktural_1_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"jabatan_struktural_2" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"jabatan_struktural_2_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"jabatan_struktural_2_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"jabatan_fungsional_1" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"jabatan_fungsional_1_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"jabatan_fungsional_1_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"jabatan_fungsional_2" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"jabatan_fungsional_2_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"jabatan_fungsional_2_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"inpassing" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"inpassing_sk" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"inpassing_tmt" => [
				"type"       => "DATETIME",
				"null"       => true
			],
		]);
		$this->forge->addKey("id_dosen", true);
		$this->forge->createTable("ms_dosen_jabatan");
	}

	public function down()
	{
		$this->forge->dropTable('ms_dosen_jabatan');
	}
}
