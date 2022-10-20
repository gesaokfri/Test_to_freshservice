<?php

namespace App\Models;

use CodeIgniter\Model;

class User extends Model
{
	protected $table                = 'ms_user';
	protected $primaryKey           = 'id_user';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $allowedFields        = [
		'id_user', 
		'username', 
		'password', 
		'account_type', 
		'name', 
		'email', 
		'photo', 
		"created_by", 
		"updated_by",
		"status"
	];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';

}
