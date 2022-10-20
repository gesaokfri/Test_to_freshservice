<?php

namespace App\Models\Dashboard\MarketingCompetitor;

use CodeIgniter\Model;

class ViewMarketingCompetitorProdi extends Model
{

  protected $table                = 'view_competitor_prodi';
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
