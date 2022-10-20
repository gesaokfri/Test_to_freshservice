<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuCapex extends Model
{
	protected $table                = 'tr_keu_capex';
	protected $primaryKey           = 'capex_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['capex_id','capex_number','capex_name',
									   'capex_period','capex_value','capex_group','remark','status',
									   'created_by','updated_by'
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
