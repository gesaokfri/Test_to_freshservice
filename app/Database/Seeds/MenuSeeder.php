<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
	public function run()
	{
		$data = array(
			array(
				'id_menu'    => 1,
				'reference'  => NULL,
				'level_menu' => 1,
				'menu'       => 'Beranda',
				'urutan'     => 1,
				'url_menu'   => '/',
				'icon_menu'  => 'bx bx-home-circle'
			),
			array(
				'id_menu'    => 2,
				'reference'  => NULL,
				'level_menu' => 1,
				'menu'       => 'Master Data',
				'urutan'     => 2,
				'url_menu'   => NULL,
				'icon_menu'  => 'bx bx-file'
			),
			array(
				'id_menu'    => 3,
				'reference'  => 2,
				'level_menu' => 2,
				'menu'       => 'User Role',
				'urutan'     => 1,
				'url_menu'   => 'user_role',
				'icon_menu'  => NULL
			),
			array(
				'id_menu'    => 4,
				'reference'  => 2,
				'level_menu' => 2,
				'menu'       => 'Dosen',
				'urutan'     => 2,
				'url_menu'   => 'dosen',
				'icon_menu'  => NULL
			),
			array(
				'id_menu'    => 5,
				'reference'  => 2,
				'level_menu' => 2,
				'menu'       => 'Mahasiswa',
				'urutan'     => 3,
				'url_menu'   => 'mahasiswa',
				'icon_menu'  => NULL
			),
			array(
				'id_menu'    => 6,
				'reference'  => 2,
				'level_menu' => 2,
				'menu'       => 'Eksternal',
				'urutan'     => 4,
				'url_menu'   => 'eksternal',
				'icon_menu'  => NULL
			),
			array(
				'id_menu'    => 7,
				'reference'  => 2,
				'level_menu' => 2,
				'menu'       => 'Bidang Penelitian',
				'urutan'     => 6,
				'url_menu'   => 'bidang_penelitian',
				'icon_menu'  => NULL
			),
			array(
				'id_menu'    => 8,
				'reference'  => NULL,
				'level_menu' => 1,
				'menu'       => 'Pengajaran',
				'urutan'     => 4,
				'url_menu'   => NULL,
				'icon_menu'  => 'bx bx-chalkboard'
			),
			array(
				'id_menu'    => 9,
				'reference'  => NULL,
				'level_menu' => 1,
				'menu'       => 'Penelitian',
				'urutan'     => 5,
				'url_menu'   => NULL,
				'icon_menu'  => 'bx bx-test-tube'
			),
			array(
				'id_menu'    => 10,
				'reference'  => NULL,
				'level_menu' => 1,
				'menu'       => 'Pengabdian',
				'urutan'     => 6,
				'url_menu'   => NULL,
				'icon_menu'  => 'bx bx-heart'
			),
			// start pengajaran
				array(
					'id_menu'    => 11,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Perkuliahan',
					'urutan'     => 1,
					'url_menu'   => 'pengajaran_perkul',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 12,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Bimbingan Seminar Mahasiswa',
					'urutan'     => 2,
					'url_menu'   => 'pengajaran_bimsemhs',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 13,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Bimbingan KKN',
					'urutan'     => 3,
					'url_menu'   => 'pengajaran_bimkkn',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 14,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Pengembangan Program Kuliah',
					'urutan'     => 4,
					'url_menu'   => 'pengajaran_bangprokul',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 15,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Bimbingan Skripsi/Tesis/Disertasi',
					'urutan'     => 5,
					'url_menu'   => 'pengajaran_bimstd',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 16,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Pengujian Mahasiswa',
					'urutan'     => 6,
					'url_menu'   => 'pengajaran_ujimhs',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 17,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Pengembangan Bahan Ajar',
					'urutan'     => 7,
					'url_menu'   => 'pengajaran_bangbajar',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 18,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Bimbingan Mahasiswa',
					'urutan'     => 8,
					'url_menu'   => 'pengajaran_bimmhs',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 19,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Detasering/Pencangkokan',
					'urutan'     => 9,
					'url_menu'   => 'pengajaran_detasering',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 20,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Orasi Ilmiah',
					'urutan'     => 10,
					'url_menu'   => 'pengajaran_orasi',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 21,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Bimbingan Dosen',
					'urutan'     => 11,
					'url_menu'   => 'pengajaran_bimdos',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 22,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Pengembangan Diri',
					'urutan'     => 12,
					'url_menu'   => 'pengajaran_bangdiri',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 23,
					'reference'  => 8,
					'level_menu' => 2,
					'menu'       => 'Sertifikasi Profesi',
					'urutan'     => 13,
					'url_menu'   => 'pengajaran_sertipro',
					'icon_menu'  => NULL
				),
			// end pengajaran
			// start penelitian
				array(
					'id_menu'    => 24,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Proposal',
					'urutan'     => 1,
					'url_menu'   => 'penelitian_proposal',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 25,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Mandiri',
					'urutan'     => 2,
					'url_menu'   => 'penelitian_mandiri',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 26,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Publikasi Karya',
					'urutan'     => 3,
					'url_menu'   => 'penelitian_publikar',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 27,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Diseminasi',
					'urutan'     => 4,
					'url_menu'   => 'penelitian_diseminasi',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 28,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Kekayaan Intelektual',
					'urutan'     => 5,
					'url_menu'   => 'penelitian_kekayaanint',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 29,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Menerjemah Buku Intelektual',
					'urutan'     => 6,
					'url_menu'   => 'penelitian_menerjemahbi',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 30,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Menyunting Karya Ilmiah',
					'urutan'     => 7,
					'url_menu'   => 'penelitian_suntingki',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 31,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Karya Inovatif',
					'urutan'     => 8,
					'url_menu'   => 'penelitian_karinova',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 32,
					'reference'  => 9,
					'level_menu' => 2,
					'menu'       => 'Rancangan & Karya Teknologi',
					'urutan'     => 9,
					'url_menu'   => 'penelitian_rankartek',
					'icon_menu'  => NULL
				),
			// end penelitian
			// start pengabdian
				array(
					'id_menu'    => 33,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Proposal',
					'urutan'     => 1,
					'url_menu'   => 'pengabdian_proposal',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 34,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Mandiri',
					'urutan'     => 2,
					'url_menu'   => 'pengabdian_mandiri',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 35,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Pengelolaan Jurnal Ilmiah',
					'urutan'     => 3,
					'url_menu'   => 'pengabdian_pengjuril',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 36,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Publikasi Pengabdian',
					'urutan'     => 4,
					'url_menu'   => 'pengabdian_publikasi',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 37,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Jabatan Pimpinan Lembaga',
					'urutan'     => 5,
					'url_menu'   => 'pengabdian_jabatan',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 38,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Pengembangan Hasil Pendidikan',
					'urutan'     => 6,
					'url_menu'   => 'pengabdian_bangdikpen',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 39,
					'reference'  => 10,
					'level_menu' => 2,
					'menu'       => 'Pelayanan Masyarakat',
					'urutan'     => 7,
					'url_menu'   => 'pengabdian_pelayanan',
					'icon_menu'  => NULL
				),
			// end pengabdian
			// start master data
				array(
					'id_menu'    => 40,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Fakultas',
					'urutan'     => 9,
					'url_menu'   => 'fakultas',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 41,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Bidang Keilmuan',
					'urutan'     => 10,
					'url_menu'   => 'bidang_keilmuan',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 42,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Prodi',
					'urutan'     => 11,
					'url_menu'   => 'prodi',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 43,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Mata Kuliah',
					'urutan'     => 12,
					'url_menu'   => 'matkul',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 44,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Co-Instructor',
					'urutan'     => 5,
					'url_menu'   => 'co_instructor',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 45,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Tujuan Sosial Ekonomi',
					'urutan'     => 7,
					'url_menu'   => 'tujuan_soseko',
					'icon_menu'  => NULL
				),
				array(
					'id_menu'    => 46,
					'reference'  => 2,
					'level_menu' => 2,
					'menu'       => 'Tema RIP',
					'urutan'     => 8,
					'url_menu'   => 'tema_rip',
					'icon_menu'  => NULL
				),
			//end master data
		);

		// Using Query Builder
		$this->db->table('ms_menu')->insertBatch($data);
	}
}
