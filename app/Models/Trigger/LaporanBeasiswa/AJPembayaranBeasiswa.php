<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class AJPembayaranBeasiswa extends Model
{
  protected $DBGroup    = 'StagingData';
  protected $table      = 'AJ_PEMBAYARAN_BEASISWA';
  protected $primaryKey = 'HEADER_ID';

  protected $returnType     = 'array';
}
