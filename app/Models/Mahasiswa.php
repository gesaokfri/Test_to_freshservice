<?php

namespace App\Models;

use CodeIgniter\Model;

class Mahasiswa extends Model
{
	protected $DBGroup              = 'StagingData';
	protected $table                = 'Mahasiswa';
	protected $primaryKey           = 'id_mahasiswa';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = [];

	// Dates
	protected $useTimestamps        = true;
	protected $dateFormat           = 'datetime';
	protected $createdField         = 'created_at';
	protected $updatedField         = 'updated_at';

	// Validation
	protected $validationRules      = [];
	protected $validationMessages   = [];
	protected $skipValidation       = false;
	protected $cleanValidationRules = true;

}
