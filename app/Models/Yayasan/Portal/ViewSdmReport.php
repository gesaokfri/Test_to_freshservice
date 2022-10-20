<?php

namespace App\Models\Yayasan\Portal;

use CodeIgniter\Model;

class ViewSdmReport extends Model
{
  protected $table      = 'ViewSdmReport';

  protected $returnType     = 'array';

  protected $allowedFields = [];

  protected $useTimestamps = false;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';
  protected $deletedField  = 'deleted_at';

  protected $validationRules    = [];
  protected $validationMessages = [];
  protected $skipValidation     = false;
}
