<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianPublikasi extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_pengabdian_publikasi" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"judul_artikel" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"nama_jurnal" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"institusi_penerbit" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"negara_penerbit" => [
				"type"       => "VARCHAR",
				"constraint" => 255,
				"null"       => false
			],
			"volume" => [
				"type"       => "INT",
				"constraint" => 5,
				"null"       => false
			],
			"nomor" => [
				"type"       => "INT",
				"constraint" => 5,
				"null"       => false
			],
			"halaman" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"issn_isbn" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"doi" => [
				"type"       => "TEXT",
				"null"       => false
			],
			"tanggal_terbit" => [
				"type"       => "DATETIME",
				"null"       => false
			],
			"url" => [
				"type"       => "TEXT",
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
		$this->forge->addKey("id_pengabdian_publikasi", true);
		$this->forge->createTable("tr_pengabdian_publikasi");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_publikasi');
	}
}
