<?php

namespace App\Models;

use CodeIgniter\Model;

class Hibah extends Model
{
	protected $table                = 'tr_hibah';
	protected $primaryKey           = 'hibah_id';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
	protected $protectFields        = true;
	protected $allowedFields        = ['hibah_id','hibah_name','hibah_price_companion','hibah_periode','hibah_price_received',
									   'hibah_institution','hibah_total_pengabdian','hibah_total_penelitian',
									   'hibah_total_penelitian_pengabdian','status','hibah_pic','hibah_member',
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
