<?php

namespace App\Models\Trigger\SDMReport;

use CodeIgniter\Model;

class TRSDMReport extends Model
{
  protected $primaryKey           = 'ID';
  protected $table                = 'tr_sdm_report';
  protected $useAutoIncrement     = true;
  protected $returnType           = 'array';
  protected $protectFields        = true;
  protected $allowedFields        = [];

  // Dates
  protected $useTimestamps        = false;
  protected $dateFormat           = 'datetime';
  protected $createdField         = 'created_at';
  protected $updatedField         = 'updated_at';

  // Validation
  protected $validationRules      = [];
  protected $validationMessages   = [];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;
}
