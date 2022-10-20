<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\Pengabdian;

use CodeIgniter\Model;

class ViewJumlahPengabdian extends Model
{
  protected $table      = 'view_tridharma_dtpengabdian';

  protected $useAutoIncrement = false;

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $useTimestamps = true;
  protected $dateFormat    = 'datetime';
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
