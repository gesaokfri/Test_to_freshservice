<?php 
use App\Models\AccountType;
use App\Models\Menu;
use App\Models\User;
use App\Models\Notification;
use App\Models\Karyawan;
use App\Models\Mahasiswa;
use App\Models\Matkul;
use App\Models\Fakultas;

use App\Models\Dashboard\LaporanBeasiswa\Beasiswa;

require 'app/ThirdParty/phpmailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


if(!function_exists('get_menu')) {
	function get_menu($id_menu, $type) {
		$menu = new Menu;
		$getMenu = $menu->select("reference, menu, url_menu")->where('id_menu', $id_menu)->first();
		$getParentMenu = $menu->select("menu")->where('id_menu', $getMenu['reference'])->first();

		switch ($type) {
			case 'link':
				return base_url($getMenu['url_menu']);
				break;
			case 'menu':
				return $getMenu['menu'];
				break;
			case 'parent':
				return $getParentMenu['menu'];
				break;
			default :
				return "menu tidak ditemukan";
				break;
		}
	}
}

if(!function_exists('diffMonth')) {
	function diffMonth($yearMonth1,$yearMonth2) { //example diffMonth('2022-01','2022-05');
		if(!empty($yearMonth1) && !empty($yearMonth2)) {
			$ts1 = strtotime($yearMonth1);
			$ts2 = strtotime($yearMonth2);

			$year1 = date('Y', $ts1);
			$year2 = date('Y', $ts2);

			$month1 = date('m', $ts1);
			$month2 = date('m', $ts2);

			$diff = (($year2 - $year1) * 12) + ($month2 - $month1) + 1;
			return $diff;
		} else {
			return "";
		}
	}
}

if(!function_exists('minplusDate')) {
	function minplusDate($val,$type,$format) { //example minplusDate('2021-11','-1 month','Y/m');
		if(!empty($val) && !empty($type) && !empty($format)) {
			$get_date = strtotime($val.' '.$type);   //example val : 2021-11 or 2021-11-01 
			$convert  = date($format, $get_date);
			return $convert;
		} else {
			return "";
		}
	}
}


if(!function_exists('check_expired_date')) {
	function check_expired_date($date, $captionExpired) {
		$today = date('Y-m-d');
		if($today > $date) {
			return $captionExpired;
		} else {
			return get_date_indonesia($date);
		}
	}
}

if(!function_exists('get_user_name')) {
	function get_user_name($id_user) {
		$user = new User;
		$getUser = $user->find($id_user);

		if (!empty($getUser)) {
			return $getUser['name'];
		} else {
			return "Administrator";
		}
	}
}

if(!function_exists('get_semester')) {
	function get_semester($tahun_semester) {
		if($tahun_semester == null) {
			$getSemester = "Tahun semester tidak valid";
		} else {
			$tahun = "20" . sprintf("%02d", substr($tahun_semester, 0, 2)) . "/" . "20" . sprintf("%02d", (substr($tahun_semester, 0, 2) + 1));
			if (substr($tahun_semester, 2) == '10') {
				$getSemester = "Ganjil " . $tahun;
			} elseif (substr($tahun_semester, 2) == '20') {
				$getSemester = "Genap " . $tahun;
			} elseif(substr($tahun_semester, 2) == '30') {
				$getSemester = "Antara " . $tahun;
			} else {
				$getSemester = "Kode semester tidak valid";
			}
		}

		return $getSemester;
	}
}

if(!function_exists('semester_to_tahun')) {
	function semester_to_tahun($tahun_semester) {
		$getDigitTahun = substr($tahun_semester, 2);
		if ($getDigitTahun == '10' || $getDigitTahun == '30') {
			$tahun = "20".sprintf("%02d", substr($tahun_semester, 0, 2));
		} elseif ($getDigitTahun == '20') {
			$tahun = "20".sprintf("%02d", (substr($tahun_semester, 0, 2) + 1));
		} else {
			$tahun = $tahun_semester." tidak valid";
		}

		return $tahun;
	}
}

if(!function_exists('tgl_to_semester')) {
	function tgl_to_semester($tgl) {
		$bulan = date('n', strtotime($tgl));
		$tahun = date('Y', strtotime($tgl));
		if ($bulan > 6) {
			$tahun_semester = sprintf("%02d", substr($tahun, 2)) . "10";
		} else {
			$tahun_semester = sprintf("%02d", (substr($tahun, 2) - 1)) . "20";
		}

		return $tahun_semester;
	}
}

if(!function_exists('tgl_to_tahun')) {
	function tgl_to_tahun($tgl) {
		$tahun = date('Y', strtotime($tgl));

		return $tahun;
	}
}

if(!function_exists('get_mahasiswa')) {
	function get_mahasiswa($nim, $type='') {
		$mahasiswa = new Mahasiswa;

		$getMahasiswa = $mahasiswa->select('nim, nama_mahasiswa')->where('nim', $nim)->first();

		if($type == 'akreditasi') {
			if($nim != '' || $nim != NULL) {
				if($getMahasiswa) {
					$lblMahasiswa = $getMahasiswa['nama_mahasiswa']." (".$getMahasiswa['nim'].")";
				} else {
					$lblMahasiswa = "Data mahasiswa dengan nim ".$nim." tidak ditemukan";
				}
			} else {
				$lblMahasiswa = "Nim kosong";
			}
		} else {
			if($nim != '' || $nim != NULL) {
				if($getMahasiswa) {
					$lblMahasiswa = $getMahasiswa['nim']." - ".$getMahasiswa['nama_mahasiswa'];
				} else {
					$lblMahasiswa = "Data mahasiswa dengan nim ".$nim." tidak ditemukan";
				}
			} else {
				$lblMahasiswa = "Nim kosong";
			}
		}

		return $lblMahasiswa;
	}
}

if (!function_exists('get_dosen')) {
	function get_dosen($nip, $type = '')
	{
		$karyawan = new Karyawan;

		$dosen = $karyawan->select('nip, gelar_depan, nama, gelar_belakang')->where('nip', $nip)->first();

		if ($type == 'akreditasi') {
			if ($nip != '' || $nip != NULL) {
				if ($dosen) {
					$lblDosen = $dosen['nama'];
				} else {
					$lblDosen = "Data dosen dengan nip " . $nip . " tidak ditemukan";
				}
			} else {
				$lblDosen = "Nip kosong";
			}
		} elseif ($type == 'nama_lengkap') {
			if ($nip != '' || $nip != NULL) {
				if ($dosen) {
					$lblDosen = $dosen['gelar_depan'] . " " . $dosen['nama'] . " " . $dosen['gelar_belakang'];
				} else {
					$lblDosen = "Data dosen dengan nip " . $nip . " tidak ditemukan";
				}
			} else {
				$lblDosen = "Nip kosong";
			}
		} else {
			if ($nip != '' || $nip != NULL) {
				if ($dosen) {
					$lblDosen = $dosen['nip'] . " - " . $dosen['gelar_depan'] . " " . $dosen['nama'] . " " . $dosen['gelar_belakang'];
				} else {
					$lblDosen = "Data dosen dengan nip " . $nip . " tidak ditemukan";
				}
			} else {
				$lblDosen = "Nip kosong";
			}
		}

		return $lblDosen;
	}
}

if(!function_exists('get_jabatan')) {
	function get_jabatan($nip) {
		$karyawan = new Karyawan;

		$dosen = $karyawan->select('jabatan_akademik')->where('nip', $nip)->first();
		$jabatan = explode(" ",$dosen['jabatan_akademik']);
		$jabatan_akademik = "";
		if($jabatan[0] == "Tenaga" && $jabatan[1] == "Pengajar") {
			$jabatan_akademik = $jabatan[0]." ".$jabatan[1];
		} elseif($jabatan[0] == "Asisten" && $jabatan[1] == "Ahli") {
			$jabatan_akademik = $jabatan[0]." ".$jabatan[1];
		} elseif($jabatan[0] == "Lektor" && $jabatan[1] == "Kepala") {
			$jabatan_akademik = $jabatan[0]." ".$jabatan[1];
		} elseif($jabatan[0] == "Lektor") {
			$jabatan_akademik = $jabatan[0];
		} elseif($jabatan[0] == "Guru" && $jabatan[1] == "Besar") {
			$jabatan_akademik = $jabatan[0]." ".$jabatan[1];
		}
		
		return $jabatan_akademik;
	}
}

if(!function_exists('get_matkul')) {
	function get_matkul($kode_matkul) {
		$matkul = new Matkul;

		$getMatkul = $matkul->select('kode_matkul, nama_matkul')->where('kode_matkul', $kode_matkul)->first();

		if($getMatkul) {
			$lblMatkul = $getMatkul['kode_matkul']." - ".$getMatkul['nama_matkul'];
		} else {
			if($kode_matkul) {
				$lblMatkul = "Data mata kuliah dengan kode ".$kode_matkul." tidak ditemukan";
			} else {
				$lblMatkul = null;
			}
		}

		return $lblMatkul;
	}
}

if(!function_exists('get_fakultas')) {
	function get_fakultas($kode_fakultas) {
		$fakultas = new Fakultas;

		$getFakultas = $fakultas->select('kode_fakultas, nama_fakultas')->where('kode_fakultas', $kode_fakultas)->first();

		if($getFakultas) {
			$lblFakultas = $getFakultas['kode_fakultas']." - ".$getFakultas['nama_fakultas'];
		} else {
			$lblFakultas = "Data fakultas dengan kode ".$kode_fakultas." tidak ditemukan";
		}

		return $lblFakultas;
	}
}

if(!function_exists('file_extension')) {
	function file_extension() {
		return [
			"jpg", 
			"jpeg",
			"JPG",
			"png",
			"gif",
			"xls",
			"xlsx",
			"ppt",
			"pptx",
			"doc",
			"docx",
			"txt",
			"pdf"
		];
	}
}

if(!function_exists('rencana_kerja_group')) {
	//example : rencana_kerja_group('1')
	function rencana_kerja_group($id=''){  
		$list = array("","Financial","Customer","Internal Process","Learning & Growth");
		if($id!=""){
			return $list[$id];
		}
		else {
			return $list;
		} 
	}
}

if(!function_exists('rencana_kerja_type')) {
	//example : rencana_kerja_type('1')
	function rencana_kerja_type($id=''){  
		$list = array("","Yayasan","Universitas","Rumah Sakit");
		if($id!=""){
			return $list[$id];
		}
		else {
			return $list;
		} 
	}
}

if(!function_exists('rencana_kerja_verify')) {
	function rencana_kerja_verify($id){  
		$list = array("Pending","Terverifikasi","Revisi");
		if($id!=""){
			return $list[$id];
		}
		else {
			return $list;
		} 
	}
}

if(!function_exists('get_quarter')) {
	function get_quarter(){  
		$curMonth = date("m", time());
		$curQuarter = ceil($curMonth/3);
		return "Q".$curQuarter;
	}
}

if(!function_exists('select_quarter')) {
	//example : select_quarter('1')
	function select_quarter($month){  
		$month_q1 = array("01","02","03","1","2","3");
		$month_q2 = array("04","05","06","4","5","6");
		$month_q3 = array("07","08","09","7","8","9");
		$month_q4 = array("10","11","12");
		if (in_array($month,$month_q1)){
	  	return "Q1";
	  }
	  else if (in_array($month,$month_q2)){
	  	return "Q2";
	  }
	  else if (in_array($month,$month_q3)){
	  	return "Q3";
	  }
	  else if (in_array($month,$month_q4)){
	  	return "Q4";
	  }
	}
}

if(!function_exists('month_quarter')) {
	//example : month_quarter('1') //Q1 or 1  
	function month_quarter($quarter){ 
		if ($quarter=="Q1" or $quarter=="1"){
	  	return array("01","02","03");
	  }
	  else if ($quarter=="Q2" or $quarter=="2"){
	  	return array("04","05","06");
	  }
	  else if ($quarter=="Q3" or $quarter=="3"){
	  	return array("07","08","09");
	  }
	  else if ($quarter=="Q4" or $quarter=="4"){
	  	return array("10","11","12");
	  }
	}
}

if (!function_exists('quarter_to_month')) {
	//example : quarter_to_month('1') //Q1 or 1
	function quarter_to_month($quarter)
	{
		if ($quarter == "Q1" or $quarter == "1") {
			return array("JAN", "FEB", "MAR");
		} else if ($quarter == "Q2" or $quarter == "2") {
			return array("APR", "MAY", "JUN");
		} else if ($quarter == "Q3" or $quarter == "3") {
			return array("JUL", "AUG", "SEP");
		} else if ($quarter == "Q4" or $quarter == "4") {
			return array("OCT", "NOV", "ADJ");
		}
	}
}

if (!function_exists('quarter_to_monthQ')) {
	//example : quarter_to_monthQ('1') //Q1 or 1
	function quarter_to_monthQ($quarter)
	{
		if ($quarter == "Q1" or $quarter == "1") {
			return array("JAN", "FEB", "MAR");
		} else if ($quarter == "Q2" or $quarter == "2") {
			return array("APR", "MAY", "JUN");
		} else if ($quarter == "Q3" or $quarter == "3") {
			return array("JUL", "AUG", "SEP");
		} else if ($quarter == "Q4" or $quarter == "4") {
			return array("OCT", "NOV", "DEC");
		}
	}
}

function getMonthNumber($monthStr) {
//e.g, $month='Jan' or 'January' or 'JAN' or 'JANUARY' or 'january' or 'jan'
$m = ucfirst(strtolower(trim($monthStr)));
switch ($m) {
    case "January":        
    case "Jan":
        $m = "01";
        break;
    case "February":
    case "Feb":
        $m = "02";
        break;
    case "March":
    case "Mar":
        $m = "03";
        break;
    case "April":
    case "Apr":
        $m = "04";
        break;
    case "May":
        $m = "05";
        break;
    case "June":
    case "Jun":
        $m = "06";
        break;
    case "July":        
    case "Jul":
        $m = "07";
        break;
    case "August":
    case "Aug":
        $m = "08";
        break;
    case "September":
    case "Sep":
        $m = "09";
        break;
    case "October":
    case "Oct":
        $m = "10";
        break;
    case "November":
    case "Nov":
        $m = "11";
        break;
    case "December":
    case "Dec":
        $m = "12";
        break;
	case "Adjustment":
	case "Adj":
		$m = "12";
		break;
    default:
        $m = false;
        break;
}
return $m;
}

function getMonthNumberCashflow($monthStr)
{
	//e.g, $month='Jan' or 'January' or 'JAN' or 'JANUARY' or 'january' or 'jan'
	$m = ucfirst(strtolower(trim($monthStr)));
	switch ($m) {
		case "January":
		case "Jan":
			$m = "01";
			break;
		case "February":
		case "Feb":
			$m = "02";
			break;
		case "March":
		case "Mar":
			$m = "03";
			break;
		case "April":
		case "Apr":
			$m = "04";
			break;
		case "May":
			$m = "05";
			break;
		case "June":
		case "Jun":
			$m = "06";
			break;
		case "July":
		case "Jul":
			$m = "07";
			break;
		case "August":
		case "Aug":
			$m = "08";
			break;
		case "September":
		case "Sep":
			$m = "09";
			break;
		case "October":
		case "Oct":
			$m = "10";
			break;
		case "November":
		case "Nov":
			$m = "11";
			break;
		case "December":
		case "Dec":
			$m = "12";
			break;
		case "Adjustment":
		case "Adj":
			$m = "13";
			break;
		default:
			$m = false;
			break;
	}
	return $m;
}

function getMonthStr($value, $type) {
	// $type = Type 1/2 (Abbreviation or Full text / Singkatan atau engga (In English)) 
	switch ($value) {
		case "1":        
		case "01":
			if($type == "1") {
				$value = "JAN";
			} else {
				$value = "January";
			}
			break;
		case "2":        
		case "02":
			if($type == "1") {
				$value = "FEB";
			} else {
				$value = "February";
			}
			break;
		case "3":        
		case "03":
			if($type == "1") {
				$value = "MAR";
			} else {
				$value = "March";
			}
			break;
		case "4":        
		case "04":
			if($type == "1") {
				$value = "APR";
			} else {
				$value = "April";
			}
			break;
		case "5":        
		case "05":
			if($type == "1") {
				$value = "MAY";
			} else {
				$value = "May";
			}
			break;
		case "6":        
		case "06":
			if($type == "1") {
				$value = "JUN";
			} else {
				$value = "June";
			}
			break;
		case "6":        
		case "06":
			if($type == "1") {
				$value = "JUN";
			} else {
				$value = "June";
			}
			break;
		case "7":        
		case "07":
			if($type == "1") {
				$value = "JUL";
			} else {
				$value = "July";
			}
			break;
		case "8":        
		case "08":
			if($type == "1") {
				$value = "AUG";
			} else {
				$value = "August";
			}
			break;
		case "9":        
		case "09":
			if($type == "1") {
				$value = "SEP";
			} else {
				$value = "September";
			}
			break;
		case "10":
			if($type == "1") {
				$value = "OCT";
			} else {
				$value = "October";
			}
			break;
		case "11":
			if($type == "1") {
				$value = "NOV";
			} else {
				$value = "November";
			}
			break;
		case "12":
			if($type == "1") {
				$value = "DEC";
			} else {
				$value = "December";
			}
			break;
	}
	return $value;
}

if(!function_exists('convert_to_kb')) {
	function convert_to_kb($value) {
		return ceil($value / 1024);
	}
}

if(!function_exists('site')) {
	function site(){
    return url('/')."/";
  }
}

if(!function_exists('apps_name')) {
	function apps_name(){
    return "Dashboard";
  }
}

if(!function_exists('array_push_assoc')) {
	function array_push_assoc($array, $key, $value){
    $array[$key] = $value;
    return $array;
  }
}

if(!function_exists('status_action')) {
	//example : status_action('1')
	function status_action($sts){  
		$status = array("Belum Mulai", "Mulai", "Selesai/Terlewati");
		return $status[$sts]; 
	}
}

if(!function_exists('date_default')) {
	//example : date_default('30/03/2018')
	function date_default($date){  	
		if(!empty($date)){
			$tgl = explode("/",$date);
			return $tgl[2]."-".$tgl[1]."-".$tgl[0];  // yyyy-mm-dd
		}	
		else {
			return null;
		}
	}
}

if(!function_exists('get_hari')) {
	//example : get_hari('2018-03-30')
	function get_hari($tanggal){  //Only format yyyy-mm-dd
		$day = date('D', strtotime($tanggal));
		$dayList = array(
			'Sun' => 'Minggu',
			'Mon' => 'Senin',
			'Tue' => 'Selasa',
			'Wed' => 'Rabu',
			'Thu' => 'Kamis',
			'Fri' => 'Jumat',
			'Sat' => 'Sabtu'
		);
		return $dayList[$day]; //output data => Day Name
	}
}

if(!function_exists('anggaran_type')) {
	//example : anggaran_type('1')
	function anggaran_type($data){ 
		$anggaran = array("","Pendapatan","Beban","Gedung & CIP","Peralatan & Prasarana","Tanah");
		return $anggaran[$data];
	}
}

if(!function_exists('get_date_indonesia')) {
	//example : get_date_indonesia('2018-03-30','all')
	function get_date_indonesia($date,$jns=''){   //Only format yyyy-mm-dd  
		if($date == null || $date == '') {
			return "Tanggal tidak ditemukan";
		} else {
			$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		  
			$tahun = substr($date, 0, 4);
			$bulan = substr($date, 5, 2);
			$tgl   = substr($date, 8, 2);
			$jam   = substr($date, 11, 2);
			$menit = substr($date, 14, 2);
			$detik = substr($date, 17, 2);
				
			if($jns=="all"){
				$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun." ".$jam.":".$menit.":".$detik;  
				// output data =>  dd, month name yyyy hour:minute:second
			}
			else if($jns=="tgl_indo"){
				$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;  
				// output data =>  dd, month name yyyy 
			}
			else if($jns=="month"){
				$result = $BulanIndo[(int)$bulan-1];   // output data => month name
			}
			else if($jns=="month_year"){
				$result = $BulanIndo[(int)$bulan-1] . " ". $tahun; // output data=> month name  yyyy
			}
			else {
				$newDate = date("d/m/Y", strtotime($date));
				if($date==null or $date==""){
					return "";
				}
				else {
					return $newDate;  // output data =>  dd/mm/yyyy
				}
			}
			return($result);
		}
	}
}

if(!function_exists('insert_datetime')) {
	//example : insert_datetime('30/03/2018')
	function insert_datetime($date){   //Only format dd-mm-yyyy
		if($date) {
			$tgl = substr($date, 0, 2);
			$bulan = substr($date, 3, 2);
			$tahun   = substr($date, 6, 4);
			
			$result = $tahun."-".$bulan."-".$tgl." ".date('H:i:s');
			
			return($result);
		} else {
			return "";
		}
	}
}

if(!function_exists('show_datetime')) {
	//example : show_datetime('2018-03-30 12:11:30')
	function show_datetime($date){   //Only format dd-mm-yyyy
  
    $tgl = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tahun   = substr($date, 8, 2);
		
		$result = $tahun."/".$bulan."/".$tgl;
		
	  return($result);
	}
}

if(!function_exists('fakultas')) {
	//example : fakultas('FP')
	function fakultas($data=''){   
		$array=array("FP"=>"Fakultas Psikologi","FT"=>"Fakultas Teknik","FH"=>"Fakultas Hukum",
					 "FK"=>"Fakultas Kesehatan","FTB"=>"Fakultas Teknobiologi",
					 "FPB"=>"Fakultas Pendidikan dan Bahasa","FE"=>"Fakultas Ekonomi",
					 "FIABIKOM"=>"Fakultas Ilmu Administrasi Bisnis dan Ilmu Komunikasi"
					);
	  if(!empty($data)){
	  	$result = $array[$data];
	  	return($result);
	  }
	  else {
	  	return $array;
	  }
	  
	}
}

if(!function_exists('bulan_indo')) {
	//example : bulan_indo('01')
	function bulan_indo($month){   //Only format mm
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
		
		$result = $BulanIndo[(int)$month-1];
		
	  return($result);
	}
}

if(!function_exists('get_date_english')) {
	//example : get_date_indonesia('2018-03-30','all')
	function get_date_english($date,$jns=''){   //Only format yyyy-mm-dd  
    $BulanEng = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
  
    $tahun = substr($date, 0, 4);
    $bulan = substr($date, 5, 2);
    $tgl   = substr($date, 8, 2);
		
		if($jns=="all"){
			$result = $tgl . " " . $BulanEng[(int)$bulan-1] . " ". $tahun;  
			// output data =>  dd, month name yyyy
		}
		else if($jns=="month"){
			$result = $BulanEng[(int)$bulan-1];   // output data => month name
		}
		else if($jns=="month_year"){
			$result = $BulanEng[(int)$bulan-1] . " ". $tahun; // output data=> month name  yyyy
		}
		else {
			$newDate = date("d/m/Y", strtotime($date));
			if($date==null or $date==""){
				return "";
			}
			else {
				return $newDate;  // output data =>  dd/mm/yyyy
			}
		}
    return($result);
	}
}

if(!function_exists('time_elapsed_string')) {
	function time_elapsed_string($datetime, $lang=false,$full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    if(empty($lang)){
      $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
      );	

      $ago  = " yang lalu";
      $just = " saat ini";
      $ss   =  "";
    }
    else if($lang=="en"){
      $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
      );

      $ago  = " ago";
      $just = " just now";
      $ss   =  "s";
    }
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? $ss : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . $ago : $just;
	}
}

if(!function_exists('get_day_hours')) {
	function get_day_hours($seconds,$tipe){
    $string = "";
    if($tipe=="hari"){
      $days = intval(intval($seconds) / (3600*24));
      if($days> 0){
          $string = $days;
      }
    }
    else if($tipe=="jam"){
      $hours = (intval($seconds) / 3600) % 24;
      if($hours > 0){
          $string = $hours;
      }
    }
    else if($tipe=="menit"){
      $minutes = (intval($seconds) / 60) % 60;
      if($minutes==0){
          $string = 1;
      }
      else {
        $string = $minutes;
      }
    }
    else if($tipe=="detik"){
      $seconds = (intval($seconds)) % 60;
      if($seconds > 0){
          $string = $seconds;
      }
    }
    return $string;
	}
}

if(!function_exists('title')) {
	function title(){
    return " | Sipol &bull; E-Procurement | Antara";
  }
}

if(!function_exists('int_to_rp')) {
	function int_to_rp($var){  
		return number_format($var,0,".",".");
	}
}

if(!function_exists('rp_to_int')) {
	function rp_to_int($var){  
    $var_result =  str_replace(".","",$var);
    return str_replace(",","",$var_result);
	}
}

if(!function_exists('send_notif')) {
	function send_notif($data){
		if($data){
			$req  = json_decode(json_encode($data));
			$ntf                    = new Notification;
			$data = [
				"id_notification"   => $req->generate_id,
				"to_user"           => $req->to_user,
				"type_notification" => $req->tipe,
				"id_data"           => $req->id_data,
				"judul"             => $req->judul,
				"pesan"             => $req->pesan,
				"url"               => $req->url,
				"created_by"        => $req->id_user,
				"updated_by"        => $req->id_user
			];
      $save = $ntf->save($data); 
		}
  }
}

if(!function_exists('get_notification')) {
	function get_notification($id_user, $not_read='') { 
		$notif = new Notification;
		$data = $notif->where('to_user', $id_user);
		if(!empty($not_read)){
			$data = $data->where('is_read','0');
		}		
		$data =	$data->orderBy('is_read','asc')->orderBy('created_at','desc')->get()->getResult();	
		return $data;
	}
}

if(!function_exists('create_link')) {
	function create_link($source){
		$string = pathinfo($source);
		$fileExt = $string['extension'];
		$lower = strtolower($string['filename']);	
		$lower = str_replace(' ', '-', $lower); // Replaces all spaces with hyphens.
		$lower = preg_replace('/[^A-Za-z0-9\-]/', '', $lower); // Removes special chars.
		$fileName = preg_replace('/-+/', '-', $lower);
		if($fileName != '' || $fileName != NULL) {
			$fullName = $fileName.".".$fileExt;
		} else {
			$fullName = date("Ymdhis").".".$fileExt;
		}
		return $fullName; // Replaces multiple hyphens with single one.
	}
}

if(!function_exists('generateRandomString')) {
	function generateRandomString($length = 10) {
		$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

if(!function_exists('generateRandomStringBig')) {
	function generateRandomStringBig($length = 10) {
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$charactersLength = strlen($characters);
		$randomString = '';
		for ($i = 0; $i < $length; $i++) {
			$randomString .= $characters[rand(0, $charactersLength - 1)];
		}
		return $randomString;
	}
}

if(!function_exists('send_mail')) {
	function send_mail($data){
		if($data){
			$req = json_decode(json_encode($data));
			$from_email_smtp     = "smtp.office365.com";
			$from_email_port     = "587";
			$from_email_account  = "sekata-noreply@atmajaya.ac.id";	
			$from_email_password = "p4u5b1rUL4ut";
			$from_email_name     = apps_name()." (Don't Reply This Message)";
			$title_success       = "Berhasil";
			$title_failed        = "Terjadi Kesalahan";
			$text_success        = "Email berhasil terkirim";

			if( (!empty($req->from_email) && !empty($req->pwd_from_email)) && !empty($req->port_from_email) )  {
				if(!empty($req->smtp_from_email)){
					$from_email_account   = $req->from_email;
					$from_email_password  = $req->pwd_from_email;
					$from_email_port      = $req->port_from_email;
					$from_email_smtp      = $req->smtp_from_email;
				}
			}

			if(!empty($req->name_from_email))  {
				$from_email_name  = $req->name_from_email;
			}
			if(!empty($req->title_success))  {
				$title_success  = $req->title_success;
			}
			if(!empty($req->title_failed))  {
				$title_failed  = $req->title_failed;
			}
			if(!empty($req->text_success))  {
				$text_success  = $req->text_success;
			}
			
			$to_email = $req->to_email;
		  $mail 		= new PHPMailer;

		  // Konfigurasi SMTP
			$mail->isSMTP();
			$mail->Host       = $from_email_smtp;
			$mail->SMTPAuth   = true;
			$mail->Username   = $from_email_account;
			$mail->Password   = $from_email_password;
			$mail->Port       = $from_email_port;
			$mail->SMTPSecure = 'tls';

			$mail->setFrom($from_email_account,$from_email_name);
			$mail->addReplyTo($from_email_account,$from_email_name); //For History
			$mail->addAddress($to_email);
			$mail->isHTML(true);
			$mail->Subject       = $req->subject;
			$mail->Body          = $req->konten;
			if(!empty($req->attachment)) {
				$mail->addAttachment($req->attachment);
			}
			$res['title']    = "";
			$res['message']  = "";
			$res['type']     = "";
			if(!$mail->send()){
				$res['title']    = $title_failed;
				$res['message']  = $mail->ErrorInfo;
				$res['type'] 	   = "error";
				$res['status'] 	 = "NOK";
			}else{
				$res['title']    = $title_success;
				$res['message']	 = $text_success;
				$res['type'] 	   = "success";
				$res['status'] 	 = "OK";
			 }
			return json_encode($res);
		}
	}
}

if(!function_exists("universitas_menu")) {
		function universitas_menu() {
			$menu = new Menu();

			$result = "<ul class='metismenu list-unstyled' id='side-menu'>";
			
			$level1 = $menu->where("level_menu", 1)
			->where("is_displayed", "1")
			->whereIn('type_menu', ['0','1'])
			->orderBy("urutan", "ASC")
			->get()
			->getResult();
			
			foreach($level1 as $lv1) {
				$level2 = $menu->where("level_menu", 2)
				->where("is_displayed", "1")
				->where("reference", $lv1->id_menu)
				->whereIn('type_menu', ['0','1'])
				->orderBy("urutan", "ASC")
				->get()
				->getResult();
				if(count($level2) > 0) {
						$result .= "<li>
						<a href='javascript:void(0)' class='waves-effect has-arrow'>
							<i class='".$lv1->icon_menu."'></i>
							<span key='t-dashboards'>".$lv1->menu."</span>
						</a>
						<ul class='sub-menu' aria-expanded='true'>";
						foreach($level2 as $lv2) {
							if(acc_read(session('level_id'), $lv2->id_menu) == 1) {
						  		$level3= $menu->where("level_menu", 3)
								->where("is_displayed", "1")
								->where("reference", $lv2->id_menu)
								->whereIn('type_menu', ['0','1'])
								->orderBy("urutan", "ASC")
								->get()
								->getResult();	
						  		if(count($level3) > 0) {
						  				$result .= "<li>
										<a href='javascript:void(0)' class='waves-effect has-arrow'>
											<span key='t-dashboards'>".$lv2->menu."</span>
										</a>
										<ul class='sub-menu' aria-expanded='true'>";
										foreach($level3 as $lv3) {
											$result .= "<li>
								              <a href='".base_url($lv3->url_menu)."'>
								                ".$lv3->menu."
								              </a>
								            </li>";	
										}
										$result .="</ul>";
						  		}
						  		else {
						  			$result .= "<li>
						              <a href='".base_url($lv2->url_menu)."'>
						                ".$lv2->menu."
						              </a>
						            </li>";
						  		}
			        	    }
						}
						$result .= "</ul>";
				} else {
						$result .= "<li>
										<a href='".base_url($lv1->url_menu)."' class='waves-effect'>
											<i class='".$lv1->icon_menu."'></i>
											<span key='t-dashboards'>".$lv1->menu."</span>
										</a>
									</li>";
				}
				
			}
			$result .= "</ul>";
			echo $result;
		}
}

if(!function_exists("yayasan_menu")) {
		function yayasan_menu() {
			$menu = new Menu();

			$result = "<ul class='metismenu list-unstyled' id='side-menu'>";
			
			$level1 = $menu->where("level_menu", 1)
			->where("is_displayed", "1")
			->orderBy("urutan", "ASC")
			->whereIn('type_menu', ['0','2'])
			->get()
			->getResult();
			
			foreach($level1 as $lv1) {
				$level2 = $menu->where("level_menu", 2)
				->where("is_displayed", "1")
				->where("reference", $lv1->id_menu)
				->whereIn('type_menu', ['0','2'])
				->orderBy("urutan", "ASC")
				->get()
				->getResult();
				if(count($level2) > 0) {
						$result .= "<li>
						<a href='javascript:void(0)' class='waves-effect has-arrow'>
							<i class='".$lv1->icon_menu."'></i>
							<span key='t-dashboards'>".$lv1->menu."</span>
						</a>
						<ul class='sub-menu' aria-expanded='true'>";
						foreach($level2 as $lv2) {
							if(acc_read(session('level_id'), $lv2->id_menu) == 1) {
						  		$level3= $menu->where("level_menu", 3)
								->where("is_displayed", "1")
								->where("reference", $lv2->id_menu)
								->whereIn('type_menu', ['0','2'])
								->orderBy("urutan", "ASC")
								->get()
								->getResult();	
						  		if(count($level3) > 0) {
						  				$result .= "<li>
										<a href='javascript:void(0)' class='waves-effect has-arrow'>
											<span key='t-dashboards'>".$lv2->menu."</span>
										</a>
										<ul class='sub-menu' aria-expanded='true'>";
										foreach($level3 as $lv3) {
											$result .= "<li>
								              <a href='".base_url($lv3->url_menu)."'>
								                ".$lv3->menu."
								              </a>
								            </li>";	
										}
										$result .="</ul>";
						  		}
						  		else {
						  			$result .= "<li>
						              <a href='".base_url($lv2->url_menu)."'>
						                ".$lv2->menu."
						              </a>
						            </li>";
						  		}
			        	    }
						}
						$result .= "</ul>";
				} else {
						$result .= "<li>
										<a href='".base_url($lv1->url_menu)."' class='waves-effect'>
											<i class='".$lv1->icon_menu."'></i>
											<span key='t-dashboards'>".$lv1->menu."</span>
										</a>
									</li>";
				}
				
			}
			$result .= "</ul>";
			echo $result;
		}
}

if(!function_exists('is_selected')) {
		function is_selected($val1, $val2) {
			$isSelected = false;
			if($val1 == $val2) {
				$isSelected = true;
			}
			return $isSelected;
		}
}

//Start Acces Data
if(!function_exists('acc_create')) {
		function acc_create($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_add']) ? $res['acc_add'] : '0');
		}
}

if(!function_exists('acc_read')) {
		function acc_read($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_read']) ? $res['acc_read'] : '0');
		}
}

if(!function_exists('acc_update')) {
		function acc_update($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_edit']) ? $res['acc_edit'] : '0');
		}
}

if(!function_exists('acc_delete')) {
		function acc_delete($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_delete']) ? $res['acc_delete'] : '0');
		}
}

if(!function_exists('acc_download')) {
		function acc_download($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_download']) ? $res['acc_download'] : '0');
		}
}

if(!function_exists('acc_upload')) {
		function acc_upload($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_upload']) ? $res['acc_upload'] : '0');
		}
}

if(!function_exists('acc_upload')) {
		function ima($data_1,$data_2){  
			$res = New AccountType();
			$res = $res->where('id_account_type',$data_1)->where('id_menu',$data_2)->first();
			return (!empty($res['acc_upload']) ? $res['acc_upload'] : '0');
		}
}
	//End Acces Data
if(!function_exists('get_selected_dosen')) {
	function get_selected_dosen($id, $type=''){  
		$dosen = new Karyawan;
		$get = $dosen->where("nip", $id)->first();

		if($type == 'key') {
			echo $get['nip']." - ".$get['gelar_depan']." ".$get['nama']." ".$get['gelar_belakang'];
		} elseif($type == 'val') {
			echo $get['nip'];
		} else {
			echo "<option value='".$get['nip']."' selected>".$get['nip']." - ".$get['gelar_depan']." ".$get['nama']." ".$get['gelar_belakang']."</option>";	
		}

	}
}

if(!function_exists('get_selected_mahasiswa')) {
	function get_selected_mahasiswa($id, $type=''){  
		$mhs = new Mahasiswa;
		$get = $mhs->where("nim", $id)->first();

		if($type == 'key') {
			echo $get['nim']." - ".$get['nama_mahasiswa'];
		} elseif($type == 'val') {
			echo $get['nim'];
		} else {
			echo "<option value='".$get['nim']."' selected>".$get['nim']." - ".$get['nama_mahasiswa']."</option>";	
		}
	}
}

if(!function_exists('number_format_short')) {
	function number_format_short( $n, $precision = 1 ) {
	    if ($n < 900) {
	        // 0 - 900
	        $n_format = number_format($n, $precision);
	        $suffix = '';
	    } else if ($n < 900000) {
	        // 0.9k-850k
	        $n_format = number_format($n / 1000, $precision);
	        $suffix = 'K';
	    } else if ($n < 900000000) {
	        // 0.9m-850jt
	        $n_format = number_format($n / 1000000, $precision);
	        $suffix = 'Jt';
	    } else if ($n < 900000000000) {
	        // 0.9b-850m
	        $n_format = number_format($n / 1000000000, $precision);
	        $suffix = 'M';
	    } else {
	        // 0.9t+
	        $n_format = number_format($n / 1000000000000, $precision);
	        $suffix = 'T';
	    }

	    if ( $precision > 0 ) {
	        $dotzero = '.' . str_repeat( '0', $precision );
	        $n_format = str_replace( $dotzero, '', $n_format );
	    }

	    return $n_format." ".$suffix;
	}
}

if(!function_exists('int_to_jt')) {
	function int_to_jt($val) {
		  $result="";
		  if(!empty($val)){
	    	$result = $val/1000000;
		  }
		  return $result;
	}
}

if (!function_exists('beacode_to_progname')) {
	function beacode_to_progname($bea_code, $type)
	{
		$beasiswa      = new Beasiswa();
		$getBeasiswa   = $beasiswa->select("program_name as ProgramName")->where('beasiswa_code', $bea_code)->first();

		switch ($type) {
			case 'program_name':
				return $getBeasiswa["ProgramName"];
				break;
			default:
				return "beasiswa tidak ditemukan";
				break;
		}
	}
}
