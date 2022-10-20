<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class FakultasSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'id_fakultas'            => 1,
				'nama_fakultas' => 'Fakultas Pendidikan dan Bahasa',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 2,
				'nama_fakultas' => 'Fakultas Psikologi',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 3,
				'nama_fakultas' => 'Fakultas Ekonomi dan Bisnis',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 4,
				'nama_fakultas' => 'Fakultas Teknobiologi',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 5,
				'nama_fakultas' => 'Fakultas Hukum',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 6,
				'nama_fakultas' => 'Fakultas Teknik',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 7,
				'nama_fakultas' => 'Fakultas Ilmu Administrasi Bisnis dan Ilmu Komunikasi',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			],
			[
				'id_fakultas'            => 8,
				'nama_fakultas' => 'Fakultas Kedokteran dan Ilmu Kesehatan',
				'created_at'    => date("Y-m-d H:i:s"),
				'created_by'    => 'Admin'
			]
		];

		// Using Query Builder
		$this->db->table('ms_fakultas')->insertBatch($data);
	}
}
