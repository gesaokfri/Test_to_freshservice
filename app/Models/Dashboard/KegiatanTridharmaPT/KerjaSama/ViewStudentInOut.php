<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama;

use CodeIgniter\Model;

class ViewStudentInOut extends Model
{
  protected $table      = 'view_unika_tridharma_kerjasama_siso';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
