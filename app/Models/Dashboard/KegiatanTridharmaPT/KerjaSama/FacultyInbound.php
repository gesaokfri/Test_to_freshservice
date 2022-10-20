<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama;

use CodeIgniter\Model;

class FacultyInbound extends Model
{
  protected $DBGroup    = "asatu";
  protected $table      = 'tr_kerjasama_fi';
  protected $primaryKey = 'id_kerjasama_fi';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
