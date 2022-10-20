<?php

namespace App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama;

use CodeIgniter\Model;

class StudentInbound extends Model
{
  protected $DBGroup    = "asatu";
  protected $table      = 'tr_kerjasama_studentinbound';
  protected $primaryKey = 'id_kerjasama_studin';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
