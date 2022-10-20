<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class TRBeasiswaDonatur extends Model
{
  protected $table      = 'tr_aj_beasiswa_donatur';
  protected $primaryKey = 'id_aj_beasiswa_donatur';

  protected $useAutoIncrement = false;

  protected $returnType     = 'array';
  protected $useSoftDeletes = false;

  protected $allowedFields = [''];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = '';

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
