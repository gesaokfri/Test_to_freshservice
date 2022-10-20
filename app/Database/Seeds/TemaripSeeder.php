<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class TemaripSeeder extends Seeder
{
	public function run()
	{
		$data = [
			[
				'id_tema_rip'          => 1,
				'id_fakultas' => 0,
				'nama_tema'   => 'Lainnya',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 2,
				'id_fakultas' => 4,
				'nama_tema'   => 'Eksplorasi Mikrob Tropis',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 3,
				'id_fakultas' => 4,
				'nama_tema'   => 'Enzim dan Senyawa Bioaktif',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 4,
				'id_fakultas' => 4,
				'nama_tema'   => 'Produk Pangan Fermentasi ',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 5,
				'id_fakultas' => 4,
				'nama_tema'   => 'Inovasi Produk Pangan Fungsional',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 6,
				'id_fakultas' => 8,
				'nama_tema'   => 'Geriatri dan Gerontologi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 7,
				'id_fakultas' => 8,
				'nama_tema'   => 'Adiksi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 8,
				'id_fakultas' => 8,
				'nama_tema'   => 'Infeksi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 9,
				'id_fakultas' => 8,
				'nama_tema'   => 'Personalisasi Pengobatan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 10,
				'id_fakultas' => 2,
				'nama_tema'   => 'Penanganan Anak Jalanan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 11,
				'id_fakultas' => 5,
				'nama_tema'   => 'Perlindungan Hukum atas Lingkungan Hidup, Bencana Alam',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 12,
				'id_fakultas' => 5,
				'nama_tema'   => 'Hukum Kontrak di Regional ASEAN',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 13,
				'id_fakultas' => 5,
				'nama_tema'   => 'Hak Asasi Manusia dan Perlindungan Saksi dan Korban',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 14,
				'id_fakultas' => 5,
				'nama_tema'   => 'Hukum Narkotika dan Hukum Kesehatan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 15,
				'id_fakultas' => 5,
				'nama_tema'   => 'Persaingan Tidak Sehat dan Perlindungan Konsumen dalam Digital Market dan Kewirausahaan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 16,
				'id_fakultas' => 1,
				'nama_tema'   => 'Pengembangan Model Kurikulum Pendidikan Bahasa Inggris',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 17,
				'id_fakultas' => 1,
				'nama_tema'   => 'Pengkajian In-house Teaching',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 18,
				'id_fakultas' => 1,
				'nama_tema'   => 'Kualitas Pembelajaran dan Kompetensi Guru',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 19,
				'id_fakultas' => 1,
				'nama_tema'   => 'Integrasi Pendidikan Karakter dan Pembelajaran',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 20,
				'id_fakultas' => 1,
				'nama_tema'   => 'Resiliensi Karyawan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 21,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian tentang Identifikasi Kondisi Fundamental Perbankan Berbasis Risiko, Kaitannya dengan Penetapan Premi Risiko di Sektor Perbankan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 22,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian tentang Perbandingan Sifat Prosiklikalitas Credit Supply Bank Asing dan Bank Domestik serta Kaitannya dengan Kebijakan Makroprudensial',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 23,
				'id_fakultas' => 3,
				'nama_tema'   => 'Pengembangan Risk Modeling untuk Pengukuran Risiko Keuangan dan Perbankan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 24,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian Mengenai Desain Sistem untuk Menghasilkan Informasi Akuntansi yang Berkualitas',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 25,
				'id_fakultas' => 6,
				'nama_tema'   => 'Perancangan Material Fungsional',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 26,
				'id_fakultas' => 3,
				'nama_tema'   => 'Aplikasi Internet 0f Things Sebagai Infrastruktur Kehidupan Modern',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 27,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian Mengenai Sistem Pengendalian Manajemen dan Kinerja Perusahaan, Peran Akuntan dalam Era Teknologi Informasi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 28,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian Tentang Transparansi Informasi dan Pertanggungjawaban Akuntansi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 29,
				'id_fakultas' => 3,
				'nama_tema'   => 'Hubungan Tata Kelola Perusahaan dengan Manajemen Laba, Kecurangan dan Kualitas Laporan Keuangan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 30,
				'id_fakultas' => 3,
				'nama_tema'   => 'Peran Akuntansi dalam Peningkatan Kesejahteraan Sosial',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 31,
				'id_fakultas' => 3,
				'nama_tema'   => 'Manajemen Pajak dan Tata Cara Peradilan Pajak dalam Lingkup Nasional Maupun Internasional',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 32,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian Mengenai Manajemen Perencanaan Pembangunan Ekonomi pada Kawasan Perbatasan Antara Negara',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 33,
				'id_fakultas' => 3,
				'nama_tema'   => 'Kajian-Kajian tentang Penilaian Risiko Perbankan dalam Era Integrasi Ekonomi dan Keuangan, Kebijakan Mikroprudensial dan Makroprudensial dalam Rangka Stabilitas Sistem Perbankan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 34,
				'id_fakultas' => 6,
				'nama_tema'   => 'Perancangan Material Fungsional',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 35,
				'id_fakultas' => 6,
				'nama_tema'   => 'Intelligent transportation system',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 36,
				'id_fakultas' => 6,
				'nama_tema'   => 'Eco‐friendly & micro‐air vehicles',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 37,
				'id_fakultas' => 6,
				'nama_tema'   => 'Perancangan robot',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 38,
				'id_fakultas' => 6,
				'nama_tema'   => 'Wireless sensor network (WSN)',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 39,
				'id_fakultas' => 6,
				'nama_tema'   => 'Highly reliable WSN',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 40,
				'id_fakultas' => 6,
				'nama_tema'   => 'Aplikasi& database WSN',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 41,
				'id_fakultas' => 6,
				'nama_tema'   => 'Smart transportation system, Smarter natural disaster management, Smart grid, Embedded systems utk consumer &industri',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 42,
				'id_fakultas' => 6,
				'nama_tema'   => 'Lainnya',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 43,
				'id_fakultas' => 2,
				'nama_tema'   => 'Kesehatan mental komunitas dan budaya',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 44,
				'id_fakultas' => 2,
				'nama_tema'   => 'Psikologi lintas budaya yg mencakup tema kesehatan ',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 45,
				'id_fakultas' => 2,
				'nama_tema'   => 'Kajian psikososial dlm penanganan masalah masyarakat marjinal',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 46,
				'id_fakultas' => 5,
				'nama_tema'   => 'HTN: Penyesuaian produk perundang-undangan dalam menghadapi revolusi industri 4.0',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 47,
				'id_fakultas' => 5,
				'nama_tema'   => 'HI: Perkembangan dan penerapan Hukum Internasional di Kawasan Asia Tenggara',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 48,
				'id_fakultas' => 5,
				'nama_tema'   => 'HK. PERDATA: Perkembangan hukum perdata dalam menyikapi perubahan dinamika masyarakat dalam ranah Hukum Benda, Hukum Keluarga dan Hukum Perjanjian.erkembangan hukum perdata dalam menyikapi perubahan dinamika masyarakat',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 49,
				'id_fakultas' => 5,
				'nama_tema'   => 'Hk Pidana: Penanggulangan tindak pidana narkotika, terorisme, perdagangan orang dan korupsi secara penal dan non penal',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 50,
				'id_fakultas' => 5,
				'nama_tema'   => 'Hukum Ekonomi Bisnis di era Digital dan Revolusi Industri 4.0',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 51,
				'id_fakultas' => 5,
				'nama_tema'   => 'Perlindungan Data Pribadi',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 52,
				'id_fakultas' => 5,
				'nama_tema'   => 'Cyber Law',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 53,
				'id_fakultas' => 5,
				'nama_tema'   => 'Persaingan Usaha',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 54,
				'id_fakultas' => 7,
				'nama_tema'   => 'Sustainability',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 55,
				'id_fakultas' => 5,
				'nama_tema'   => 'Perlindungan anak dan perempuan',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 56,
				'id_fakultas' => 6,
				'nama_tema'   => 'Data sains',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 57,
				'id_fakultas' => 2,
				'nama_tema'   => 'Kajian mengenai psikologi lintas budaya yang mencakup tema kepemimpinan, kesehatan, komunikasi ‐Kajian psikososial dalam penanganan masalah HIV/AID, anak jalanan, kebhinnekaan, masyarakat marjinal',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 58,
				'id_fakultas' => 2,
				'nama_tema'   => 'Kajian mengenai individu maupun sistem yang melingkupi individu seperti kurikulum, sekolah, keluarga',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 59,
				'id_fakultas' => 2,
				'nama_tema'   => 'Kajian mengenai pengukuran psikologis',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
			[
				'id_tema_rip'          => 60,
				'id_fakultas' => 8,
				'nama_tema'   => 'Lainnya',
				'created_at'  => date("Y-m-d H:i:s"),
				'created_by'  => 'Admin'
			],
		];

		// Using Query Builder
		$this->db->table('ms_tema_rip')->insertBatch($data);
	}
}
