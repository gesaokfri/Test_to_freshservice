<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\Pengabdian;

use CodeIgniter\Model;

class ViewTridharmaPengabdianFakultas extends Model
{
	
	protected $table                = 'view_tridharma_pengabdian_fakultas';
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
