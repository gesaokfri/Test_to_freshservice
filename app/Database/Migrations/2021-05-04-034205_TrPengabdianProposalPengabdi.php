<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPengabdianProposalPengabdi extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_pengabdian_proposal" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"tipe" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_pengabdi" => [
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
		$this->forge->createTable("tr_pengabdian_proposal_pengabdi");
	}

	public function down()
	{
		$this->forge->dropTable('tr_pengabdian_proposal_pengabdi');
	}
}
