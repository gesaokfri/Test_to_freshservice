<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class AJBeasiswaDonatur extends Model
{
  protected $DBGroup    = 'StagingData';
  protected $table      = 'AJ_BEASISWA_DONATUR';
  protected $primaryKey = 'CODE_DONATUR';

  protected $returnType     = 'array';
}
