<?php

namespace App\Models\Dashboard\MahasiswaDanDosen\Dosen;

use CodeIgniter\Model;

class ViewDosenJabatanAkademik extends Model
{
	protected $table                = 'view_dosen_jabatanakademik';
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
