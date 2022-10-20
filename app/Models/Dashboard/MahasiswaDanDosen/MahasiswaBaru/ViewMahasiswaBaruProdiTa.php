<?php

namespace App\Models\Dashboard\MahasiswaDanDosen\MahasiswaBaru;

use CodeIgniter\Model;

class ViewMahasiswaBaruProdiTa extends Model
{
	protected $table                = 'view_mahasiswa_baru_prodi_ta';
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
