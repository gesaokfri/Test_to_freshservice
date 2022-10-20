<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianProposal extends Migration
{
	public function up() {
		$this->forge->addField([
			"id_penelitian_proposal" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"jenis_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
				//0 = Dasar, 1 = Terapan, 2 = Pengembangan
			],
			"skema_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
				/*
				0 = JIF
				1 = Pascasarjana
				2 = kompetitif
				3 = lppm
				4 = fakultas,
				5 = Dosen pemula
				*/
			],
			"id_bidang_penelitian" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_tujuan_soseko" => [
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
				"type"       => "VARCHAR",
				"constraint" => 55,
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
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"file" => [
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
		$this->forge->addKey("id_penelitian_proposal", true);
		$this->forge->createTable("tr_penelitian_proposal");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_proposal');
	}
}
