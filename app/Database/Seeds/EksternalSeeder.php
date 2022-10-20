<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class EksternalSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
			'id_eksternal'        => "eks_".date("ymdhis")."_".uniqid(),
			'nama_eksternal'      => static::faker()->name,
			'institusi_eksternal' => static::faker()->word,
			'negara_eksternal'    => static::faker()->country,
			'created_at'          => date("Y-m-d H:i:s"),
			'created_by'          => 'Admin'
			],
			[
				'id_eksternal'        => "eks_".date("ymdhis")."_".uniqid(),
				'nama_eksternal'      => static::faker()->name,
				'institusi_eksternal' => static::faker()->word,
				'negara_eksternal'    => static::faker()->country,
				'created_at'          => date("Y-m-d H:i:s"),
				'created_by'          => 'Admin'
			],
			[
				'id_eksternal'        => "eks_".date("ymdhis")."_".uniqid(),
				'nama_eksternal'      => static::faker()->name,
				'institusi_eksternal' => static::faker()->word,
				'negara_eksternal'    => static::faker()->country,
				'created_at'          => date("Y-m-d H:i:s"),
				'created_by'          => 'Admin'
			]
		];

		// Using Query Builder
		$this->db->table('ms_eksternal')->insertBatch($data);
	}
}
