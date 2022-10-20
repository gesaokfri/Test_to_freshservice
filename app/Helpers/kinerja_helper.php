<?php
use App\Models\Karyawan;

if(!function_exists("batas_min")) {
	function batas_min($nip, $type) {
		$karyawan = new Karyawan;

		$dosen = $karyawan->select('jabatan_akademik')->where('nip', $nip)->first();
		$jabatan = explode(" ",$dosen['jabatan_akademik']);
		// Persentase jika ingin IKT terhitung
		$persenTarget_iku_a = 100/100;
		$persenTarget_iku_b = 75/100;
		$persenTarget_iku_c = 75/100;
		$persenTarget_iku_d = 75/100;
		if($jabatan[0] == "Tenaga" && $jabatan[1] == "Pengajar") {
			// Tenaga Pengajar
			if($type == 'iku_a') {
				return $persenTarget_iku_a*3;
			} elseif($type == 'iku_b') {
				return $persenTarget_iku_b*3;
			} elseif($type == 'iku_c') {
				return $persenTarget_iku_c*3;
			} elseif($type == 'iku_d') {
				return $persenTarget_iku_d*2;
			}
		} elseif($jabatan[0] == "Asisten" && $jabatan[1] == "Ahli") {
			// Asisten Ahli
			if($type == 'iku_a') {
				return $persenTarget_iku_a*3;
			} elseif($type == 'iku_b') {
				return $persenTarget_iku_b*4;
			} elseif($type == 'iku_c') {
				return $persenTarget_iku_c*3;
			} elseif($type == 'iku_d') {
				return $persenTarget_iku_d*2;
			}
		} elseif($jabatan[0] == "Lektor" && $jabatan[1] == "Kepala") {
			// Lektor Kepala
			if($type == 'iku_a') {
				return $persenTarget_iku_a*6;
			} elseif($type == 'iku_b') {
				return $persenTarget_iku_b*6;
			} elseif($type == 'iku_c') {
				return $persenTarget_iku_c*3;
			} elseif($type == 'iku_d') {
				return $persenTarget_iku_d*2;
			}
		} elseif($jabatan[0] == "Lektor") {
			// Lektor
			if($type == 'iku_a') {
				return $persenTarget_iku_a*6;
			} elseif($type == 'iku_b') {
				return $persenTarget_iku_b*5;
			} elseif($type == 'iku_c') {
				return $persenTarget_iku_c*3;
			} elseif($type == 'iku_d') {
				return $persenTarget_iku_d*2;
			}
		} elseif($jabatan[0] == "Guru") {
			// Guru
			if($type == 'iku_a') {
				return $persenTarget_iku_a*6;
			} elseif($type == 'iku_b') {
				return $persenTarget_iku_b*7;
			} elseif($type == 'iku_c') {
				return $persenTarget_iku_c*3;
			} elseif($type == 'iku_d') {
				return $persenTarget_iku_d*2;
			}
		}
	}
}

if(!function_exists("batas_max")) {
	function batas_max($type) {
		if($type == "iku_a") {
			return 10;
		} elseif($type == "ikt_a") {
			return 3;
		} elseif($type == "iku_b") {
			return 10;
		} elseif($type == "ikt_b") {
			return 3;
		} elseif($type == "iku_c") {
			return 6;
		} elseif($type == "ikt_c") {
			return 1;
		} elseif($type == "iku_d") {
			return 4;
		} elseif($type == "ikt_d") {
			return 3;

		}
	}
}