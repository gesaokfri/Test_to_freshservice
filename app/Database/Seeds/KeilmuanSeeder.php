<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KeilmuanSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'id_bidang_keilmuan'   => 1,
				'nama_bidang_keilmuan' => 'Bahasa',
				'created_at'           => date("Y-m-d H:i:s"),
				'created_by'           => 'Admin'
			],
			[
				'id_bidang_keilmuan'   => 2,
				'nama_bidang_keilmuan' => 'Matematika',
				'created_at'           => date("Y-m-d H:i:s"),
				'created_by'           => 'Admin'
			],
			[
				'id_bidang_keilmuan'   => 3,
				'nama_bidang_keilmuan' => 'Manajemen',
				'created_at'           => date("Y-m-d H:i:s"),
				'created_by'           => 'Admin'
			]
		];

		// Using Query Builder
		$this->db->table('ms_bidang_keilmuan')->insertBatch($data);
	}
}
