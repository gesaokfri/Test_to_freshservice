<?php

namespace App\Models;

use CodeIgniter\Model;

class Menu extends Model
{
	protected $table                = 'ms_menu';
	protected $primaryKey           = 'id_menu';
	protected $useAutoIncrement     = false;
	protected $returnType           = 'array';
}
