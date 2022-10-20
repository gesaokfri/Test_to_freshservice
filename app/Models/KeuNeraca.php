<?php

namespace App\Models;

use CodeIgniter\Model;

class KeuNeraca extends Model
{
	protected $table                = 'tr_keu_neraca';
	protected $primaryKey           = 'neraca_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['neraca_id','neraca_type','group_type','neraca_number',
									   'neraca_name','neraca_group','neraca_period','neraca_value',
									   'remark','status','reference','segment4','created_by','updated_by', 'neraca_quarter_value'
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
