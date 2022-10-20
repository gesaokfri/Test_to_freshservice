<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class AJPenerimaanBeasiswa extends Model
{
  protected $DBGroup    = 'StagingData';
  protected $table      = 'AJ_PENERIMAAN_BEASISWA';
  protected $primaryKey = 'HEADER_ID';

  protected $returnType     = 'array';
}
