<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\Hibah;

use CodeIgniter\Model;

class ViewTridharmaHibahAnggaran extends Model
{
	
	protected $table                = 'view_tridharma_hibah_anggaran_sum';
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
