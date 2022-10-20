<?php

namespace App\Models\Trigger\LaporanBeasiswa;

use CodeIgniter\Model;

class TRPembayaranBeasiswa extends Model
{
  protected $table      = 'tr_aj_pembayaran_beasiswa';
  protected $primaryKey = 'id_aj_pembayaran_beasiswa';

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
