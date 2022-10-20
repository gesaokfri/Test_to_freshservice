<?php

namespace App\Models\Trigger\MahasiswaBaru;

use CodeIgniter\Model;

class DashboardJumlahPmbPerTahun extends Model
{
  protected $DBGroup              = 'StagingData';
  protected $table                = 'DashboardJumlahPmbPerTahun';
  protected $useAutoIncrement     = false;
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
