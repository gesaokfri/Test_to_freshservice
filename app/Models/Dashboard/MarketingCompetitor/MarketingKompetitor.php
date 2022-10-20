<?php

namespace App\Models\Dashboard\MarketingCompetitor;

use CodeIgniter\Model;

class MarketingKompetitor extends Model
{
	protected $table                = 'tr_competitor';
	protected $primaryKey           = 'competitor_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['competitor_id','competitor_name','competitor_fakultas','competitor_jurusan','competitor_value','tahun_akademik','status','created_by','updated_by'];

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

	// Callbacks
	protected $allowCallbacks       = true;
	protected $beforeInsert         = [];
	protected $afterInsert          = [];
	protected $beforeUpdate         = [];
	protected $afterUpdate          = [];
	protected $beforeFind           = [];
	protected $afterFind            = [];
	protected $beforeDelete         = [];
	protected $afterDelete          = [];
}
