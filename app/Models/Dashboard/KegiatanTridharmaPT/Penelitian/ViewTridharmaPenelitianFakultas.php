<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\Penelitian;

use CodeIgniter\Model;

class ViewTridharmaPenelitianFakultas extends Model
{
	
	protected $table                = 'view_tridharma_penelitian_fakultas';
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
