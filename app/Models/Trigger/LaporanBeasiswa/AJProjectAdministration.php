<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class AJProjectAdministration extends Model
{
  protected $DBGroup    = 'StagingData';
  protected $table      = 'AJ_PROJECT_ADMINISTRATION';
  protected $primaryKey = 'HEADER_ID';

  protected $returnType     = 'array';
}
