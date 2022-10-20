<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class TrPenelitianProposalPeneliti extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_penelitian_proposal" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"keterangan" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_peneliti" => [
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
		$this->forge->createTable("tr_penelitian_proposal_peneliti");
	}

	public function down()
	{
		$this->forge->dropTable('tr_penelitian_proposal_peneliti');
	}
}
