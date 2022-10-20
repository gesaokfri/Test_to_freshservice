<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\KeuLabaRugi;
use App\Models\KeuNeraca;
use App\Models\View\Unika\Dashboard\ViewBebanPendapatan;
use App\Models\View\Unika\Dashboard\ViewPiutangMahasiswa;
use App\Models\View\Unika\Dashboard\ViewPosisiKeuangan;

use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKas;
use App\Models\Yayasan\Portal\ViewSdmReport;

class DashboardController extends BaseController
{
  public $mydata;

  public function __construct() 
  {
    $this->mydata['id_menu'] = '1';
  }

  public function index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // pass data for filtering
        for ($i = date("Y"); $i >= 1900; $i--) {
          $data['tahun'][] = $i;
        }
        $karyawan = $this->karyawan();

        $data['karyawan']        = $karyawan["data"];
        $data['bulan']           = $karyawan["bulan"];
        $data['tahun']           = $karyawan["tahun"];
        $data['ratio']           = $this->ratio();
        $data['kasDanBank']      = $this->kasDanBank();
        $data['surplus']         = $this->surplus();
        $data['piutang']         = $this->piutangMahasiswa();
        $data['pendapatanBeban'] = $this->pendapatanBeban();
        // print_r($data['piutang']);die();
        return $this->blade->render("pages.dashboard.res", $data);
      } else {
        $_SESSION["error"] = true;
        return redirect()->to('/503');
      }

    }
  }

  public function karyawan(){
    $karyawan = new ViewSdmReport();
    
    $data = $karyawan
    ->select("SUM(Jumlah) AS Jumlah")
    ->where('Tahun', date('Y'))
    ->where('Bulan', date('m'))
    ->first();
    
    if(empty($data['Jumlah'])) {
      if(date('m') == '01') {
        $bulan = 12;
        $tahun = date('Y',strtotime("-1 year"));
      } else {
        $bulan = date('m', strtotime("-1 month"));
        $tahun = date('Y');
      }

      $data = $karyawan
      ->select("SUM(Jumlah) AS Jumlah")
      ->where('Tahun', $tahun)
      ->where('Bulan', $bulan)
      ->first();

      if (empty($data['Jumlah'])) {
        $bulan = date('m', strtotime("-2 month"));
        $tahun = date('Y');
        
        $data = $karyawan
        ->select("SUM(Jumlah) AS Jumlah")
        ->where('Tahun', $tahun)
        ->where('Bulan', $bulan)
        ->first();

        if (empty($data['Jumlah'])) {
          $bulan = date('m', strtotime("-3 month"));
          $tahun = date('Y');

          $data = $karyawan
          ->select("SUM(Jumlah) AS Jumlah")
          ->where('Tahun', $tahun)
          ->where('Bulan', $bulan)
          ->first();
        }
      }
    }


    return [
      "data"  => $data,
      "bulan" => $bulan,
      "tahun" => $tahun
    ];
  }

  public function ratio()
  {
    $posisiKeuangan = new KeuNeraca();

    // Present = Bulan ini - 1, Past = Present - 1
    $present = date("Y") . "-" . date("m", strtotime("-1 Months"));

    $getAsetLancar = $posisiKeuangan->select("SUM (neraca_value) as Total")
    ->where("neraca_name", "Aset Lancar")
    ->where("neraca_group", "2")
    ->where("neraca_period", $present)
    ->where("neraca_quarter_value IS NULL")
    ->where("neraca_value IS NOT NULL")
    ->where("neraca_value <>", 0)
    ->first();

    $getJangkaPendek = $posisiKeuangan->select("SUM (neraca_value) as Total")
    ->where("neraca_name", "Liabilitas Jangka Pendek")
    ->where("neraca_group", "2")
    ->where("neraca_period", $present)
    ->where("neraca_quarter_value IS NULL")
    ->where("neraca_value IS NOT NULL")
    ->where("neraca_value <>", 0)
    ->first();

    // // Calc ratio AL:JP
    if (empty($getJangkaPendek["Total"])) {
      $result = 0;
    } else {
      $result = $getAsetLancar['Total'] / $getJangkaPendek['Total'];
    }
    $data = round($result, 1);
    return $data;
  }

  public function kasDanBank()
  {
    $kasBank = new KeuNeraca();

    // Present = Bulan ini - 1, Past = Present - 1
    $present = date('Y') . "-" . date('m', strtotime('-1 Months'));
    $pass    = date('Y') . "-" . date('m', strtotime('-2 Months'));

    $getBulanLalu = $kasBank->select("SUM (neraca_value) as Total")
    ->whereIn("neraca_name", ["Kas", "Bank", "Deposito Berjangka"])
    ->where("neraca_group", "2")
    ->where("neraca_period", $pass)
    ->where("neraca_quarter_value IS NULL")
    ->where("neraca_value IS NOT NULL")
    ->first();

    $getBulanIni = $kasBank->select("SUM (neraca_value) as Total")
    ->whereIn("neraca_name", ["Kas", "Bank", "Deposito Berjangka"])
    ->where("neraca_group", "2")
    ->where("neraca_period", $present)
    ->where("neraca_quarter_value IS NULL")
    ->where("neraca_value IS NOT NULL")
    ->first();

    // Kalkulasi persentase
    if (empty($getBulanLalu["Total"])) {
      $result = 0;
    } else {
      $result = (($getBulanIni["Total"] / $getBulanLalu["Total"]) * 100) - 100;
    }
    $data = round($result, 1);

    return $data;
  }

  public function surplus()
  {
    $surplus = new KeuLabaRugi();

    // Present = Bulan ini - 1, Past = Present - 1
    $present = date('Y') . "-" . sprintf("%02d", (date('m') - 1));
    $past = date('Y') . "-" . sprintf("%02d", (date('m') - 2));

    $pendapatanPresent = $surplus
    ->select("SUM (laba_rugi_quarter_value) AS Jumlah")
    ->where("status", "1")
    ->where("laba_rugi_group", "2")
    ->where("laba_rugi_name", "Pendapatan")
    ->where("group_type", "1")
    ->where("laba_rugi_period", $present)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->first();

    $bebanPresent = $surplus
    ->select("SUM (laba_rugi_quarter_value) AS Jumlah")
    ->where("status", "1")
    ->where("laba_rugi_group", "2")
    ->where("laba_rugi_name", "Beban")
    ->where("group_type", "2")
    ->where("laba_rugi_period", $present)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->first();

    $pendapatanPast = $surplus
    ->select("SUM (laba_rugi_quarter_value) AS Jumlah")
    ->where("status", "1")
    ->where("laba_rugi_group", "2")
    ->where("laba_rugi_name", "Pendapatan")
    ->where("group_type", "1")
    ->where("laba_rugi_period", $past)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->first();

    $bebanPast = $surplus
    ->select("SUM (laba_rugi_quarter_value) AS Jumlah")
    ->where("status", "1")
    ->where("laba_rugi_group", "2")
    ->where("laba_rugi_name", "Beban")
    ->where("group_type", "2")
    ->where("laba_rugi_period", $past)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->first();

    $akhir = ($pendapatanPresent['Jumlah']*-1) - $bebanPresent['Jumlah'];  // BULAN INI
    $awal  = ($pendapatanPast['Jumlah']*-1) - $bebanPast['Jumlah'];        // BULAN LALU

    if (empty($akhir)) { // Jika surplus bulan ini kosong
      if (empty($awal)) {
        $data = "0";
      } else {
        $data = "-100";
      } 
    } else { // Jika surplus bulan ini ada
      if (empty($awal)) {
        $data = "100";
      } else {
        $result = (($akhir-$awal)/abs($awal))*100;
        $data   = round($result, 1);
      }
    }

    return $data;
  }

  public function piutangMahasiswa(){
    $piutangMahasiswa = new ViewPiutangMahasiswa();

    $present = date('Y')."-".sprintf("%02d", (date('m')-1));
    $past    = date('Y')."-".sprintf("%02d", (date('m')-2));

    $getBulanLalu = $piutangMahasiswa->select("SUM (Hutang) as Hutang")
    ->where("LEFT(Periode, 7)", $past)
    ->first();

    $getBulanIni = $piutangMahasiswa->select("SUM (Hutang) as Hutang")
    ->where("LEFT(Periode, 7)", $present)
    ->first();

    $data['monthPass']    = json_encode(get_date_indonesia(date('Y-m-d',strtotime("-2 months", strtotime(date("Y-m-d")))),'month'));
    $data['monthPresent'] = json_encode(get_date_indonesia(date('Y-m-d',strtotime("-1 months", strtotime(date("Y-m-d")))),'month'));
    $data['past']         = json_encode(round($getBulanLalu['Hutang']/1000000000));
    $data['present']      = json_encode(round($getBulanIni['Hutang']/1000000000));
    
    return $data;
  }

  public function pendapatanBeban(){
    $pendapatanBeban = new KeuLabaRugi();

    // tahun ini
    $present = date('Y-m',  strtotime("-1 Months"));
    $past    = date('Y-m',  strtotime("-2 Months"));

    $getPendapatan = $pendapatanBeban->select("SUM (laba_rugi_quarter_value) as Total")
    ->where("group_type", "1") // Pendapatan
    ->where("laba_rugi_name", "Pendapatan")
    ->where("laba_rugi_period", $present)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->where("laba_rugi_group", "2") // Universitas
    ->first();

    (empty($getPendapatan["Total"]) ? $getPendapatan["Total"] = 0 : $getPendapatan["Total"] = $getPendapatan["Total"]*-1);

    $getBeban = $pendapatanBeban->select("SUM (laba_rugi_quarter_value) as Total")
    ->where("group_type", "2") // Beban
    ->where("laba_rugi_name", "Beban")
    ->where("laba_rugi_period", $present)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->where("laba_rugi_group", "2") // Universitas
    ->first();

    (empty($getBeban["Total"]) ? $getBeban["Total"] = 0 : $getBeban["Total"] = $getBeban["Total"]);

    $getPendapatanPast = $pendapatanBeban->select("SUM (laba_rugi_quarter_value) as Total")
    ->where("group_type", "1") // Pendapatan
    ->where("laba_rugi_name", "Pendapatan")
    ->where("laba_rugi_period", $past)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->where("laba_rugi_group", "2") // Yayasan, Universitas, Rumah Sakit
    ->first();

    (empty($getPendapatanPast["Total"]) ? $getPendapatanPast["Total"] = 0 : $getPendapatanPast["Total"] = $getPendapatanPast["Total"]*-1);

    $getBebanPast = $pendapatanBeban->select("SUM (laba_rugi_quarter_value) as Total")
    ->where("group_type", "2") // Beban
    ->where("laba_rugi_name", "Beban")
    ->where("laba_rugi_period", $past)
    ->where("laba_rugi_value is NULL", NULL, FALSE)
    ->where("laba_rugi_quarter_value is NOT NULL", NULL, FALSE)
    ->where("laba_rugi_group", "2") // Yayasan, Universitas, Rumah Sakit
    ->first();

    (empty($getBebanPast["Total"]) ? $getBebanPast["Total"] = 0 : $getBebanPast["Total"] = $getBebanPast["Total"]);

    if($getPendapatan['Total'] == 0 || $getBeban['Total'] == 0) {
      if($getPendapatan['Total'] == 0) {
        $pendapatan = 0;
        $persenPendapatan = 0;
        $beban = round($getBeban['Total']/1000000000);
        $persenBeban = 100;

      } else if($getBeban['Total'] == 0) {
        $pendapatan = round($getPendapatan['Total']/1000000000);
        $persenPendapatan = 100;
        $beban = 0;
        $persenBeban = 0;
      }
      $pendapatan = 0;
      $persenPendapatan = 0;
      $beban = 0;
      $persenBeban = 0;
      $surplus = 0;
      $persenSurplus = 0;
    } else {
      $pendapatan = round($getPendapatan['Total']/1000000000);
      $beban = round($getBeban['Total']/1000000000);
      $persenPendapatan = round(($pendapatan/($pendapatan+$beban))*100, 1);
      $persenBeban = round(($beban/($pendapatan+$beban))*100, 1);
      $surplus = round(($getPendapatan['Total']-$getBeban['Total'])/1000000000);
      $surplusPresent = ($getPendapatan['Total'])-$getBeban['Total'];
      $surplusPast    = ($getPendapatanPast['Total']) - $getBebanPast['Total'];

      if (empty($surplusPresent)) { // Jika surplus bulan ini kosong
        if (empty($surplusPast)) {
          $persenSurplus = "0";
        } else {
          $persenSurplus = "-100";
        } 
      } else { // Jika surplus bulan ini ada
        if (empty($surplusPast)) {
          $persenSurplus = "100";
        } else {
          $persenSurplus   = round((($surplusPresent-$surplusPast)/abs($surplusPast))*100, 1);
        }
      }

    }
    $total = round(($getPendapatan['Total']+$getBeban['Total']));

    // data untuk pass ke chart
    $data['chartPendapatan'] = json_encode($pendapatan);
    $data['chartBeban']      = json_encode($beban);
    // data untuk pass ke view card
    $data['pendapatan']       = $pendapatan;
    $data['persenPendapatan'] = $persenPendapatan;
    $data['beban']            = $beban;
    $data['persenBeban']      = $persenBeban;
    $data['surplus']          = $surplus;
    $data['persenSurplus']    = $persenSurplus;
    $data['total']            = $total;
    
    return $data;
  }

  public function ResetFilter()
  {
    $name  = $this->request->getPost('name');

    unset($_SESSION[$name]);
  }
  
  public function ResetCompare()
  {
    $name  = $this->request->getPost('name');

    unset($_SESSION[$name]);
  }
  
  public function ResetGlobalCompare()
  {
    unset($_SESSION["compareTotalMahasiswa"]);
    unset($_SESSION["compareTotalMahasiswaBaru"]);
    unset($_SESSION["compareTotalDosen"]);
    unset($_SESSION["compareRasioDosenMahasiswa"]);
    unset($_SESSION["globalCompare"]);
  }
  
  public function ResetGlobalFilter()
  {
    unset($_SESSION["totalMahasiswa"]);
    unset($_SESSION["totalMahasiswaBaru"]);
    unset($_SESSION["totalDosen"]);
    unset($_SESSION["rasioDosenMahasiswa"]);
    unset($_SESSION["globalFilter"]);
  }

}