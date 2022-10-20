<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsUser extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_user" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => false
			],
			"username" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => false
			],
			"password" => [
				"type"       => "VARCHAR",
				"constraint" => 25,
				"null"       => false
			],
			"account_type" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
				"null"       => false
			],
			"email" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => false
			],
			"name" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => false
			],
			"photo" => [
				"type"       => "VARCHAR",
				"constraint" => 50,
				"null"       => false
			],
			"created_at" => [
				"type" => "DATETIME",
				"null" => false
			],
			"created_by" => [
				"type"       => "VARCHAR",
				'constraint' => 55,
				"null"       => false
			],
			"updated_at" => [
				"type"      => "DATETIME",
				"null"      => true,
			],
			"updated_by" => [
				"type"       => "VARCHAR",
				'constraint' => 55,
				"null"       => true
			],
		]);
		$this->forge->addKey("id_user", true);
		$this->forge->createTable("ms_user");
	}

	public function down()
	{
		$this->forge->dropTable('ms_user');
	}
}
