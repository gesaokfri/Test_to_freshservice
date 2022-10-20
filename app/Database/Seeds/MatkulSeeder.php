<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MatkulSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
			'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
			'kode_matkul'        => 'MGT 101',
			'nama_matkul'        => 'Bahasa Indonesia',
			'id_bidang_keilmuan' => 1,
			'created_at'         => date("Y-m-d H:i:s"),
			'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 108',
				'nama_matkul'        => 'Bahasa Inggris Bisnis',
				'id_bidang_keilmuan' => 1,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 205',
				'nama_matkul'        => 'Manajemen Keuangan I',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 207',
				'nama_matkul'        => 'Manajemen Operasi I',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 209',
				'nama_matkul'        => 'Manajemen Pemasaran I',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 206',
				'nama_matkul'        => 'Manajemen Keuangan II',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 208',
				'nama_matkul'        => 'Manajemen Operasi II',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 210',
				'nama_matkul'        => 'Manajemen Pemasaran II',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 213',
				'nama_matkul'        => 'Manajemen Sumber Daya Manusia',
				'id_bidang_keilmuan' => 3,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			],
			[
				'id_matkul'          => "mat_".date("ymdhis")."_".uniqid(),
				'kode_matkul'        => 'MGT 107',
				'nama_matkul'        => 'Matematika Ekonomi',
				'id_bidang_keilmuan' => 2,
				'created_at'         => date("Y-m-d H:i:s"),
				'created_by'         => 'Admin'
			]
		];

		// Using Query Builder
		$this->db->table('ms_matkul')->insertBatch($data);
	}
}
