<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama;

use CodeIgniter\Model;

class StudentOutbound extends Model
{
  protected $DBGroup    = "asatu";
  protected $table      = 'tr_kerjasama_so';
  protected $primaryKey = 'id_kerjasama_so';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
