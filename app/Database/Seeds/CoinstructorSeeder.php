<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\Prodi;

class CoinstructorSeeder extends Seeder
{
	public function run()
	{
		$prodi = new Prodi;
		$data = $prodi->findAll();
		$id_prodi = [];
		foreach($data as $item) {
			$id_prodi[] = $item['id_prodi'];
		}
		$data = [
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
			[
			'id_co_instructor'        => "co_".date("ymdhis")."_".uniqid(),
			'nama_co_instructor'      => static::faker()->name,
			'id_prodi'                => static::faker()->randomElements($id_prodi),
			'negara_co_instructor'    => static::faker()->country,
			'institusi_co_instructor' => static::faker()->word,
			'created_at'              => date("Y-m-d H:i:s"),
			'created_by'              => 'Admin'
			],
		];

		// Using Query Builder
		$this->db->table('ms_co_instructor')->insertBatch($data);
	}
}
