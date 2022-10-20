<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuLabaRugi extends Model
{
	protected $table                = 'tr_keu_laba_rugi';
	protected $primaryKey           = 'laba_rugi_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['laba_rugi_id','laba_rugi_type','group_type','laba_rugi_number',
									   'laba_rugi_name','laba_rugi_period','laba_rugi_value','remark',
									   'status','laba_rugi_group','created_by','updated_by', 'laba_rugi_quarter_value'
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
