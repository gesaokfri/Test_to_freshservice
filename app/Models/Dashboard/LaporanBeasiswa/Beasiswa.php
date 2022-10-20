<?php

namespace App\Models\Dashboard\LaporanBeasiswa;

use CodeIgniter\Model;

class Beasiswa extends Model
{
  protected $table      = 'tr_aj_master_beasiswa';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $useTimestamps = false;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
