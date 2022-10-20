<?php

namespace App\Models;

use CodeIgniter\Model;

class RencanaKerjaDetail extends Model
{
	protected $table                = 'tr_rencana_kerja_detail';
	protected $primaryKey           = 'rencana_kerja_detail_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['rencana_kerja_detail_id','rencana_kerja_id',
									   'rencana_kerja_detail_quarter','rencana_kerja_verifikasi',
									   'rencana_kerja_detail_status','rencana_kerja_detail_catatan',
									   'rencana_kerja_detail_pencapaian','created_by','updated_by'
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
