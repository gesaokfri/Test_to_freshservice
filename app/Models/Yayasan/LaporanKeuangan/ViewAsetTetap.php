<?php

namespace App\Models\Yayasan\LaporanKeuangan;

use CodeIgniter\Model;

class ViewAsetTetap extends Model
{
  protected $table      = 'view_yayasan_lapkeu_asettetap';

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
