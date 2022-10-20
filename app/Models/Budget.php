<?php

namespace App\Models;

use CodeIgniter\Model;

class Budget extends Model
{
	protected $table                = 'ms_budget';
	protected $primaryKey           = 'budget_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['budget_id','budget_type','budget_name','budget_period',
									   'budget_value','budget_group','created_by','updated_by'
									  ];

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
