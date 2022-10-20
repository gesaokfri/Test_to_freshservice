<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class MsMenu extends Migration
{
	public function up()
	{
		$this->forge->addField([
			"id_menu" => [
				"type"       => "INT",
				"constraint" => 4,
			],
			"reference" => [
				"type"       => "INT",
				"constraint" => 4,
				"null"       => true
			],
			"level_menu" => [
				"type"       => "INT",
				"constraint" => 4,
			],
			"menu" => [
				"type"       => "VARCHAR",
				"constraint" => 55,
			],
			"urutan" => [
				"type"       => "INT",
				"constraint" => 4,
			],
			"url_menu" => [
				"type"       => "VARCHAR",
				"constraint" => 50,
				"null"       => true
			],
			"icon_menu" => [
				"type"       => "VARCHAR",
				"constraint" => 30,
				"null"       => true
			]
		]);
		$this->forge->addKey("id_menu", true);
		$this->forge->createTable("ms_menu");
	}

	public function down()
	{
		$this->forge->dropTable('ms_menu');
	}
}
