<?php

namespace App\Models\Trigger\MahasiswaBaru;

use CodeIgniter\Model;

class MsMahasiswaBaru extends Model
{
  protected $primaryKey           = 'ID';
  protected $table                = 'ms_mahasiswa_baru';
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
