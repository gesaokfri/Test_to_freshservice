<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AccountTypeSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00001",
				'id_parent_account_type' => NULL,
				'account_type'           => "Admin",
				'id_menu'                => 1,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00001",
				'id_parent_account_type' => NULL,
				'account_type'           => "Admin",
				'id_menu'                => 2,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00001",
				'id_parent_account_type' => NULL,
				'account_type'           => "Admin",
				'id_menu'                => 3,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00002",
				'id_parent_account_type' => NULL,
				'account_type'           => "Dosen",
				'id_menu'                => 1,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00003",
				'id_parent_account_type' => NULL,
				'account_type'           => "LPPM",
				'id_menu'                => 1,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
			[
				'id'                     => date('ymdhis')."_".uniqid(),
				'id_account_type'        => "fa_210507085301_00004",
				'id_parent_account_type' => NULL,
				'account_type'           => "Mahasiswa",
				'id_menu'                => 1,
				'acc_read'               => 1,
				'acc_add'                => 1,
				'acc_edit'               => 1,
				'acc_delete'             => 1,
				'acc_upload'             => 1,
				'acc_download'           => 1,
				'created_at'             => date("Y-m-d H:i:s"),
				'created_by'             => 'Admin',
				'updated_at'             => date("Y-m-d H:i:s"),
				'updated_by'             => 'Admin'
			],
		];

		// Using Query Builder
		$this->db->table('ms_account_type')->insertBatch($data);
	}
}
