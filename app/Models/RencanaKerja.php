<?php

namespace App\Models;

use CodeIgniter\Model;

class RencanaKerja extends Model
{
	protected $table                = 'tr_rencana_kerja';
	protected $primaryKey           = 'rencana_kerja_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['rencana_kerja_id','rencana_kerja_no','rencana_kerja_group',
									   'rencana_kerja_name','rencana_kerja_kegiatan','rencana_kerja_pic',
									   'rencana_kerja_tahun','rencana_kerja_type','created_by','updated_by'
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
