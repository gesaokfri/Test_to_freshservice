<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AccounttypeMigration extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"id_account_type" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_parent_account_type" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => true
			],
			"account_type" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"id_menu" => [
				"type"       => "INT",
				"constraint" => 4,
				"null"       => true
			],
			"acc_read" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"acc_edit" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"acc_delete" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"acc_add" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"acc_upload" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"acc_download" => [
				"type"       => "INT",
				"constraint" => 2,
				"null"       => true
			],
			"created_at" => [
				"type"       => "DATETIME",
				"null"       => true
			],
			"created_by" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => true
			],
			"updated_at" => [
				"type"       => "DATETIME",
				"null"       => true,
			],
			"updated_by" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => true
			],
		]);
		$this->forge->addKey("id", true);
		$this->forge->createTable("ms_account_type");
	}

	public function down()
	{
		$this->forge->dropTable('ms_account_type');
	}
}
