<?php

namespace App\Models;

use CodeIgniter\Model;

class AccountType extends Model
{
	protected $table                = 'ms_account_type';
	protected $primaryKey           = 'id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['id',
									   'id_account_type',
									   'id_parent_account_type',
									   'account_type',
									   'id_menu',
									   'acc_read',
									   'acc_add',
									   'acc_edit',
									   'acc_delete',
									   'acc_upload',
									   'acc_download',
									   'created_at',
									   'created_by',
									   'updated_at',
									   'updated_by'
									];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';
}