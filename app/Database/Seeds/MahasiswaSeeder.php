<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
	public function run()
	{
		$data = [
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 26,
					'nim'            => '2015042010',
					'nama_mahasiswa' => 'Evans Karlin',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 26,
					'nim'            => '201704520018',
					'nama_mahasiswa' => 'Jonathan Christian K',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2016070043',
					'nama_mahasiswa' => 'Cindy Fransiska',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070158',
					'nama_mahasiswa' => 'Eric Gunardy',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070034',
					'nama_mahasiswa' => 'Wilson',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 5,
					'nim'            => '2015004040',
					'nama_mahasiswa' => 'Frieska Soplantila',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '12017000028',
					'nama_mahasiswa' => 'Astrella T',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '12017000972',
					'nama_mahasiswa' => 'Aldo Sebastian',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '12017002223',
					'nama_mahasiswa' => 'Gregoria Christ',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '12017001751',
					'nama_mahasiswa' => 'Jennifer',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 12,
					'nim'            => '12018003835',
					'nama_mahasiswa' => 'Esther Meiliana Nathanael.',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 12,
					'nim'            => '12018003896',
					'nama_mahasiswa' => 'Daniel Joshua',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 10,
					'nim'            => '2016009006',
					'nama_mahasiswa' => 'hanna ariesta',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 32,
					'nim'            => '12017003044',
					'nama_mahasiswa' => 'Jeffry',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 32,
					'nim'            => '12015002715',
					'nama_mahasiswa' => 'Felianti',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 32,
					'nim'            => '12015002729',
					'nama_mahasiswa' => 'Jeniffer',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 32,
					'nim'            => '12015002768',
					'nama_mahasiswa' => 'Theresia Andriana',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 33,
					'nim'            => '20170050003',
					'nama_mahasiswa' => 'jennifer lhema',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 23,
					'nim'            => '2015035002',
					'nama_mahasiswa' => 'Ester Cahyani',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 3,
					'nim'            => '-----',
					'nama_mahasiswa' => 'Helnywati',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 20,
					'nim'            => '2013031009',
					'nama_mahasiswa' => 'Fransiska Regita T',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '-----',
					'nama_mahasiswa' => 'Dimas',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 34,
					'nim'            => '2018060200--',
					'nama_mahasiswa' => 'adeline',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 29,
					'nim'            => '2016001397',
					'nama_mahasiswa' => 'Refindie Micatie',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '201807000115',
					'nama_mahasiswa' => 'Patricia Lidwina',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017000307',
					'nama_mahasiswa' => 'Andreas Wiratmaja Herlambang',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017000459',
					'nama_mahasiswa' => 'Gilbert Christopher Mamoto',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017002883',
					'nama_mahasiswa' => 'Michael Parlindungan',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017000557',
					'nama_mahasiswa' => 'Christoper Vito Ngundi',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017000568',
					'nama_mahasiswa' => 'Stainlus Verrelio',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 9,
					'nim'            => '12017000571',
					'nama_mahasiswa' => 'Christoper Angelo',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070062',
					'nama_mahasiswa' => 'lamuel wilson',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070173',
					'nama_mahasiswa' => 'winda noviana',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070180',
					'nama_mahasiswa' => 'felisitas',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
				[
					
					'id_mahasiswa'             => "mhs_".date("ymdhis")."_".uniqid(),
					'id_prodi'       => 31,
					'nim'            => '2015070313',
					'nama_mahasiswa' => 'joana christy',
					'created_at'     => date("Y-m-d H:i:s"),
					'created_by'     => 'Admin'
				],
		];

		// Using Query Builder
		$this->db->table('ms_mahasiswa')->insertBatch($data);
	}
}
