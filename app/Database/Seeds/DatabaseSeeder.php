<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	public function run()
	{
		$this->call('UserSeeder');
		$this->call('MenuSeeder');
		$this->call('EksternalSeeder');
		$this->call('KeilmuanSeeder');
		$this->call('ProdiSeeder');
		$this->call('MatkulSeeder');
		$this->call('MahasiswaSeeder');
		$this->call('DosenSeeder');
		$this->call('BidangPenelitianSeeder');
		$this->call('TujuanSosekoSeeder');
		$this->call('TemaRipSeeder');
		$this->call('FakultasSeeder');
		$this->call('CoinstructorSeeder');
		$this->call('AccountTypeSeeder');
	}
}
