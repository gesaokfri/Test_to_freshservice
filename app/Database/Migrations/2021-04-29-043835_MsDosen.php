<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsDosen extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_dosen" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nama_lengkap" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"gelar_depan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"gelar_belakang" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"nip" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"tempat_lahir" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tanggal_lahir" => [
				"type"       => "DATETIME",
				"null"       => false
			],
			"jenkel" => [
				"type"       => "CHAR",
				"constraint" => 1,
				"null"       => false
			],
			"alamat" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"pos_el" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"no_hp" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"npwp" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"no_ktp" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"nama_bank" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"jamsostek" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"bpjs_kes" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"yadapen" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"asuransi" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"nidn" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"id_bidang_keilmuan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"no_kontak_emergency" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => true
			],
			"nama_kontak_emergency" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => true
			],
			"sinta_id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"laman_sinta" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"publon" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"laman_publon" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"scopus_id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"laman_scopus" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"garuda_id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"laman_garuda" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"profile_scholar" => [
				"type"       => "TEXT",
				"null"       => true
			],
			"tag_struktural" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"tag_fungsional" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"tag_admin_fakultas" => [
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
		$this->forge->addKey("id_dosen", true);
		$this->forge->createTable("ms_dosen");
	}

	public function down()
	{
		$this->forge->dropTable('ms_dosen');
	}
}
