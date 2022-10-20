<?php

namespace App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa;

use CodeIgniter\Model;

class ViewMahasiswaProdiTa extends Model
{
  protected $table                = 'view_mahasiswa_prodi_ta';
  protected $useAutoIncrement     = false;
  protected $returnType           = 'array';
  protected $protectFields        = true;
  protected $allowedFields        = [];

  // Dates
  protected $useTimestamps        = true;
  protected $dateFormat           = 'datetime';
  protected $createdField         = 'created_at';
  protected $updatedField         = 'updated_at';

  // Validation
  protected $validationRules      = [];
  protected $validationMessages   = [];
  protected $skipValidation       = false;
  protected $cleanValidationRules = true;
}
