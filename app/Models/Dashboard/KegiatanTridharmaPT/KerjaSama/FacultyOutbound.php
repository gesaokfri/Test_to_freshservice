<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama;

use CodeIgniter\Model;

class FacultyOutbound extends Model
{
  protected $DBGroup    = "asatu";
  protected $table      = 'tr_kerjasama_fo';
  protected $primaryKey = 'id_kerjasama_fo';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
