<?php

namespace App\Models\Trigger\PiutangAging;

use CodeIgniter\Model;

class PiutangAging extends Model
{
  protected $DBGroup              = 'StagingData';
  protected $table                = 'PIUTANG_AGING';
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
