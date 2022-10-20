<?php

namespace App\Models\Dashboard\LaporanBeasiswa;

use CodeIgniter\Model;

class ViewBeasiswaPembayaran extends Model
{
  protected $table      = 'view_beasiswa_pembayaran';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $useTimestamps = false;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
