<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BidangpenelitianSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'id_bidang_penelitian'          => 1,
				'nama_bidang_penelitian' => 'Agricultural and Veterinary Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 2,
				'nama_bidang_penelitian' => 'Behavioural and Cognitive',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 3,
				'nama_bidang_penelitian' => 'Biological Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 4,
				'nama_bidang_penelitian' => 'Chemical Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 5,
				'nama_bidang_penelitian' => 'Commerce, Management, Tourism and Services',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 6,
				'nama_bidang_penelitian' => 'Communications Technologies',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 7,
				'nama_bidang_penelitian' => 'Computer Hardware',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 8,
				'nama_bidang_penelitian' => 'Economics',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 9,
				'nama_bidang_penelitian' => 'Education',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 10,
				'nama_bidang_penelitian' => 'Electrical and Electonic',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 11,
				'nama_bidang_penelitian' => 'Environmental Engineering',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 12,
				'nama_bidang_penelitian' => 'Environmental Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 13,
				'nama_bidang_penelitian' => 'History and Archeology',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 14,
				'nama_bidang_penelitian' => 'Information, Computing, and Communication Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 15,
				'nama_bidang_penelitian' => 'Journalism, Librarianship and Curatorial Studies',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 16,
				'nama_bidang_penelitian' => 'Law, Justice, and Law Enforcement',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 17,
				'nama_bidang_penelitian' => 'Manufacturing Engineering',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 18,
				'nama_bidang_penelitian' => 'Mechanical and Industrial Engineering',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 19,
				'nama_bidang_penelitian' => 'Medical Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 20,
				'nama_bidang_penelitian' => 'Other Medical and Health Sciences ',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 21,
				'nama_bidang_penelitian' => 'Policy and Political Sciences',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 22,
				'nama_bidang_penelitian' => 'Public Health and Health Services',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 23,
				'nama_bidang_penelitian' => 'Resources Engineering',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_bidang_penelitian'          => 24,
				'nama_bidang_penelitian' => 'Studies in Human Society',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
		];

		// Using Query Builder
		$this->db->table('ms_bidang_penelitian')->insertBatch($data);
	}
}
