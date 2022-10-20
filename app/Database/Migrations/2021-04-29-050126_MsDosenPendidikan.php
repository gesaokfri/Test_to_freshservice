<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsDosen_pendidikanPendidikan extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"ijazah_s1" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"tgl_ijazah_s1" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"id_keilmuan_s1" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"transkrip_s1" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"penyetaraan_s1" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"ijazah_s2" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"tgl_ijazah_s2" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"id_keilmuan_s2" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"transkrip_s2" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"penyetaraan_s2" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"ijazah_s3" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"tgl_ijazah_s3" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"id_keilmuan_s3" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"transkrip_s3" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"penyetaraan_s3" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
		]);
		$this->forge->addKey("id_dosen", true);
		$this->forge->createTable("ms_dosen_pendidikan");
	}

	public function down()
	{
		$this->forge->dropTable('ms_dosen_pendidikan');
	}
}
