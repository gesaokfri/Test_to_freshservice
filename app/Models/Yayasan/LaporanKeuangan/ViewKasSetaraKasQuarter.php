<?php

namespace App\Models\Yayasan\LaporanKeuangan;

use CodeIgniter\Model;

class ViewKasSetaraKasQuarter extends Model
{
  protected $table      = 'view_yayasan_lapkeu_kassetarakas_quarter';

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
