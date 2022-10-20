<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class AJMasterBeasiswa extends Model
{
  protected $DBGroup    = 'StagingData';
  protected $table      = 'AJ_MASTER_BEASISWA';
  protected $primaryKey = 'HEADER_ID';

  protected $returnType     = 'array';
}
