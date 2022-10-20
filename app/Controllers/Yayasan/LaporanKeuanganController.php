<?php

namespace App\Controllers\Yayasan;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;
use App\Models\KeuNeraca;
use App\Models\KeuLabaRugi;
use App\Models\KeuCashflow;
use App\Models\KeuCapex;
use App\Models\Budget;

// 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKas;
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKasQuarter;
use App\Models\Yayasan\LaporanKeuangan\ViewTrendPendInvestasi;
use App\Models\Yayasan\LaporanKeuangan\ViewInvestasi;
use App\Models\Yayasan\LaporanKeuangan\ViewInvestasiQuarter;
use App\Models\Yayasan\LaporanKeuangan\ViewPengeluaranInvestasiCapex;
use App\models\Yayasan\LaporanKeuangan\ViewAsetTetap;
// 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211

class LaporanKeuanganController extends BaseController
{

  public $mydata;
  private $db;

  public function __construct() {
    $this->mydata["id_menu"]    = "12";
    $this->mydata["group_type"] = [ // 1 = Yayasan,  2 = Unika
      0 => "1",
      1 => "2",
      2 => "3"
    ];

    $this->mydata["group_data"] = [ // 1 = Yayasan,  2 = Unika
      0 => "01",
      1 => "02",
      2 => "03"
    ];

    $this->db = db_connect();

    // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
    $this->mydata["color"] = [
      0  => "#479f76",
      1  => "#fd9843",
      2  => "#3d8bfd",
      3  => "#de5c9d",
      4  => "#ffcd39",
      5  => "#8540f5",
      6  => "#4dd4ac",
      7  => "#e35d6a",
      8  => "#FFBC42",
      9  => "#3dd5f3",
      10 => "#423E28",
      11 => "#FC8B00",
      12 => "#7FF518",
    ];
    // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
    
  }

  public function Index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/'); //Back to Login
    } else {
      $data['id_menu']  = $this->mydata['id_menu'];
      if(acc_read(session('level_id'),$data['id_menu'])=="1"){
        $year_month        = date("Y-m");
        $year_month_before = date("Y-m",strtotime("-1 month"));
        $data['year_month']           = $year_month;
        $data['year_month_before']    = $year_month_before;
        return $this->blade->render("yayasan/pages/laporan_keuangan/index", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Chart() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/'); //Back to Login
    } else {
      $data['id_menu']  = $this->mydata['id_menu'];
      if(acc_read(session('level_id'),$data['id_menu'])=="1"){
        $year_month        = date("Y-m");
        $year_month_before = date("Y-m",strtotime("-1 month"));
        $data['year']                 = date("Y");
        $data['year_month']           = $year_month;
        $data['year_month_before']    = $year_month_before;

        return $this->blade->render("yayasan/pages/laporan_keuangan/chart", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function FilterChartNeraca()
  {
    $filter_type    = $this->request->getPost('Filter');
    $tahunTerakhir  = $this->request->getPost('Year');
    $quarter_get    = @$this->request->getPost('Quarter');

    if (empty($filter_type)) {
      $filter_type = "tahun";
    }

    // PANGGIL MODEL
    $LabaRugi = new KeuLabaRugi();

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if ($tahunTerakhir == "") {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir-4;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    if ($filter_type == "tahun") {
      // VARIABEL UNTUK CHART
      $chartPBYAJ = array();

      // START MAIN FUNCTION
      //Pendapatan
      $chartPBYAJ[0]["name"] = "Pendapatan";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn]."-12")
        ->first();

        if (empty($getValue["total"])) {
          $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
          ->where("laba_rugi_value <>", 0)
          ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
          ->groupBy("laba_rugi_period")
          ->orderBy("laba_rugi_period", "DESC")
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, $getValue["total"] / 1000000000);
          }
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
        }
      }
      $chartPBYAJ[0]["data"] = $data_array;

      //Beban
      $chartPBYAJ[1]["name"] = "Beban";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn]."-12")
        ->first();

        if (empty($getValue["total"])) {
          $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
          ->where("laba_rugi_value <>", 0)
          ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
          ->groupBy("laba_rugi_period")
          ->orderBy("laba_rugi_period", "DESC")
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, $getValue["total"] / 1000000000);
          }
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
        }
      }
      $chartPBYAJ[1]["data"] = $data_array;
      // END MAIN FUNCTION

      $LastFiveYears = [];
      for ($i = 0; $i <= 4; $i++) {
        $LastFiveYears[] = [
          "$listTahun[$i]"
        ];
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartPBYAJ"]               = json_encode($chartPBYAJ);
      $data["LastFiveYears"]            = json_encode($LastFiveYears);
      return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_chart_tahun", $data);
    } 
    else if ($filter_type == "quarter") {
      // VARIABEL UNTUK CHART
      $chartPBYAJ = array();

      // START MAIN FUNCTION
      //Pendapatan
      $chartPBYAJ[0]["name"] = "Pendapatan";
      $data_array  = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {

        $getMonthQuarter = month_quarter($quarter);
        $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];

        $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();


        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, ($getValue["total"]*-1) / 1000000000);
        }
      }
      $chartPBYAJ[0]["data"] = $data_array;

      //Beban
      $chartPBYAJ[1]["name"] = "Beban";
      $data_array  = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {

        $getMonthQuarter = month_quarter($quarter);
        $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];
        $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, ($getValue["total"]) / 1000000000);
        }
      }
      $chartPBYAJ[1]["data"] = $data_array;
      // END MAIN FUNCTION

      $ListQuarter = ['Q1 ' . $tahunTerakhir, 'Q2 ' . $tahunTerakhir, 'Q3 ' . $tahunTerakhir, 'Q4 ' . $tahunTerakhir];

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartPBYAJ"]              = json_encode($chartPBYAJ);
      $data["ListQuarter"]             = json_encode($ListQuarter);
      return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_chart_quarter", $data);
    } 
    else if ($filter_type == "quater_komparasi") {
      // VARIABEL UNTUK CHART
      $chartPBYAJ = array();

      // START MAIN FUNCTION
      //Pendapatan
      $chartPBYAJ[0]["name"] = "Pendapatan";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {

        $getMonthQuarter = month_quarter($quarter_get);
        $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];

        $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, ($getValue["total"]*-1) / 1000000000);
        }
      }
      $chartPBYAJ[0]["data"] = $data_array;

      //Beban
      $chartPBYAJ[1]["name"] = "Beban";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {

        $getMonthQuarter = month_quarter($quarter_get);
        $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];
        $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, ($getValue["total"]) / 1000000000);
        }
      }
      $chartPBYAJ[1]["data"] = $data_array;
      // END MAIN FUNCTION

      $LastFiveYears = [];
      for ($i = 0; $i <= 4; $i++) {
        $LastFiveYears[] = [
          $quarter_get . " $listTahun[$i]"
        ];
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartPBYAJ"]            = json_encode($chartPBYAJ);
      $data["LastFiveYears"]         = json_encode($LastFiveYears);
      return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_chart_quarter_komparasi", $data);
    }
    else if($filter_type == "tahun_bulan"){
         $date1  = $this->request->getPost('From');
          $date2  = $this->request->getPost('To');

          $countMonth = diffMonth($date1,$date2);

          //List Month
          $ListYearMonth = [];
          $i = date("Ym", strtotime($date1));
          while($i <= date("Ym", strtotime($date2))){
            $result =  substr($i,0,4)."-".substr($i,4);
            $ListYearMonth[] = $result;
            if(substr($i, 4, 2) == "12")
              $i = (date("Y", strtotime($i."01")) + 1)."01";
            else
              $i++;
          }

          // VARIABEL UNTUK CHART
          $chartPBYAJ = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartPBYAJ[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("laba_rugi_period", $ListYearMonth[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, round($getValue["total"] / 1000000000));
                }
              } else {
                array_push($data_array, round($getValue["total"]/1000000000));
              }
            }
            $chartPBYAJ[0]["data"] = $data_array;

            //Beban
            $chartPBYAJ[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("laba_rugi_period", $ListYearMonth[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, round($getValue["total"] / 1000000000));
                }
              } else {
                array_push($data_array, round($getValue["total"]/1000000000));
              }
            }
            $chartPBYAJ[1]["data"] = $data_array;
          // END MAIN FUNCTION

          //List Month
          $LastFiveYears = [];
          $i = date("Ym", strtotime($date1));
          while($i <= date("Ym", strtotime($date2))){
            $result =  substr($i,0,4)."-".substr($i,4);
            $LastFiveYears[] = [$result];
            if(substr($i, 4, 2) == "12")
                $i = (date("Y", strtotime($i."01")) + 1)."01";
            else
                $i++;
          }

          // DATA YANG AKAN DIKIRIM KE CHART
          $data["chartPBYAJ"]               = json_encode($chartPBYAJ);
          $data["LastFiveYears"]            = json_encode($LastFiveYears);
          return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_chart_tahun", $data);
    }
  }


  public function FilterChartLabaRugi(){
        $filter_type    = $this->request->getPost('Filter');
        $tahunTerakhir  = $this->request->getPost('Year');
        $quarter_get    = @$this->request->getPost('Quarter');


        if (empty($filter_type)) {
          $filter_type = "tahun";
        }

        // PANGGIL MODEL
        $LabaRugi = new KeuLabaRugi();

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn = 0;
        $listTahun = [];
        if ($tahunTerakhir == "") {
          $tahunTerakhir = date("Y");
        }
        $back_year = $tahunTerakhir-4;
        for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
          $listTahun[] = $thn;
        }

      if($filter_type=="tahun"){
          // VARIABEL UNTUK CHART
          $chartKenaikanPB = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartKenaikanPB[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $getValue["total"]);
                }
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[0]["real"] = $data_array;

            //Persen Pendapatan
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $year_before = minplusDate($listTahun[$perthn].'-01','-1 year','Y');
                  $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period LIKE '".$year_before."%'", NULL, FALSE)
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $chartKenaikanPB[0]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[0]["real"][$i-1];
                 $total       = $chartKenaikanPB[0]["real"][$i];
                  
              }

              if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[0]["data"] = $data_array_persen;

            //Beban
            $chartKenaikanPB[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $getValue["total"]);
                }
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[1]["real"] = $data_array;

            //Persen Beban
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $year_before = minplusDate($listTahun[$perthn].'-01','-1 year','Y');
                  $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period LIKE '".$year_before."%'", NULL, FALSE)
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $chartKenaikanPB[1]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[1]["real"][$i-1];
                 $total       = $chartKenaikanPB[1]["real"][$i];
              }

              if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[1]["data"] = $data_array_persen;

          // END MAIN FUNCTION

           $LastFiveYears = [];
            for ($i = 0; $i <= 4; $i++) {
              $LastFiveYears[] = [
                "$listTahun[$i]"
              ];
            } 

          // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartKenaikanPB"]            = json_encode($chartKenaikanPB);
        $data["LastFiveYears"]              = json_encode($LastFiveYears);
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_chart_tahun", $data);
          
      }
      else if($filter_type=="quarter"){
          // VARIABEL UNTUK CHART
          $chartKenaikanPB = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartKenaikanPB[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($quarter = 1; $quarter <= 4; $quarter++) {
              
              $getMonthQuarter = month_quarter($quarter);
              $dt1 = $tahunTerakhir."-".$getMonthQuarter[0];
              $dt2 = $tahunTerakhir."-".$getMonthQuarter[1];
              $dt3 = $tahunTerakhir."-".$getMonthQuarter[2];
                
              $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();

              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]*-1);
              }
            }
            $chartKenaikanPB[0]["real"] = $data_array;

            //Persen Pendapatan
            $i=0;
            $data_array_persen  = array();
            for ($quarter = 1; $quarter <= 4; $quarter++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getMonthQuarter = month_quarter("Q4");
                  $dt1 = $back_year."-".$getMonthQuarter[0];
                  $dt2 = $back_year."-".$getMonthQuarter[1];
                  $dt3 = $back_year."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                  if (!empty($getQuarterBefore)) {
                    $totalBefore = $getQuarterBefore["total"]*-1;
                  }
                  $total = $chartKenaikanPB[0]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[0]["real"][$i-1];
                 $total       = $chartKenaikanPB[0]["real"][$i];
              }

              if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[0]["data"] = $data_array_persen;

            //Beban
            $chartKenaikanPB[1]["name"] = "Beban";
            $data_array  = array();
            for ($quarter = 1; $quarter <= 4; $quarter++) {
              
              $getMonthQuarter = month_quarter($quarter);
              $dt1 = $tahunTerakhir."-".$getMonthQuarter[0];
              $dt2 = $tahunTerakhir."-".$getMonthQuarter[1];
              $dt3 = $tahunTerakhir."-".$getMonthQuarter[2];
              $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                              ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                              ->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[1]["real"] = $data_array;

            //Persen Beban
            $i=0;
            $data_array_persen  = array();
            for ($quarter = 1; $quarter <= 4; $quarter++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getMonthQuarter = month_quarter("Q4");
                  $dt1 = $back_year."-".$getMonthQuarter[0];
                  $dt2 = $back_year."-".$getMonthQuarter[1];
                  $dt3 = $back_year."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                  if (!empty($getQuarterBefore)) {
                    $totalBefore = $getQuarterBefore["total"];
                  }
                  $total = $chartKenaikanPB[1]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[1]["real"][$i-1];
                 $total       = $chartKenaikanPB[1]["real"][$i];
              }

              if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[1]["data"] = $data_array_persen;
          // END MAIN FUNCTION
          
          $ListQuarter = ['Q1 '.$tahunTerakhir,'Q2 '.$tahunTerakhir,'Q3 '.$tahunTerakhir,'Q4 '.$tahunTerakhir];

          // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartKenaikanPB"]            = json_encode($chartKenaikanPB);
        $data["ListQuarter"]                = json_encode($ListQuarter);
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_chart_quarter", $data);
          
      }
      else if($filter_type=="quater_komparasi"){
           // VARIABEL UNTUK CHART
          $chartKenaikanPB = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartKenaikanPB[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {

              $getMonthQuarter = month_quarter($quarter_get);
              $dt1 = $listTahun[$perthn]."-".$getMonthQuarter[0];
              $dt2 = $listTahun[$perthn]."-".$getMonthQuarter[1];
              $dt3 = $listTahun[$perthn]."-".$getMonthQuarter[2];

              $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();

              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]*-1);
              }
            }
            $chartKenaikanPB[0]["real"] = $data_array;

            //Persen Pendapatan
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getyear6before =  minplusDate($back_year.'-01','-1 year','Y');
                  $getMonthQuarter = month_quarter($quarter_get);
                  $dt1 = $getyear6before."-".$getMonthQuarter[0];
                  $dt2 = $getyear6before."-".$getMonthQuarter[1];
                  $dt3 = $getyear6before."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                  if (!empty($getQuarterBefore)) {
                    $totalBefore = $getQuarterBefore["total"]*-1;
                  }
                  $total = $chartKenaikanPB[0]["real"][$i];
                  if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                  }
              }
              else {
                 $totalBefore = $chartKenaikanPB[0]["real"][$i-1];
                 $total       = $chartKenaikanPB[0]["real"][$i];
                  if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi   = round((($total-$totalBefore)/$totalBefore)*100);
                  }
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[0]["data"] = $data_array_persen;

            //Beban
            $chartKenaikanPB[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {

              $getMonthQuarter = month_quarter($quarter_get);
              $dt1 = $listTahun[$perthn]."-".$getMonthQuarter[0];
              $dt2 = $listTahun[$perthn]."-".$getMonthQuarter[1];
              $dt3 = $listTahun[$perthn]."-".$getMonthQuarter[2];
              $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                              ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                              ->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[1]["real"] = $data_array;

            //Persen Beban
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getyear6before =  minplusDate($back_year.'-01','-1 year','Y');
                  $getMonthQuarter = month_quarter($quarter_get);
                  $dt1 = $getyear6before."-".$getMonthQuarter[0];
                  $dt2 = $getyear6before."-".$getMonthQuarter[1];
                  $dt3 = $getyear6before."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                  
                  if (!empty($getQuarterBefore)) {
                    $totalBefore = $getQuarterBefore["total"];
                  }
                  $total = $chartKenaikanPB[1]["real"][$i];
                  if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                  }
              }
              else {
                 $totalBefore = $chartKenaikanPB[1]["real"][$i-1];
                 $total       = $chartKenaikanPB[1]["real"][$i];
                  if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi   = round((($total-$totalBefore)/$totalBefore)*100);
                  }
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[1]["data"] = $data_array_persen;
          // END MAIN FUNCTION

           $LastFiveYears = [];
            for ($i = 0; $i <= 4; $i++) {
              $LastFiveYears[] = [
                $quarter_get." $listTahun[$i]"
              ];
            } 

          // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartKenaikanPB"]            = json_encode($chartKenaikanPB);
        $data["LastFiveYears"]              = json_encode($LastFiveYears);
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_chart_quarter_komparasi", $data);
          
      }
      else if($filter_type=="tahun_bulan"){
         $date1            = $this->request->getPost('From');
          $date2            = $this->request->getPost('To');
          $countMonth       = diffMonth($date1,$date2);
          $yearMonth_before = minplusDate($date1,'-1 month','Y-m');
          // VARIABEL UNTUK CHART
          $chartKenaikanPB = array();

          //List Month
          $ListYearMonth = [];
          $i = date("Ym", strtotime($date1));
          while($i <= date("Ym", strtotime($date2))){
            $result =  substr($i,0,4)."-".substr($i,4);
            $ListYearMonth[] = $result;
            if(substr($i, 4, 2) == "12")
                $i = (date("Y", strtotime($i."01")) + 1)."01";
            else
                $i++;
          }

          // START MAIN FUNCTION
            //Pendapatan
            $chartKenaikanPB[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $getValue["total"]);
                }
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[0]["real"] = $data_array;

            //Persen Pendapatan
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$yearMonth_before)
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $chartKenaikanPB[0]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[0]["real"][$i-1];
                 $total       = $chartKenaikanPB[0]["real"][$i];
                  
              }

              if(!empty($total) && !empty($totalBefore)){
                    $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[0]["data"] = $data_array_persen;

            //Beban
            $chartKenaikanPB[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                ->where("laba_rugi_value <>", 0)
                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                ->groupBy("laba_rugi_period")
                ->orderBy("laba_rugi_period", "DESC")
                ->first();

                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $getValue["total"]);
                }
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartKenaikanPB[1]["real"] = $data_array;

            //Persen Beban
            $i=0;
            $data_array_persen  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $totalBefore  = 0;
              $total        = 0;
              $kalkulasi    = 0;
              if($i==0){
                  $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$yearMonth_before)
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $chartKenaikanPB[1]["real"][$i];
              }
              else {
                 $totalBefore = $chartKenaikanPB[1]["real"][$i-1];
                 $total       = $chartKenaikanPB[1]["real"][$i];
              }

              if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
              }
              array_push($data_array_persen,$kalkulasi);
              $i++;
            }
            $chartKenaikanPB[1]["data"] = $data_array_persen;

          // END MAIN FUNCTION


          // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartKenaikanPB"]            = json_encode($chartKenaikanPB);
        $data["LastFiveYears"]              = json_encode($ListYearMonth);
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_chart_tahun", $data);
      }
  }


  public function PendapatanBebanTable()
  {
    $filter_type    = $this->request->getPost('Filter');
    $tahunTerakhir  = $this->request->getPost('Year');
    $quarter_get    = @$this->request->getPost('Quarter');

    if (empty($filter_type)) {
      $filter_type = "tahun";
    }

    // PANGGIL MODEL
    $LabaRugi = new KeuLabaRugi();

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if ($tahunTerakhir == "") {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir - 4;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $TablePendapatanvsBeban = array();
    $TablePendapatanvsBebanPersen = array();

    if ($filter_type == "tahun") {
      // START MAIN FUNCTION
      //Pendapatan
      $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
      $data_array         = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
        ->first();
        if (empty($getValue["total"])) {
          $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
          ->where("laba_rugi_value <>", 0)
          ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
          ->groupBy("laba_rugi_period")
          ->orderBy("laba_rugi_period", "DESC")
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, round($getValue["total"] / 1000000000));
          }
        } else {
          array_push($data_array, round($getValue["total"] / 1000000000));
        }
      }
      $TablePendapatanvsBeban[0]["real"]  = $data_array;

      //Persen
      $i = 0;
      $data_array_persen  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $year_before = minplusDate($listTahun[$perthn] . '-01', '-1 year', 'Y');
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
            ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
            ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = $getValueYearBefore["total"] / 1000000000;
          }
          $total = $TablePendapatanvsBeban[0]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[0]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[0]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

      //Beban
      $TablePendapatanvsBeban[1]["name"] = "Beban";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
        ->first();
        if (empty($getValue["total"])) {
          $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
          ->where("laba_rugi_value <>", 0)
          ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
          ->groupBy("laba_rugi_period")
          ->orderBy("laba_rugi_period", "DESC")
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, round($getValue["total"] / 1000000000));
          }
        } else {
          array_push($data_array, round($getValue["total"] / 1000000000));
        }
      }
      $TablePendapatanvsBeban[1]["real"] = $data_array;

      //Persen
      $i = 0;
      $data_array_persen  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $year_before = minplusDate($listTahun[$perthn] . '-01', '-1 year', 'Y');
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
            ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
            ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = $getValueYearBefore["total"] / 1000000000;
          }
          $total = $TablePendapatanvsBeban[1]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[1]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[1]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
      // END MAIN FUNCTION

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["tableListHeader"] = $listTahun;
    } 
    else if ($filter_type == "quarter") {
      // START MAIN FUNCTION
      //Pendapatan
      $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
      $data_array         = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {
        $getMonthQuarter = month_quarter($quarter);
        $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];
        $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, round(($getValue["total"]*-1) / 1000000000));
        }
      }
      $TablePendapatanvsBeban[0]["real"]  = $data_array;

      //Persen Pendapatan
      $i = 0;
      $data_array_persen  = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $getMonthQuarter = month_quarter("Q4");
          $dt1 = $back_year . "-" . $getMonthQuarter[0];
          $dt2 = $back_year . "-" . $getMonthQuarter[1];
          $dt3 = $back_year . "-" . $getMonthQuarter[2];
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
          ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = ($getValueYearBefore["total"]*-1) / 1000000000;
          }
          $total = $TablePendapatanvsBeban[0]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[0]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[0]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

      //Beban
      $TablePendapatanvsBeban[1]["name"] = "Beban";
      $data_array  = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {
        $getMonthQuarter = month_quarter($quarter);
        $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];
        $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, round(($getValue["total"]) / 1000000000));
        }
      }
      $TablePendapatanvsBeban[1]["real"] = $data_array;

      //Persen Beban
      $i = 0;
      $data_array_persen  = array();
      for ($quarter = 1; $quarter <= 4; $quarter++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $getMonthQuarter = month_quarter("Q4");
          $dt1 = $back_year . "-" . $getMonthQuarter[0];
          $dt2 = $back_year . "-" . $getMonthQuarter[1];
          $dt3 = $back_year . "-" . $getMonthQuarter[2];
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
          ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = ($getValueYearBefore["total"]) / 1000000000;
          }
          $total = $TablePendapatanvsBeban[1]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[1]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[1]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
      // END MAIN FUNCTION

      $ListQuarter = ['Q1 ' . $tahunTerakhir, 'Q2 ' . $tahunTerakhir, 'Q3 ' . $tahunTerakhir, 'Q4 ' . $tahunTerakhir];

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["tableListHeader"] = $ListQuarter;
    } 
    else if ($filter_type == "quater_komparasi") {
      // START MAIN FUNCTION
      //Pendapatan
      $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
      $data_array         = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getMonthQuarter = month_quarter($quarter_get);
        $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];
        $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, round(($getValue["total"]*-1) / 1000000000));
        }
      }
      $TablePendapatanvsBeban[0]["real"]  = $data_array;

      //Persen Pendapatan
      $i = 0;
      $data_array_persen  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $getyear6before =  minplusDate($back_year . '-01', '-1 year', 'Y');
          $getMonthQuarter = month_quarter($quarter_get);
          $dt1 = $getyear6before . "-" . $getMonthQuarter[0];
          $dt2 = $getyear6before . "-" . $getMonthQuarter[1];
          $dt3 = $getyear6before . "-" . $getMonthQuarter[2];
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
          ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = ($getValueYearBefore["total"]*1) / 1000000000;
          }
          $total = $TablePendapatanvsBeban[0]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[0]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[0]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

      //Beban
      $TablePendapatanvsBeban[1]["name"] = "Beban";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {

        $getMonthQuarter = month_quarter($quarter_get);
        $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];
        $getValue  = $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, round(($getValue["total"]) / 1000000000));
        }
      }
      $TablePendapatanvsBeban[1]["real"] = $data_array;

      //Persen Beban
      $i = 0;
      $data_array_persen  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $totalBefore  = 0;
        $total        = 0;
        $kalkulasi    = 0;
        if ($i == 0) {
          $getyear6before =  minplusDate($back_year . '-01', '-1 year', 'Y');
          $getMonthQuarter = month_quarter($quarter_get);
          $dt1 = $getyear6before . "-" . $getMonthQuarter[0];
          $dt2 = $getyear6before . "-" . $getMonthQuarter[1];
          $dt3 = $getyear6before . "-" . $getMonthQuarter[2];
          $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
          ->first();
          if (!empty($getValueYearBefore)) {
            $totalBefore = ($getValueYearBefore["total"]) / 1000000000;
          }
          $total = $TablePendapatanvsBeban[1]["real"][$i];
        } else {
          $totalBefore = $TablePendapatanvsBeban[1]["real"][$i - 1];
          $total       = $TablePendapatanvsBeban[1]["real"][$i];
        }

        if (!empty($total) && $totalBefore > 0) {
          $kalkulasi = number_format((($total - $totalBefore) / $totalBefore) * 100, 2);
        }
        array_push($data_array_persen, $kalkulasi);
        $i++;
      }
      $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
      // END MAIN FUNCTION

      $ListYearQuarter = [
        $quarter_get . " " . $listTahun[0], $quarter_get . " " . $listTahun[1],
        $quarter_get . " " . $listTahun[2], $quarter_get . " " . $listTahun[3], $quarter_get . " " . $listTahun[4]
      ];

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["tableListHeader"] = $ListYearQuarter;
    }
    else if ($filter_type == "tahun_bulan") {
      $date1           = $this->request->getPost('From');
      $date2            = $this->request->getPost('To');
      $countMonth       = diffMonth($date1,$date2);
      $yearMonth_before = minplusDate($date1,'-1 month','Y-m');

         //List Month
        $ListYearMonth = [];
        $i = date("Ym", strtotime($date1));
        while($i <= date("Ym", strtotime($date2))){
          $result =  substr($i,0,4)."-".substr($i,4);
          $ListYearMonth[] = $result;
          if(substr($i, 4, 2) == "12")
              $i = (date("Y", strtotime($i."01")) + 1)."01";
          else
              $i++;
        }

        // START MAIN FUNCTION
          //Pendapatan
          $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
          $data_array         = array();
          for ($perthn = 0; $perthn < $countMonth; $perthn++) {
            $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Pendapatan")
                              ->where("group_type","1")
                              ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$ListYearMonth[$perthn])
                              ->first();
            if (empty($getValue["total"])) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
              ->where("status", "1")
              ->where("laba_rugi_name", "Pendapatan")
              ->where("group_type", "1")
              ->whereIn("laba_rugi_group", $this->mydata['group_type'])
              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
              ->where("laba_rugi_value <>", 0)
              ->where("laba_rugi_period",$ListYearMonth[$perthn])
              ->groupBy("laba_rugi_period")
              ->orderBy("laba_rugi_period", "DESC")
              ->first();

              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, round($getValue["total"] / 1000000000));
              }
            } else {
              array_push($data_array, round($getValue["total"]/1000000000));
            }
          }
          $TablePendapatanvsBeban[0]["real"]  = $data_array;

          //Persen
          $i=0;
          $data_array_persen  = array();
          for ($perthn = 0; $perthn < $countMonth; $perthn++) {
            $totalBefore  = 0;
            $total        = 0;
            $kalkulasi    = 0;
            if($i==0){
                $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Pendapatan")
                              ->where("group_type","1")
                              ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$yearMonth_before)
                              ->first();
                if (!empty($getValueYearBefore)) {
                  $totalBefore = $getValueYearBefore["total"]/1000000000;
                }
                $total = $TablePendapatanvsBeban[0]["real"][$i];
            }
            else {
               $totalBefore = $TablePendapatanvsBeban[0]["real"][$i-1];
               $total       = $TablePendapatanvsBeban[0]["real"][$i];
            }

            if(!empty($total) && $totalBefore > 0){
              $kalkulasi = number_format((($total-$totalBefore)/$totalBefore)*100, 2);
            }
            array_push($data_array_persen,$kalkulasi);
            $i++;
          }
          $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

          //Beban
          $TablePendapatanvsBeban[1]["name"] = "Beban";
          $data_array  = array();
          for ($perthn = 0; $perthn < $countMonth; $perthn++) {
            $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                            ->where("status","1")
                            ->where("laba_rugi_name", "Beban")
                            ->where("group_type","2")
                            ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                            ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                            ->where("laba_rugi_period",$ListYearMonth[$perthn])
                            ->first();
            if (empty($getValue["total"])) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
              ->where("status", "1")
              ->where("laba_rugi_name", "Beban")
              ->where("group_type", "2")
              ->whereIn("laba_rugi_group", $this->mydata['group_type'])
              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
              ->where("laba_rugi_value <>", 0)
              ->where("laba_rugi_period",$ListYearMonth[$perthn])
              ->groupBy("laba_rugi_period")
              ->orderBy("laba_rugi_period", "DESC")
              ->first();

              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, round($getValue["total"] / 1000000000));
              }
            } else {
              array_push($data_array, round($getValue["total"]/1000000000));
            }
          }
          $TablePendapatanvsBeban[1]["real"] = $data_array;

          //Persen
          $i=0;
          $data_array_persen  = array();
          for ($perthn = 0; $perthn < $countMonth; $perthn++) {
            $totalBefore  = 0;
            $total        = 0;
            $kalkulasi    = 0;
            if($i==0){
              $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                            ->where("status","1")
                            ->where("laba_rugi_name", "Beban")
                            ->where("group_type","2")
                            ->whereIn("laba_rugi_group",$this->mydata['group_type'])
                            ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                            ->where("laba_rugi_period", $yearMonth_before)
                            ->first();
              if (!empty($getValueYearBefore)) {
                $totalBefore = $getValueYearBefore["total"]/1000000000;
              }
              $total = $TablePendapatanvsBeban[1]["real"][$i];
            }
            else {
               $totalBefore = $TablePendapatanvsBeban[1]["real"][$i-1];
               $total       = $TablePendapatanvsBeban[1]["real"][$i];
            }

            if(!empty($total) && $totalBefore > 0){
              $kalkulasi = number_format((($total-$totalBefore)/$totalBefore)*100, 2);
            }
            array_push($data_array_persen,$kalkulasi);
            $i++;
          }
          $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
        // END MAIN FUNCTION

        // DATA YANG AKAN DIKIRIM KE CHART

        $data["tableListHeader"] = $ListYearMonth;
        $data["countMonth"]      = $countMonth;
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["filter_type"]                = $filter_type;
    $data["tablePendapatanBeban"]       = $TablePendapatanvsBeban;
    return $this->blade->render("yayasan/pages/laporan_keuangan/pendapatan_beban_table", $data);
  }

  public function FilterChartRealisasiAPB()
  {
    $tahunTerakhir  = $this->request->getPost('Year');

    // PANGGIL MODEL
    $Budget   = new Budget();
    $LabaRugi = new KeuLabaRugi();

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if ($tahunTerakhir == "") {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir - 4;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $ChartRealisasiAPB = array();
    $TableRealisasiAPB = array();

    // START MAIN FUNCTION
    //Pendapatan
    $TableRealisasiAPB[0]["name"] = "Pendapatan";
    $ChartRealisasiAPB[0]["name"] = "Pendapatan";
    $ChartRealisasiAPB[0]["type"] = "column";
    $data_array       = array();
    $datatable_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
      ->where("status", "1")
      ->where("laba_rugi_name", "Pendapatan")
      ->where("group_type", "1")
      ->whereIn("laba_rugi_group", $this->mydata['group_type'])
      ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
      ->where("laba_rugi_period", $listTahun[$perthn]."-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_value <>", 0)
        ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
        ->groupBy("laba_rugi_period")
        ->orderBy("laba_rugi_period", "DESC")
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
          array_push($datatable_array, 0);
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
          array_push($datatable_array, $getValue["total"] / 1000000000);
        }
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
        array_push($datatable_array, $getValue["total"] / 1000000000);
      }
    }
    $ChartRealisasiAPB[0]["data"] = $data_array;
    $TableRealisasiAPB[0]["data"] = $datatable_array;

    //Beban
    $TableRealisasiAPB[1]["name"] = "Beban";
    $ChartRealisasiAPB[1]["name"] = "Beban";
    $ChartRealisasiAPB[1]["type"] = "column";
    $data_array  = array();
    $datatable_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
      ->where("status", "1")
      ->where("laba_rugi_name", "Beban")
      ->where("group_type", "2")
      ->whereIn("laba_rugi_group", $this->mydata['group_type'])
      ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
      ->where("laba_rugi_period", $listTahun[$perthn]."-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", $this->mydata['group_type'])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_value <>", 0)
        ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
        ->groupBy("laba_rugi_period")
        ->orderBy("laba_rugi_period", "DESC")
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
          array_push($datatable_array, 0);
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
          array_push($datatable_array, $getValue["total"] / 1000000000);
        }
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
        array_push($datatable_array, $getValue["total"] / 1000000000);
      }
    }
    $ChartRealisasiAPB[1]["data"] = $data_array;
    $TableRealisasiAPB[1]["data"] = $datatable_array;

    //Surplus
    $TableRealisasiAPB[3]["name"] = "Surplus (defisit)";
    $datatable_array  = array();
    for ($i = 0; $i <= 4; $i++) {
      //Aset - Liabilitas
      $kalkulasi = $TableRealisasiAPB[0]["data"][$i] - $TableRealisasiAPB[1]["data"][$i];
      if (empty($kalkulasi)) {
        array_push($datatable_array, 0);
      } else {
        array_push($datatable_array, $kalkulasi);
      }
    }
    $TableRealisasiAPB[3]["data"] = $datatable_array;

    //Anggaran Pendapatan
    $ChartRealisasiAPB[2]["name"] = "Anggaran Pendapatan";
    $ChartRealisasiAPB[2]["type"] = "line";
    $data_array       = array();
    $datatable_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  = $Budget->select('sum(budget_value) as total')
      ->where("budget_type", "1")
      ->where("budget_group", "2") // 1 UNIKA 2 YAYASAN
      ->where("budget_value is NOT NULL", NULL, FALSE)
      ->where("budget_period", $listTahun[$perthn]."-12")
      ->first();

      if (empty($getValue)) {
        array_push($data_array, 0);
        array_push($datatable_array, 0);
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
        array_push($datatable_array, $getValue["total"] / 1000000000);
      }
    }
    $ChartRealisasiAPB[2]["data"]    = $data_array;
    $TableRealisasiAPB[0]["dataAPB"] = $datatable_array;

    //Anggaran Beban
    $ChartRealisasiAPB[3]["name"] = "Anggaran Beban";
    $ChartRealisasiAPB[3]["type"] = "area";
    $data_array  = array();
    $datatable_array = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  = $Budget->select('sum(budget_value) as total')
      ->where("budget_type", "2")
      ->where("budget_group", "2") // 1 UNIKA 2 YAYASAN
      ->where("budget_value is NOT NULL", NULL, FALSE)
      ->where("budget_period", $listTahun[$perthn]."-12")
      ->first();
      if (empty($getValue)) {
        array_push($data_array, 0);
        array_push($datatable_array, 0);
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
        array_push($datatable_array, $getValue["total"] / 1000000000);
      }
    }
    $ChartRealisasiAPB[3]["data"]    = $data_array;
    $TableRealisasiAPB[1]["dataAPB"] = $data_array;

    //Surplus APB
    $datatable_array  = array();
    for ($i = 0; $i <= 4; $i++) {
      $kalkulasi = $TableRealisasiAPB[0]["dataAPB"][$i] - $TableRealisasiAPB[1]["dataAPB"][$i];
      if (empty($kalkulasi)) {
        array_push($datatable_array, 0);
      } else {
        array_push($datatable_array, $kalkulasi / 1);
      }
    }
    $TableRealisasiAPB[3]["dataAPB"] = $datatable_array;
    // END MAIN FUNCTION

    $LastFiveYears = [];
    for ($i = 0; $i <= 4; $i++) {
      $LastFiveYears[] = [
        "$listTahun[$i]"
      ];
      $dataColor[] = $this->mydata['color'][$i];
    }

    // DATA YANG AKAN DIKIRIM KE CHART

    $data["ChartRealisasiAPB"]    = json_encode($ChartRealisasiAPB);
    $data["dataColor"]            = json_encode($dataColor);
    $data["LastFiveYears"]        = json_encode($LastFiveYears);
    $data["tableListTahun"]       = $listTahun;
    $data["TableRealisasiAPB"]    = $TableRealisasiAPB;
    return $this->blade->render("yayasan/pages/laporan_keuangan/realisasi_chart", $data);
  }

  public function FilterChartPosisiKeuangan()
  {
    $tahunTerakhir  = $this->request->getPost('Year');
    $chartType      = $this->request->getPost('Chart');
    $quarter_get    = @$this->request->getPost('Quarter');

    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $Neraca = new KeuNeraca();

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if ($tahunTerakhir == "") {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir-4;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $chartPosisiKeuangan = array();
    $tablePosisiKeuangan = array();


    // START MAIN FUNCTION
    $data_array       = array();
    $datatable_array  = array();

    //Aset Lancar
    $chartPosisiKeuangan[0]["name"] = "Aset Lancar";
    $tablePosisiKeuangan[0]["name"] = "Aset Lancar";
    $data_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  =  $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "1")
      ->where("neraca_name", "Aset Lancar")
      ->whereIn("neraca_group", $this->mydata['group_type'])
      ->where("neraca_quarter_value is NULL", NULL, FALSE)
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn]."-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
        ->where("neraca_name", "Aset Lancar")
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
        ->groupBy("neraca_period")
        ->orderBy("neraca_period", "DESC")
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
          array_push($datatable_array, 0);
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
          array_push($datatable_array, $getValue["total"] / 1000000000);
        }
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
        array_push($datatable_array, $getValue["total"] / 1000000000);
      }
    }
    $chartPosisiKeuangan[0]["data"] = $data_array;
    $tablePosisiKeuangan[0]["data"] = $datatable_array;


    //Aset Tidak Lancar
    $chartPosisiKeuangan[1]["name"] = "Aset Tidak Lancar";
    $tablePosisiKeuangan[1]["name"] = "Aset Tidak Lancar";
    $data_array  = array();
    $datatable_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  =  $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "1")
      ->whereIn("neraca_name", ["Aset Tidak Lancar", "Aset Investasi", "Aset Lain - Lain"])
      ->whereIn("neraca_group", $this->mydata['group_type'])
      ->where("neraca_quarter_value is NULL", NULL, FALSE)
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn]."-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
        ->whereIn("neraca_name", ["Aset Tidak Lancar", "Aset Investasi", "Aset Lain - Lain"])
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
        ->groupBy("neraca_period")
        ->orderBy("neraca_period", "DESC")
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
          array_push($datatable_array, 0);
        } else {
          array_push($data_array, ($getValue["total"]) / 1000000000);
          array_push($datatable_array, ($getValue["total"]) / 1000000000);
        }
      } else {
        array_push($data_array, ($getValue["total"]) / 1000000000);
        array_push($datatable_array, ($getValue["total"]) / 1000000000);
      }
    }
    $chartPosisiKeuangan[1]["data"] = $data_array;
    $tablePosisiKeuangan[1]["data"] = $datatable_array;


    //Liabilitas
    $chartPosisiKeuangan[2]["name"] = "Liabilitas";
    $data_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  =  $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "2")
      ->whereIn("neraca_name", ["Liabilitas Jangka Pendek", "Liabilitas Jangka Panjang"])
      ->whereIn("neraca_group", $this->mydata['group_type'])
      ->where("neraca_quarter_value is NULL", NULL, FALSE)
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn] . "-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "2")
        ->whereIn("neraca_name", ["Liabilitas Jangka Pendek", "Liabilitas Jangka Panjang"])
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
        ->groupBy("neraca_period")
        ->orderBy("neraca_period", "DESC")
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
        }
      } else {
        array_push($data_array, $getValue["total"] / 1000000000);
      }
    }
    $chartPosisiKeuangan[2]["data"] = $data_array;

    //Jumlah Liabilitas
    $tablePosisiKeuangan[5]["name"] = "Liabilitas";
    $datatable_array  = array();
    $sum_col1 = "Liabilitas Jangka Pendek";
    $sum_col2 = "Liabilitas Jangka Panjang";
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $sum = 0;
      $getValue  =  $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "2")
      ->whereIn('neraca_name', [$sum_col1, $sum_col2])
      ->whereIn("neraca_group", $this->mydata['group_type'])
      ->where("neraca_quarter_value is NULL", NULL, FALSE)
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn]."-12")
      ->first();

      if (!empty($getValue["total"])) {
        $sum += $getValue["total"];
      } else {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "2")
        ->whereIn("neraca_name", [$sum_col1, $sum_col2])
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
        ->groupBy("neraca_period")
        ->orderBy("neraca_period", "DESC")
        ->first();

        if (!empty($getValue)) {
          $sum += $getValue["total"];
        }
      }

      if (empty($sum)) {
        array_push($datatable_array, 0);
      } else {
        array_push($datatable_array, $sum / 1000000000);
      }
    }
    $tablePosisiKeuangan[5]["data"] = $datatable_array;


    //Aset Neto
    $chartPosisiKeuangan[3]["name"] = "Modal";
    $tablePosisiKeuangan[6]["name"] = "Modal";
    $data_array  = array();
    $datatable_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue = $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "1")
      ->where("neraca_name", "Aset Neto")
      ->whereIn("neraca_group", $this->mydata['group_type'])
      ->where("neraca_quarter_value is NULL", NULL, FALSE)
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn]."-12")->first();

      if ($listTahun[$perthn] >= 2021) {
        $getIntercompany  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
        ->where("neraca_name", "Intercompany")
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_period", $listTahun[$perthn] . "-12")
        ->first();

        if (empty($getIntercompany["total"])) {
          $getIntercompany  =  $Neraca->select('sum(neraca_value) as total')
          ->where("status", "1")->where("group_type", "1")
          ->where("neraca_name", "Intercompany")
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_quarter_value is NULL", NULL, FALSE)
          ->where("neraca_value is NOT NULL", NULL, FALSE)
          ->where("neraca_value <>", 0)
          ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
          ->groupBy("neraca_period")
          ->orderBy("neraca_period", "DESC")
          ->first();

          if (empty($getIntercompany["total"])) {
            $intercompanyValue = 0;
          } else {
            $intercompanyValue = $getIntercompany["total"];
          }
        } else {
          $intercompanyValue = $getIntercompany["total"];
        }
      } else {
        $intercompanyValue = 0;
      }

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
        ->where("neraca_name", "Aset Neto")
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
        ->groupBy("neraca_period")
        ->orderBy("neraca_period", "DESC")
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0 - $intercompanyValue);
          array_push($datatable_array, 0 - $intercompanyValue);
        } else {
          array_push($data_array, ($getValue["total"] - $intercompanyValue) / 1000000000);
          array_push($datatable_array, ($getValue["total"] - $intercompanyValue) / 1000000000);
        }
      } else {
        array_push($data_array, ($getValue["total"] - $intercompanyValue) / 1000000000);
        array_push($datatable_array, ($getValue["total"] - $intercompanyValue) / 1000000000);
      }
    }
    $chartPosisiKeuangan[3]["data"] = $data_array;
    $tablePosisiKeuangan[6]["data"] = $datatable_array;

    // END MAIN FUNCTION

    $LastFiveYears = [];
    for ($i = 0; $i <= 4; $i++) {
      $LastFiveYears[] = [
        "$listTahun[$i]"
      ];
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartPosisiKeuangan"]      = json_encode($chartPosisiKeuangan);
    $data["LastFiveYears"]            = json_encode($LastFiveYears);
    $data["chartType"]                = $chartType;
    $data["tableListTahun"]           = $listTahun;
    $data["tablePosisiKeuangan"]      = $tablePosisiKeuangan;
    return $this->blade->render("yayasan/pages/laporan_keuangan/posisi_keuangan_chart", $data);
  }

  public function FilterChartCapex()
  {
    $tahunTerakhir  = $this->request->getPost('Year');

    // PANGGIL MODEL
    $capex                 = new KeuCapex();

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];

    // COLORS
    $Colors["color"] = [
      0  => "#479f76",
      1  => "#fd9843",
      2  => "#3d8bfd",
      3  => "#de5c9d",
      4  => "#f46a6a"
    ];
    // COLORS

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if ($tahunTerakhir == "") {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir-4;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    $chartCapexData = array();
    $tableCapexData = array();
    $data_capex_categories = $capex->select('capex_name,capex_number')
    ->where("status", "1")
    ->whereNotIn("capex_name", ["Gedung & CIP", "Investasi Gedung & CIP", "Investasi Tanah", "Investasi Peralatan dan Prasarana"])
    ->whereIn("capex_group", $this->mydata['group_type'])
    ->groupBy("capex_name,capex_number")
    ->orderBy("
          CASE
            WHEN capex_name = 'Tanah' THEN 1
            WHEN capex_name = 'Bangunan/Gedung' THEN 2
            WHEN capex_name = 'Aset dalam penyelesaian (CIP)' THEN 3
            WHEN capex_name = 'Mesin' THEN 4
          ELSE 5 END ASC")
      ->findAll();
    $i = 0;
    foreach ($data_capex_categories as $cg) {
      $data_array       = array();
      $datatable_array  = array();
      $chartCapexData[$i]['name'] = $cg['capex_name'];
      $tableCapexData[$i]['name'] = $cg['capex_name'];
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue = $capex->select('sum(capex_value) as total')
        ->where("status", "1")
        ->whereIn("capex_group", $this->mydata['group_type'])
        ->where("capex_name", $cg['capex_name'])
        ->where("capex_number", $cg['capex_number'])
        ->where("capex_value is NOT NULL", NULL, FALSE)
        ->where("capex_period", $listTahun[$perthn]."-12")
        ->first();
        if (empty($getValue["total"])) {
          $getValue  =  $capex->select('sum(capex_value) as total')
          ->where("status", "1")
          ->whereIn("capex_group", $this->mydata['group_type'])
          ->where("capex_name", $cg['capex_name'])
          ->where("capex_number", $cg['capex_number'])
          ->where("capex_value is NOT NULL", NULL, FALSE)
          ->where("capex_value <>", 0)
          ->where("LEFT(capex_period, 4)", $listTahun[$perthn])
          ->groupBy("capex_period")
          ->orderBy("capex_period", "DESC")
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
            array_push($datatable_array, 0);
          } else {
            array_push($data_array, $getValue["total"] / 1000000000);
            array_push($datatable_array, $getValue["total"] / 1000000000);
          }
        } else {
          array_push($data_array, $getValue["total"] / 1000000000);
          array_push($datatable_array, $getValue["total"] / 1000000000);
        }
      }
      $chartCapexData[$i]['data'] = $data_array;
      $tableCapexData[$i]['data'] = $datatable_array;
      $dataColor[]                = $Colors['color'][$i];
      for ($totalPerIndex = 0; $totalPerIndex <= 4; $totalPerIndex++) {
        $totalPerCategories[$totalPerIndex + 1] += $data_array[$totalPerIndex];
      }
      $i++;
    }

    $LastFiveYears = [];
    for ($i = 0; $i <= 4; $i++) {
      $LastFiveYears[] = [
        "$listTahun[$i]"
      ];
    }

    $data['LastFiveYears']  = json_encode($LastFiveYears);
    $data['dataColor']      = json_encode($dataColor);
    $data['CapexData']      = json_encode($chartCapexData);
    $data['tableListTahun'] = $listTahun;
    $data['TableCapex']     = $tableCapexData;
    $data["TableCapexSum"]  = $totalPerCategories;

    return $this->blade->render("yayasan/pages/laporan_keuangan/capex_chart", $data);
  }

  public function NeracaTable(){
    if (!$this->session->has('session_id')) {
      echo "You must login again";
    } else {
      $neraca              = new KeuNeraca();
      $req_filter_type     = @$this->request->getPost("filter_type");
      
      $filter_type         = "1"; 
      if(!empty($req_filter_type)){
        $filter_type       = $req_filter_type; 
      }

      if($filter_type=="1"){ // 1 = Bulan yang dipilih dan bulan sebelumnya
        $req_month = $this->request->getPost("filter_month");
        $req_year  = $this->request->getPost("filter_year");
        if(!empty($req_month) && !empty($req_year)){
          $year_month                    = $req_year."-".$req_month;
          $year_month_before             = minplusDate($year_month,'-1 month','Y-m');
        }
        else {
          $year_month                    = date("Y-m");
          $year_month_before             =
          minplusDate($year_month, '-1 month', 'Y-m');
        }
        $year_min1 = minplusDate($year_month,'-1 year','Y');
        $year_min2 = minplusDate($year_month,'-2 year','Y');  
        
        $data['group_List']            = $neraca->where("status","1")->where("neraca_group",$this->mydata['group_type'])
                                            ->where("neraca_type","1")
                                            ->where("neraca_period LIKE '".$year_month."%'", NULL, FALSE)
                                            ->orderBy("neraca_number","asc")->findAll();
                      
        foreach($data['group_List'] as $list_group){
             $group_qry = "select * from tr_keu_neraca where status='1' and neraca_group='".$this->mydata['group_type']."' and neraca_type='1'
                   and neraca_name='".$list_group['neraca_name']."'";
             $value_now_qry       = $group_qry." and neraca_period like '".$year_month."%'";
             $value_before_qry    = $group_qry." and neraca_period like '".$year_month_before."%'";
             $value_year_min1_qry = $group_qry." and neraca_period like '".$year_min1."%'";
             $value_year_min2_qry = $group_qry." and neraca_period like '".$year_min2."%'";
             $value_now           = $this->db->query($value_now_qry)->getRow();
             $value_before        = $this->db->query($value_before_qry)->getRow();
             $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
             $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();


            $data['group_val_now'][$list_group['neraca_id']]    = (!empty($value_now->neraca_value)?int_to_jt($value_now->neraca_value):'');
            $data['group_val_before'][$list_group['neraca_id']] = (!empty($value_before->neraca_value)?int_to_jt($value_before->neraca_value):'');
            $data['group_val_year_min1'][$list_group['neraca_id']] = (!empty($value_year_min1->neraca_value)?int_to_jt($value_year_min1->neraca_value):'');
            $data['group_val_year_min2'][$list_group['neraca_id']] = (!empty($value_year_min2->neraca_value)?int_to_jt($value_year_min2->neraca_value):'');
      
            $data['sub_List'][$list_group['neraca_id']] = $neraca->where("status","1")->where("neraca_group",$this->mydata['group_type'])
                                                             ->where("neraca_type","2")
                                                             ->where("reference",$list_group['neraca_id'])  
                                                             ->where("neraca_period LIKE '".$year_month."%'", NULL, FALSE)
                                                             ->orderBy("neraca_number","asc")->findAll();
                               
            foreach($data['sub_List'][$list_group['neraca_id']] as $list_sub){
              $sub_qry = "select * from tr_keu_neraca where status='1' and neraca_group='".$this->mydata['group_type']."' and neraca_type='2'
                   and neraca_name='".$list_sub['neraca_name']."'";
              $value_now_qry       = $sub_qry." and neraca_period like '".$year_month."%'";
              $value_before_qry    = $sub_qry." and neraca_period like '".$year_month_before."%'";
              $value_year_min1_qry = $sub_qry." and neraca_period like '".$year_min1."%'";
              $value_year_min2_qry = $sub_qry." and neraca_period like '".$year_min2."%'";
              $value_now           = $this->db->query($value_now_qry)->getRow();
              $value_before        = $this->db->query($value_before_qry)->getRow();
              $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
              $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();


                $data['sub_val_now'][$list_sub['neraca_id']]    = (!empty($value_now->neraca_value)?int_to_jt($value_now->neraca_value):'');
                $data['sub_val_before'][$list_sub['neraca_id']] = (!empty($value_before->neraca_value)?int_to_jt($value_before->neraca_value):'');
                $data['sub_val_year_min1'][$list_sub['neraca_id']] = (!empty($value_year_min1->neraca_value)?int_to_jt($value_year_min1->neraca_value):'');
                $data['sub_val_year_min2'][$list_sub['neraca_id']] = (!empty($value_year_min2->neraca_value)?int_to_jt($value_year_min2->neraca_value):'');

                $data['child_List'][$list_sub['neraca_id']] = $neraca->where("status","1")
                                                                   ->where("neraca_group",$this->mydata['group_type'])
                                                                   ->where("neraca_type","3")
                                                                   ->where("reference",$list_sub['neraca_id'])  
                                                                   ->where("neraca_period LIKE '".$year_month."%'", NULL, FALSE)
                                                                   ->orderBy("neraca_number","asc")->findAll();

                foreach($data['child_List'][$list_sub['neraca_id']] as $list_child){
                   $child_qry = "select * from tr_keu_neraca where status='1' and neraca_group='".$this->mydata['group_type']."' and neraca_type='3'
                   and neraca_name='".$list_child['neraca_name']."'";

                   $value_now_qry       = $child_qry." and neraca_period like '".$year_month."%'";
                   $value_before_qry    = $child_qry." and neraca_period like '".$year_month_before."%'";
                   $value_year_min1_qry = $child_qry." and neraca_period like '".$year_min1."%'";
                   $value_year_min2_qry = $child_qry." and neraca_period like '".$year_min2."%'";
                   $value_now           = $this->db->query($value_now_qry)->getRow();
                   $value_before        = $this->db->query($value_before_qry)->getRow();
                   $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
                   $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

                   $data['child_val_now'][$list_child['neraca_id']]    = (!empty($value_now->neraca_value)?int_to_jt($value_now->neraca_value):'');
                   $data['child_val_before'][$list_child['neraca_id']] = (!empty($value_before->neraca_value)?int_to_jt($value_before->neraca_value):'');
                   $data['child_val_year_min1'][$list_child['neraca_id']] = (!empty($value_year_min1->neraca_value)?int_to_jt($value_year_min1->neraca_value):'');
                   $data['child_val_year_min2'][$list_child['neraca_id']] = (!empty($value_year_min2->neraca_value)?int_to_jt($value_year_min2->neraca_value):'');
                }                                                                  
              }                                                 
        }
        $data['lbl_year_month_ind']        = get_date_indonesia($year_month."-01",'month_year'); 
        $data['lbl_year_month_before_ind'] = get_date_indonesia($year_month_before."-01",'month_year');
        $data['lbl_year_min1']             = $year_min1;
        $data['lbl_year_min2']             = $year_min2;
        return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_table_monthly", $data);
      }
      else if($filter_type=="2"){  //2 = Tahunan, 5 tahun belakang
        return $this->blade->render("yayasan/pages/laporan_keuangan/neraca_table_yearly", $data);
      }  
      
    }
  }

  public function LabaRugiTable(){
    if (!$this->session->has('session_id')) {
      echo "You must login again";
    } else {
      $labarugi            = new KeuLabaRugi();
      $req_filter_type     = @$this->request->getPost("filter_type");
      
      $filter_type         = "1"; 
      if(!empty($req_filter_type)){
        $filter_type       = $req_filter_type; 
      }

      if($filter_type=="1"){ // 1 = Bulan yang dipilih dan bulan sebelumnya
          $req_month = $this->request->getPost("filter_month");
          $req_year  = $this->request->getPost("filter_year");
          if(!empty($req_month) && !empty($req_year)){
            $year_month                    = $req_year."-".$req_month;
            $year_month_before             = minplusDate($year_month,'-1 month','Y-m');
            $data['lbl_year_now']          = $req_year;
          }
          else {
            $year_month                    = date("Y-m");
            $year_month_before             = date("Y-m",strtotime("-1 month"));
            $data['lbl_year_now']          = date("Y");
          }
          $year_min1 = minplusDate($year_month,'-1 year','Y');
          $year_min2 = minplusDate($year_month,'-2 year','Y');  
          
          $data['group_List']           = $labarugi->where("status","1")->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                               ->where("laba_rugi_type","1")
                                               ->where("laba_rugi_period LIKE '".$year_month."%'", NULL, FALSE)
                                               ->orderBy("laba_rugi_number","asc")->findAll();
      foreach($data['group_List'] as $list_group){
             $group_qry = "select * from tr_keu_laba_rugi where status='1' and laba_rugi_group='".$this->mydata['group_type']."' and laba_rugi_type='1' and laba_rugi_name='".$list_group['laba_rugi_name']."'";
             $value_now_qry       = $group_qry." and laba_rugi_period like '".$year_month."%'";
             $value_before_qry    = $group_qry." and laba_rugi_period like '".$year_month_before."%'";
             $value_year_min1_qry = $group_qry." and laba_rugi_period like '".$year_min1."%'";
             $value_year_min2_qry = $group_qry." and laba_rugi_period like '".$year_min2."%'";
             $value_now           = $this->db->query($value_now_qry)->getRow();
             $value_before        = $this->db->query($value_before_qry)->getRow();
             $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
             $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

             $data['group_val_now'][$list_group['laba_rugi_id']]       = (!empty($value_now->laba_rugi_value)?int_to_rp($value_now->laba_rugi_value):'');
             $data['group_val_before'][$list_group['laba_rugi_id']]    = (!empty($value_before->laba_rugi_value)?int_to_rp($value_before->laba_rugi_value):'');
             $data['group_val_year_min1'][$list_group['laba_rugi_id']] = (!empty($value_year_min1->laba_rugi_value)?int_to_rp($value_year_min1->laba_rugi_value):'');
             $data['group_val_year_min2'][$list_group['laba_rugi_id']] = (!empty($value_year_min2->laba_rugi_value)?int_to_rp($value_year_min2->laba_rugi_value):''); 
       
        $data['sub_List'][$list_group['laba_rugi_id']] = $labarugi->where("status","1")->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                                               ->where("laba_rugi_type","2")
                                                               ->where("reference",$list_group['laba_rugi_id'])  
                                                               ->where("laba_rugi_period LIKE '".$year_month."%'", NULL, FALSE)
                                                               ->orderBy("laba_rugi_number","asc")->findAll();
         foreach($data['sub_List'][$list_group['laba_rugi_id']] as $list_sub){
             
             $sub_qry = "select * from tr_keu_laba_rugi where status='1' and laba_rugi_group='".$this->mydata['group_type']."' and laba_rugi_type='2' and laba_rugi_name='".$list_sub['laba_rugi_name']."'";
             $value_now_qry       = $sub_qry." and laba_rugi_period like '".$year_month."%'";
             $value_before_qry    = $sub_qry." and laba_rugi_period like '".$year_month_before."%'";
             $value_year_min1_qry = $sub_qry." and laba_rugi_period like '".$year_min1."%'";
             $value_year_min2_qry = $sub_qry." and laba_rugi_period like '".$year_min2."%'";
             $value_now           = $this->db->query($value_now_qry)->getRow();
             $value_before        = $this->db->query($value_before_qry)->getRow();
             $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
             $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

             $data['sub_val_now'][$list_sub['laba_rugi_id']]       = (!empty($value_now->laba_rugi_value)?int_to_jt($value_now->laba_rugi_value):'');
             $data['sub_val_before'][$list_sub['laba_rugi_id']]    = (!empty($value_before->laba_rugi_value)?int_to_jt($value_before->laba_rugi_value):'');
             $data['sub_val_year_min1'][$list_sub['laba_rugi_id']] = (!empty($value_year_min1->laba_rugi_value)?int_to_jt($value_year_min1->laba_rugi_value):'');
             $data['sub_val_year_min2'][$list_sub['laba_rugi_id']] = (!empty($value_year_min2->laba_rugi_value)?int_to_jt($value_year_min2->laba_rugi_value):''); 

            $data['child_List'][$list_sub['laba_rugi_id']] = $labarugi->where("status","1")
                                                                   ->whereIn("laba_rugi_group", $this->mydata['group_type'])
                                                                   ->where("laba_rugi_type","3")
                                                                   ->where("reference",$list_sub['laba_rugi_id'])  
                                                                   ->where("laba_rugi_period LIKE '".$year_month."%'", NULL, FALSE)
                                                                   ->orderBy("laba_rugi_number","asc")->findAll();

            foreach($data['child_List'][$list_sub['laba_rugi_id']] as $list_child){
               $child_qry = "select * from tr_keu_laba_rugi where status='1' and laba_rugi_group='".$this->mydata['group_type']."' and laba_rugi_type='3' and laba_rugi_name='".$list_child['laba_rugi_name']."'";
               $value_now_qry       = $child_qry." and laba_rugi_period like '".$year_month."%'";
               $value_before_qry    = $child_qry." and laba_rugi_period like '".$year_month_before."%'";
               $value_year_min1_qry = $child_qry." and laba_rugi_period like '".$year_min1."%'";
               $value_year_min2_qry = $child_qry." and laba_rugi_period like '".$year_min2."%'";
               $value_now           = $this->db->query($value_now_qry)->getRow();
               $value_before        = $this->db->query($value_before_qry)->getRow();
               $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
               $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

               $data['child_val_now'][$list_child['laba_rugi_id']]       = (!empty($value_now->laba_rugi_value)?int_to_jt($value_now->laba_rugi_value):'');
               $data['child_val_before'][$list_child['laba_rugi_id']]    = (!empty($value_before->laba_rugi_value)?int_to_jt($value_before->laba_rugi_value):'');
               $data['child_val_year_min1'][$list_child['laba_rugi_id']] = (!empty($value_year_min1->laba_rugi_value)?int_to_jt($value_year_min1->laba_rugi_value):'');
               $data['child_val_year_min2'][$list_child['laba_rugi_id']] = (!empty($value_year_min2->laba_rugi_value)?int_to_jt($value_year_min2->laba_rugi_value):'');
            }                                                                  

         }                                                 
      }
        $data['lbl_year_min1']             = $year_min1;
        $data['lbl_year_month_ind']        = get_date_indonesia($year_month."-01",'month_year'); 
        $data['lbl_year_month_before_ind'] = get_date_indonesia($year_month_before."-01",'month_year');
        $data['lbl_ytd_before_ind']        = get_date_indonesia($year_month."-01",'month')." ".minplusDate($year_month,'-1 year','Y');
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_table_monthly", $data);
      }
      else if ($filter_type=="2"){ //2 = Tahunan, 5 tahun belakang
        return $this->blade->render("yayasan/pages/laporan_keuangan/laba_rugi_table_yearly", $data);
 
      }

      
    }
  }

  public function CashflowTable(){
    if (!$this->session->has('session_id')) {
      echo "You must login again";
    } else {
      $cashflow            = new KeuCashflow();
      $req_filter_type     = @$this->request->getPost("filter_type");
      
      $filter_type         = "1"; 
      if(!empty($req_filter_type)){
        $filter_type       = $req_filter_type; 
      }

      if($filter_type=="1"){ // 1 = Bulan yang dipilih dan bulan sebelumnya
        $req_month = $this->request->getPost("filter_month");
        $req_year  = $this->request->getPost("filter_year");
        if(!empty($req_month) && !empty($req_year)){
          $year_month                    = $req_year."-".$req_month;
          $year_month_before             = minplusDate($year_month,'-1 month','Y-m');
        }
        else {
          $year_month                    = date("Y-m");
          $year_month_before             = date("Y-m",strtotime("-1 month"));
        }
        $year_min1 = minplusDate($year_month,'-1 year','Y');
        $year_min2 = minplusDate($year_month,'-2 year','Y');

        $data['group_List']            = $cashflow->where("status","1")->where("cashflow_group",$this->mydata['group_type'])
                                          ->where("cashflow_period LIKE '".$year_month."%'", NULL, FALSE)
                                          ->orderBy("cashflow_number","asc")->findAll();
        foreach($data['group_List'] as $list_group){
             $group_qry = "select * from tr_keu_cashflow where status='1' and cashflow_group='".$this->mydata['group_type']."' and cashflow_name='".$list_group['cashflow_name']."'";
             $value_now_qry       = $group_qry." and cashflow_period like '".$year_month."%'";
             $value_before_qry    = $group_qry." and cashflow_period like '".$year_month_before."%'";
             $value_year_min1_qry = $group_qry." and cashflow_period like '".$year_min1."%'";
             $value_year_min2_qry = $group_qry." and cashflow_period like '".$year_min2."%'";
             $value_now           = $this->db->query($value_now_qry)->getRow();
             $value_before        = $this->db->query($value_before_qry)->getRow();
             $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
             $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

             $data['group_val_now'][$list_group['cashflow_id']]      = (!empty($value_now->cashflow_value)?int_to_jt($value_now->cashflow_value):'');
             $data['group_val_before'][$list_group['cashflow_id']]    = (!empty($value_before->cashflow_value)?int_to_jt($value_before->cashflow_value):'');
             $data['group_val_year_min1'][$list_group['cashflow_id']] = (!empty($value_year_min1->cashflow_value)?int_to_jt($value_year_min1->cashflow_value):'');
             $data['group_val_year_min2'][$list_group['cashflow_id']] = (!empty($value_year_min2->cashflow_value)?int_to_jt($value_year_min2->cashflow_value):'');                                          
        }

        $data['lbl_year_month_ind']        = get_date_indonesia($year_month."-01",'month_year'); 
        $data['lbl_year_month_before_ind'] = get_date_indonesia($year_month_before."-01",'month_year');
        $data['lbl_year_min1']             = $year_min1;
        $data['lbl_year_min2']             = $year_min2;
        return $this->blade->render("yayasan/pages/laporan_keuangan/cashflow_table_monthly", $data);
      }
      else if ($filter_type=="2"){ //2 = Tahunan, 5 tahun belakang
        return $this->blade->render("yayasan/pages/laporan_keuangan/cashflow_table_yearly", $data);
      }
    }
  }

  public function CapexTable(){
    if (!$this->session->has('session_id')) {
      echo "You must login again";
    } else {
      $capex          = new KeuCapex();
      
      $req_filter_type     = @$this->request->getPost("filter_type");
      
      $filter_type         = "1"; 
      if(!empty($req_filter_type)){
        $filter_type       = $req_filter_type; 
      }

      if($filter_type=="1"){ // 1 = Bulan yang dipilih dan bulan sebelumnya
        $req_month = $this->request->getPost("filter_month");
        $req_year  = $this->request->getPost("filter_year");
        if(!empty($req_month) && !empty($req_year)){
          $year_month                    = $req_year."-".$req_month;
          $year_month_before             = minplusDate($year_month,'-1 month','Y-m');
          $ytd_before                    = minplusDate($year_month,'-1 year -1 month','Y');
          $data['lbl_year_now']          = $req_year;
        }
        else {
          $year_month                    = date("Y-m");
          $year_month_before             = date("Y-m",strtotime("-1 month"));
          $ytd_before                    = minplusDate($year_month,'-1 year -1 month','Y');
          $data['lbl_year_now']          = date("Y");
        }
        $year_min1 = minplusDate($year_month,'-1 year','Y');
        $year_min2 = minplusDate($year_month,'-2 year','Y');

        
        $data['year_month']                = $year_month;
        $data['year_month_before']         = $year_month_before;

        $data['group_List']            = $capex->where("status","1")->where("capex_group",$this->mydata['group_type'])
                                               ->where("capex_period LIKE '".$year_month."%'", NULL, FALSE)
                                               ->orderBy("capex_number","asc")->findAll();
        foreach($data['group_List'] as $list_group){
            $group_qry = "select * from tr_keu_capex where status='1' and capex_group='".$this->mydata['group_type']."' and capex_name='".$list_group['capex_name']."'";
             $value_now_qry       = $group_qry." and capex_period like '".$year_month."%'";
             $value_before_qry    = $group_qry." and capex_period like '".$year_month_before."%'";
             $value_year_min1_qry = $group_qry." and capex_period like '".$year_min1."%'";
             $value_year_min2_qry = $group_qry." and capex_period like '".$year_min2."%'";
             $value_now           = $this->db->query($value_now_qry)->getRow();
             $value_before        = $this->db->query($value_before_qry)->getRow();
             $value_year_min1     = $this->db->query($value_year_min1_qry)->getRow();
             $value_year_min2     = $this->db->query($value_year_min2_qry)->getRow();

             $data['group_val_now'][$list_group['capex_id']]      = (!empty($value_now->capex_value)?int_to_jt($value_now->capex_value):'');
             $data['group_val_before'][$list_group['capex_id']]    = (!empty($value_before->capex_value)?int_to_jt($value_before->capex_value):'');
             $data['group_val_year_min1'][$list_group['capex_id']] = (!empty($value_year_min1->capex_value)?int_to_jt($value_year_min1->capex_value):'');
             $data['group_val_year_min2'][$list_group['capex_id']] = (!empty($value_year_min2->capex_value)?int_to_jt($value_year_min2->cashflow_value):'');                                           
        }

        $data['lbl_year_min1']             = $year_min1;
        $data['lbl_year_month_ind']        = get_date_indonesia($year_month."-01",'month_year'); 
        $data['lbl_year_month_before_ind'] = get_date_indonesia($year_month_before."-01",'month_year');
        $data['lbl_ytd_before_ind']        = get_date_indonesia($year_month."-01",'month')." ".minplusDate($year_month,'-1 year','Y');
        return $this->blade->render("yayasan/pages/laporan_keuangan/capex_table_monthly", $data);
      }else if ($filter_type=="2"){ //2 = Tahunan, 5 tahun belakang
        return $this->blade->render("yayasan/pages/laporan_keuangan/capex_table_yearly", $data);
      } 

    }
  }

  // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
  public function FilterChartKasSetaraKas()
  {
    $filter_type     = $this->request->getPost('tipeFilter');
    $tahunTerakhir   = $this->request->getPost('parameter');
    $chartType       = $this->request->getPost('chart');
    $quarterSelected = $this->request->getPost('q');

    if (empty($filter_type)) {
      $filter_type = "tahun";
    }
    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $viewKasSetaraKas  = new KeuNeraca();

    // GET KAS SETARA KAS NAME
    $KasSetaraKas = [
      0 => "Bank",
      1 => "Deposito Berjangka",
      2 => "Kas"
    ];
    // END KAS SETARA KAS NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];
    $totalQPerCategories     = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0
    ];

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if (strlen($tahunTerakhir) > 4) {
      $tahunTerakhir = date("Y");
    }
    for ($thn = $tahunTerakhir-5; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    if ($filter_type == "tahun") {
      // VARIABEL UNTUK CHART
      $chartKasSetaraKas = array();

      // START MAIN FUNCTION
      foreach ($KasSetaraKas as $value) {
        $chartKasSetaraKas[$i]["name"] = $value;

        $data_array  = array();
        for ($perthn = 1; $perthn <= 5; $perthn++) {
          $getValue = $viewKasSetaraKas->select("SUM(neraca_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_value is NOT NULL", NULL, FALSE)
          ->where("neraca_quarter_value is NULL", NULL, FALSE)
          ->where("neraca_value <>", 0)
          ->where("neraca_period", $listTahun[$perthn] . "-12")
          ->first();

          if (empty($getValue["Total"])) {
            $getValue  =  $viewKasSetaraKas->select('SUM(neraca_value) as Total')
            ->where("status", "1")
            ->where("group_type", "1")
            ->where("neraca_name", $value)
            ->whereIn("neraca_group", $this->mydata['group_type'])
            ->where("neraca_value is NOT NULL", NULL, FALSE)
            ->where("neraca_quarter_value is NULL", NULL, FALSE)
            ->where("neraca_value <>", 0)
            ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
            ->groupBy("neraca_period")
            ->orderBy("neraca_period", "DESC")
            ->first();

            if (empty($getValue["Total"])) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, (int)$getValue["Total"] / 1000000000);
            }
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartKasSetaraKas[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
          $totalPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        $getValueTahunKeEnam = $viewKasSetaraKas->select("SUM(neraca_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("neraca_period", $listTahun[0] . "-12")
        ->first();
        if (empty($getValueTahunKeEnam["Total"])) {
          $valueKe6 = 0;
        } else {
          $valueKe6 = $getValueTahunKeEnam["Total"];
        }
        $totalPerCategories[0] += (int)$valueKe6 / 1000000000;

        $i++;
      }
      // END MAIN FUNCTION

      $LastFiveYears = [];
      for ($i = 1; $i <= 5; $i++) {
        $LastFiveYears[] = "$listTahun[$i]";
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartKasSetaraKas"]       = json_encode($chartKasSetaraKas);
      $data["chartCategories"]         = json_encode($LastFiveYears);
      $data["tableKasSetaraKas"]       = $chartKasSetaraKas;
      $data["tableCategories"]         = $LastFiveYears;
      $data["tableTotalPerCategories"] = $totalPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/kassetarakas_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    } else if ($filter_type == "quarter") {
      // VARIABEL UNTUK CHART
      $chartKasSetaraKas = array();

      // START MAIN FUNCTION
      foreach ($KasSetaraKas as $value) {
        $chartKasSetaraKas[$i]["name"] = $value;

        $data_array  = array();
        for ($quarter = 1; $quarter <= 4; $quarter++) {

          $getMonthQuarter = month_quarter($quarter);
          $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
          $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
          $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];

          $getValue = $viewKasSetaraKas->select("SUM(neraca_quarter_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
          ->where("neraca_value is NULL", NULL, FALSE)
          ->where("neraca_quarter_value <>", 0)
          ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
          ->first();

          if (empty($getValue["Total"])) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartKasSetaraKas[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 3; $totalCJM++) {
          $totalQPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        // AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM
        $getMonthQuarter = month_quarter(4);
        $dt1 = $tahunTerakhir-1 . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir-1 . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir-1 . "-" . $getMonthQuarter[2];

        $getValueQ4LastYear = $viewKasSetaraKas->select("SUM(neraca_quarter_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value is NULL", NULL, FALSE)
        ->where("neraca_quarter_value <>", 0)
        ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValueQ4LastYear["Total"])) {
          $valueQ4LastYear = 0;
        } else {
          $valueQ4LastYear = $getValueQ4LastYear["Total"];
        }
        $totalQPerCategories[0] += (int)$valueQ4LastYear / 1000000000;
        // END AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM

        $i++;
      }
      // END MAIN FUNCTION

      for ($i = 1; $i <= 4; $i++) {
        $ListQuarter[] = "Q" . "$i" . " - " . $tahunTerakhir;
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartKasSetaraKas"]       = json_encode($chartKasSetaraKas);
      $data["chartCategories"]         = json_encode($ListQuarter);
      $data["tableKasSetaraKas"]       = $chartKasSetaraKas;
      $data["tableCategories"]         = $ListQuarter;
      $data["tableTotalPerCategories"] = $totalQPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/kassetarakas_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    } else if ($filter_type == "quarter_komparasi") {
      if (empty($quarterSelected)) {
        $quarterSelected = "Q1";
      }
      // VARIABEL UNTUK CHART
      $chartKasSetaraKas = array();

      // START MAIN FUNCTION
      foreach ($KasSetaraKas as $value) {
        $chartKasSetaraKas[$i]["name"] = $value;

        $data_array  = array();
        for ($perthn = 1; $perthn <= 5; $perthn++) {
          $getMonthQuarter = month_quarter($quarterSelected);
          $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
          $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
          $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];

          $getValue = $viewKasSetaraKas->select("SUM(neraca_quarter_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
          ->where("neraca_value is NULL", NULL, FALSE)
          ->where("neraca_quarter_value <>", 0)
          ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
          ->first();

          if (empty($getValue["Total"])) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartKasSetaraKas[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
          $totalPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        // AMBIL NILAI (QUARTER SELECTED) DARI TAHUN SEBELUM
        $getMonthQuarter = month_quarter($quarterSelected);
        $dt1 = $listTahun[0] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[0] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[0] . "-" . $getMonthQuarter[2];

        $getValueQLastYear  =  $viewKasSetaraKas->select("SUM(neraca_quarter_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value is NULL", NULL, FALSE)
        ->where("neraca_quarter_value <>", 0)
        ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValueQLastYear["Total"])) {
          $valueQLastYear = 0;
        } else {
          $valueQLastYear = $getValueQLastYear["Total"];
        }
        $totalPerCategories[0] += (int)$valueQLastYear / 1000000000;
        // END AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM

        $i++;
      }
      // END MAIN FUNCTION

      for ($i = 1; $i <= 5; $i++) {
        $ListYearQuarter[] = $quarterSelected . " - " . $listTahun[$i];
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartKasSetaraKas"]       = json_encode($chartKasSetaraKas);
      $data["chartCategories"]         = json_encode($ListYearQuarter);
      $data["tableKasSetaraKas"]       = $chartKasSetaraKas;
      $data["tableCategories"]         = $ListYearQuarter;
      $data["tableTotalPerCategories"] = $totalPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/kassetarakas_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    }

  }

  public function FilterChartTrendInvestasi()
  {
    $year = $this->request->getPost("parameter");

    // PANGGIL MODEL
    $viewTrendPendInvestasi = new KeuLabaRugi();

    // GET TREND PEND INVESTASI NAME
    $trendPendInvestasiItem = [
      0 => "Bunga Deposito Berjangka",
      1 => "Bunga Obligasi",
      2 => "Laba Reksadana",
      3 => "Capital Gain",
      4 => "Laba (rugi) anak perusahaan"
    ];
    foreach ($trendPendInvestasiItem as $value) {
      $TrendPendapatanInvestasi[] = $value;
    }
    // END TREND PEND INVESTASI NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL UNTUK CHART
    $chartTrendPendapatanInvestasi = array();
    $chartColor                    = array();

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $listTahun = [];
    if (strlen($year) > 4) {
      $year = date("Y");
    }
    for ($thn = ($year - 4); $thn <= $year; $thn++) {
      $listTahun[] = $thn;
    }

    // START MAIN FUNCTION
    if (!empty($TrendPendapatanInvestasi)) {
      foreach ($TrendPendapatanInvestasi as $value) {

        $chartTrendPendapatanInvestasi[$i]["name"] = $value;
        $chartColor[] = $this->mydata["color"][$i];

        $data_array = array();
        for ($perthn = 0; $perthn <= 4; $perthn++) {
          $getValue = $viewTrendPendInvestasi->select("SUM(laba_rugi_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("laba_rugi_name", $value)
          ->whereIn("laba_rugi_group", $this->mydata['group_type'])
          ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
          ->where("laba_rugi_quarter_value is NULL", NULL, FALSE)
          ->where("laba_rugi_value <>", 0)
          ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
          ->first();
          if (empty($getValue["Total"])) {
            $getValue = $viewTrendPendInvestasi->select('SUM(laba_rugi_value) as Total')
            ->where("status", "1")
            ->where("group_type", "1")
            ->where("laba_rugi_name", $value)
            ->whereIn("laba_rugi_group", $this->mydata['group_type'])
            ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
            ->where("laba_rugi_quarter_value is NULL", NULL, FALSE)
            ->where("laba_rugi_value <>", 0)
            ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
            ->groupBy("laba_rugi_period")
            ->orderBy("laba_rugi_period", "DESC")
            ->first();

            if (!empty($getValue["Total"])) {
              $result = (int)$getValue["Total"] / 1000000000;
            } else {
              $result = 0;
            }
            if ($result < 0) {
              $result = $result * -1;
            } else {
              $result = $result;
            }
            array_push($data_array, $result);
          } else {
            if ((int)$getValue["Total"] < 0) {
              $result = (int)$getValue["Total"]*-1;
            } else {
              $result = (int)$getValue["Total"];
            }
            array_push($data_array, $result / 1000000000);
          }
        }

        $chartTrendPendapatanInvestasi[$i]["data"] = $data_array;

        $i++;
      }
    }
    // END MAIN FUNCTION

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartTrendPendapatanInvestasi"] = json_encode($chartTrendPendapatanInvestasi);
    $data["chartColor"]                    = json_encode($chartColor);
    $data["chartCategories"]               = json_encode($listTahun);
    $data["tableListTahun"]                = $listTahun;
    $data["tableTrendPendapatanInvestasi"] = $chartTrendPendapatanInvestasi;

    return $this->blade->render("yayasan/pages/laporan_keuangan/trend_pendapatan_chart", $data);
  }

  public function FilterChartInvestasi()
  {
    $filter_type     = $this->request->getPost('tipeFilter');
    $tahunTerakhir   = $this->request->getPost('parameter');
    $chartType       = $this->request->getPost('chart');
    $quarterSelected = $this->request->getPost('q');

    if (empty($filter_type)) {
      $filter_type = "tahun";
    }
    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $viewInvestasi  = new KeuNeraca();

    // GET INVESTASI NAME
    $getInvestasiName = [
      0 => "Deposito Berjangka",
      1 => "Surat Berharga",
      2 => "PT - Anak Perusahaan"
    ];
    foreach ($getInvestasiName as $item) {
      $Investasi[] = $item;
    }
    // END INVESTASI NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];
    $totalQPerCategories     = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0
    ];

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if (strlen($tahunTerakhir) > 4) {
      $tahunTerakhir = date("Y");
    }
    for ($thn = $tahunTerakhir-5; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    if ($filter_type == "tahun") {
      // VARIABEL UNTUK CHART
      $chartInvestasi = array();

      // START MAIN FUNCTION
      foreach ($Investasi as $value) {
        $chartInvestasi[$i]["name"] = $value;

        $data_array  = array();
        for ($perthn = 1; $perthn <= 5; $perthn++) {
          $getValue = $viewInvestasi->select("SUM(neraca_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", "Investasi " . $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_value is NOT NULL", NULL, FALSE)
          ->where("neraca_quarter_value is NULL", NULL, FALSE)
          ->where("neraca_value <>", 0)
          ->where("neraca_period", $listTahun[$perthn] . "-12")
          ->first();

          if (empty($getValue["Total"])) {
            $getValue = $viewInvestasi->select('SUM(neraca_value) as Total')
            ->where("status", "1")
            ->where("group_type", "1")
            ->where("neraca_name", "Investasi " . $value)
            ->whereIn("neraca_group", $this->mydata['group_type'])
            ->where("neraca_value is NOT NULL", NULL, FALSE)
            ->where("neraca_quarter_value is NULL", NULL, FALSE)
            ->where("neraca_value <>", 0)
            ->where("LEFT(neraca_period, 4)", $listTahun[$perthn])
            ->groupBy("neraca_period")
            ->orderBy("neraca_period", "DESC")
            ->first();

            if (empty($getValue["Total"])) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, (int)$getValue["Total"] / 1000000000);
            }
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartInvestasi[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
          $totalPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        $getValueTahunKeEnam = $viewInvestasi->select("SUM(neraca_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", "Investasi " . $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value <>", 0)
        ->where("neraca_period", $listTahun[0] . "-12")
        ->first();
        if (empty($getValueTahunKeEnam["Total"])) {
          $valueKe6 = 0;
        } else {
          $valueKe6 = $getValueTahunKeEnam["Total"];
        }
        $totalPerCategories[0] += (int)$valueKe6 / 1000000000;

        $i++;
      }
      // END MAIN FUNCTION

      $LastFiveYears = [];
      for ($i = 1; $i <= 5; $i++) {
        $LastFiveYears[] = "$listTahun[$i]";
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartInvestasi"]          = json_encode($chartInvestasi);
      $data["chartCategories"]         = json_encode($LastFiveYears);
      $data["tableInvestasi"]          = $chartInvestasi;
      $data["tableCategories"]         = $LastFiveYears;
      $data["tableTotalPerCategories"] = $totalPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/investasi_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    } else if ($filter_type == "quarter") {
      // VARIABEL UNTUK CHART
      $chartInvestasi = array();

      // START MAIN FUNCTION
      foreach ($Investasi as $value) {
        $chartInvestasi[$i]["name"] = $value;

        $data_array  = array();
        for ($quarter = 1; $quarter <= 4; $quarter++) {

          $getMonthQuarter = month_quarter($quarter);
          $dt1 = $tahunTerakhir . "-" . $getMonthQuarter[0];
          $dt2 = $tahunTerakhir . "-" . $getMonthQuarter[1];
          $dt3 = $tahunTerakhir . "-" . $getMonthQuarter[2];

          $getValue = $viewInvestasi->select("SUM(neraca_quarter_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", "Investasi " . $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
          ->where("neraca_value is NULL", NULL, FALSE)
          ->where("neraca_quarter_value <>", 0)
          ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartInvestasi[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 3; $totalCJM++) {
          $totalQPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        // AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM
        $getMonthQuarter = month_quarter(4);
        $dt1 = $tahunTerakhir-1 . "-" . $getMonthQuarter[0];
        $dt2 = $tahunTerakhir-1 . "-" . $getMonthQuarter[1];
        $dt3 = $tahunTerakhir-1 . "-" . $getMonthQuarter[2];

        $getValueQ4LastYear = $viewInvestasi->select("SUM(neraca_quarter_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", "Investasi " . $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value is NULL", NULL, FALSE)
        ->where("neraca_quarter_value <>", 0)
        ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValueQ4LastYear["Total"])) {
          $valueQ4LastYear = 0;
        } else {
          $valueQ4LastYear = $getValueQ4LastYear["Total"];
        }
        $totalQPerCategories[0] += (int)$valueQ4LastYear / 1000000000;
        // END AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM

        $i++;
      }
      // END MAIN FUNCTION

      for ($i = 1; $i <= 4; $i++) {
        $ListQuarter[] = "Q" . "$i" . " - " . $tahunTerakhir;
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartInvestasi"]          = json_encode($chartInvestasi);
      $data["chartCategories"]         = json_encode($ListQuarter);
      $data["tableInvestasi"]          = $chartInvestasi;
      $data["tableCategories"]         = $ListQuarter;
      $data["tableTotalPerCategories"] = $totalQPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/investasi_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    } else if ($filter_type == "quarter_komparasi") {
      if (empty($quarterSelected)) {
        $quarterSelected = "Q1";
      }
      // VARIABEL UNTUK CHART
      $chartInvestasi = array();

      // START MAIN FUNCTION
      foreach ($Investasi as $value) {
        $chartInvestasi[$i]["name"] = $value;

        $data_array  = array();
        for ($perthn = 1; $perthn <= 5; $perthn++) {
          $getMonthQuarter = month_quarter($quarterSelected);
          $dt1 = $listTahun[$perthn] . "-" . $getMonthQuarter[0];
          $dt2 = $listTahun[$perthn] . "-" . $getMonthQuarter[1];
          $dt3 = $listTahun[$perthn] . "-" . $getMonthQuarter[2];

          $getValue = $viewInvestasi->select("SUM(neraca_quarter_value) as Total")
          ->where("status", "1")
          ->where("group_type", "1")
          ->where("neraca_name", "Investasi " . $value)
          ->whereIn("neraca_group", $this->mydata['group_type'])
          ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
          ->where("neraca_value is NULL", NULL, FALSE)
          ->where("neraca_quarter_value <>", 0)
          ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
          ->first();

          if (empty($getValue)) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          }
        }

        $chartInvestasi[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
          $totalPerCategories[$totalCJM+1] += $data_array[$totalCJM];
        }

        // AMBIL NILAI (QUARTER SELECTED) DARI TAHUN SEBELUM
        $getMonthQuarter = month_quarter($quarterSelected);
        $dt1 = $listTahun[0] . "-" . $getMonthQuarter[0];
        $dt2 = $listTahun[0] . "-" . $getMonthQuarter[1];
        $dt3 = $listTahun[0] . "-" . $getMonthQuarter[2];

        $getValueQLastYear = $viewInvestasi->select("SUM(neraca_quarter_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", "Investasi " . $value)
        ->whereIn("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NOT NULL", NULL, FALSE)
        ->where("neraca_value is NULL", NULL, FALSE)
        ->where("neraca_quarter_value <>", 0)
        ->whereIn("neraca_period", [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValueQLastYear["Total"])) {
          $valueQLastYear = 0;
        } else {
          $valueQLastYear = $getValueQLastYear["Total"];
        }
        $totalPerCategories[0] += (int)$valueQLastYear / 1000000000;
        // END AMBIL NILAI (QUARTER 4) DARI TAHUN SEBELUM

        $i++;
      }
      // END MAIN FUNCTION

      for ($i = 1; $i <= 5; $i++) {
        $ListYearQuarter[] = $quarterSelected . " - " . $listTahun[$i];
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartInvestasi"]          = json_encode($chartInvestasi);
      $data["chartCategories"]         = json_encode($ListYearQuarter);
      $data["tableInvestasi"]          = $chartInvestasi;
      $data["tableCategories"]         = $ListYearQuarter;
      $data["tableTotalPerCategories"] = $totalPerCategories;

      if ($chartType == "bar") {
        return $this->blade->render("yayasan/pages/laporan_keuangan/investasi_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    }

  }

  public function FilterChartPengeluaranVesCapex()
  {
    $tahunTerakhir   = $this->request->getPost('parameter');
    $chartType       = $this->request->getPost('chart');

    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $viewPengVesCapex = new ViewPengeluaranInvestasiCapex();

    // GET PENGELUARAN INVESTASI CAPEX NAME
    $getPengeluaranInvestasiCapexName = $viewPengVesCapex->select("PengeluaranInvesCapex")->whereIn("PengeluaranInvesCapex", ["Gedung & CIP", "Peralatan dan Prasarana", "Tanah"])->groupBy("PengeluaranInvesCapex")->orderBy("PengeluaranInvesCapex", "ASC")->findAll();
    foreach ($getPengeluaranInvestasiCapexName as $item) {
      $PengeluaranInvestasiCapex[] = $item["PengeluaranInvesCapex"];
    }
    // END PENGELUARAN INVESTASI CAPEX NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if (strlen($tahunTerakhir) > 4) {
      $tahunTerakhir = date("Y");
    }
    $back_year = $tahunTerakhir-5;
    for ($thn = $back_year; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $chartPengeluaranInvesCapex = array();

    // START MAIN FUNCTION
    foreach ($PengeluaranInvestasiCapex as $value) {
      $chartPengeluaranInvesCapex[$i]["name"] = $value;

      $data_array  = array();

      // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG DI GANTI $perthn ke 1-5
      for ($perthn = 1; $perthn <= 5; $perthn++) {
        $getValue  =  $viewPengVesCapex->select("SUM(Total) as Total")
        ->where("PengeluaranInvesCapex", $value)
        ->whereIn("GroupData", $this->mydata['group_data'])
        ->where("Period", "ADJ-" . substr($listTahun[$perthn], -2))
        ->first();
        if ($value == "Tanah") {
          $sut = "Penyusutan Tanah";
        } elseif ($value == "Gedung & CIP") {
          $sut = "Penyusutan Gedung";
        } elseif ($value == "Peralatan dan Prasarana") {
          $sut = "Penyusutan Peralatan dan Prasarana";
        }
        $getValue2  =  $viewPengVesCapex->select("SUM(Total) as Total")
        ->where("PengeluaranInvesCapex", $sut)
        ->whereIn("GroupData", $this->mydata['group_data'])
        ->where("Period", "ADJ-" . substr($listTahun[$perthn], -2))
        ->first();

        if (empty($getValue)) {
          if (empty($getValue2["Total"])) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, 0 - (int)$getValue2["Total"]);
          }
        } else {
          // array_push($data_array, (int)$getValue["Total"]);
          // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG HAPUS COMMENT INI
          if (empty($getValue2["Total"])) {
            array_push($data_array, (int)$getValue["Total"] / 1000000000);
          } else {
            array_push($data_array, (int)($getValue["Total"]-(int)$getValue2["Total"]) / 1000000000);
          }
          // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG HAPUS COMMENT INI
        }
      }
      
      // $data_final = array();
      
      // for ($perthn=0; $perthn <= 4; $perthn++) { 
      //   $data_final[] = ($data_array[$perthn+1]-$data_array[$perthn])/1000000000;
      // }

      // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG GANTI $data_final menjadi $data_array
      $chartPengeluaranInvesCapex[$i]["data"] = $data_array;
      // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG GANTI $data_final menjadi $data_array

      // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
      // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG GANTI $data_final menjadi $data_array
      for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
        $totalPerCategories[$totalCJM+1] += $data_array[$totalCJM];
      }
      // KALAU TIDAK JADI DIKURANG DENGAN TAHUN SEBELUMNYA TOLONG GANTI $data_final menjadi $data_array

      $getValueTahunKeEnam = $viewPengVesCapex->select("SUM(Total) as Total")
      ->where("PengeluaranInvesCapex", $value)
      ->whereIn("GroupData", $this->mydata['group_data'])
      ->where("Period", "ADJ-" . substr($listTahun[0], -2))
      ->first();
      if ($value == "Tanah") {
        $sut = "Penyusutan Tanah";
      } elseif ($value == "Gedung & CIP") {
        $sut = "Penyusutan Gedung";
      } elseif ($value == "Peralatan dan Prasarana") {
        $sut = "Penyusutan Peralatan dan Prasarana";
      }
      $getValueTahunKeEnam2 = $viewPengVesCapex->select("SUM(Total) as Total")
      ->where("PengeluaranInvesCapex", $sut)
      ->whereIn("GroupData", $this->mydata['group_data'])
      ->where("Period", "ADJ-" . substr($listTahun[0], -2))
      ->first();
      if (empty($getValueTahunKeEnam["Total"])) {
        if (empty($getValueTahunKeEnam2["Total"])) {
          $valueKe6 = 0;
        } else {
          $valueKe6 = 0 - (int)$getValueTahunKeEnam2["Total"];
        }
      } else {
        if (empty($getValueTahunKeEnam2["Total"])) {
          $valueKe6 = (int)$getValueTahunKeEnam["Total"];
        } else {
          $valueKe6 = (int)$getValueTahunKeEnam["Total"] - (int)$getValueTahunKeEnam2["Total"];
        }
      }
      $totalPerCategories[0] += (int)$valueKe6 / 1000000000;

      $i++;
    }
    // END MAIN FUNCTION

    $LastFiveYears = [];
    for ($i = 1; $i <= 5; $i++) {
      $LastFiveYears[] = "$listTahun[$i]";
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartPengeluaranInvesCapex"] = json_encode($chartPengeluaranInvesCapex);
    $data["chartCategories"]            = json_encode($LastFiveYears);
    $data["tablePengeluaranInvesCapex"] = $chartPengeluaranInvesCapex;
    $data["tableCategories"]            = $LastFiveYears;
    $data["tableTotalPerCategories"]    = $totalPerCategories;

    if ($chartType == "bar") {
      return $this->blade->render("yayasan/pages/laporan_keuangan/pengeluaran_inves_capex", $data);
    } else if ($chartType == "pie") {
      return redirect()->to('/');
    }
    
  }

  public function FilterChartAsetTetap()
  {
    $tahunTerakhir   = $this->request->getPost('parameter');
    $chartType       = $this->request->getPost('chart');

    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $viewAsetTetap = new ViewAsetTetap();

    // GET ASET TETAP NAME
    $getAsetTetapName = $viewAsetTetap->select("AsetTetap")->groupBy("AsetTetap")->orderBy("AsetTetap", "ASC")->findAll();
    foreach ($getAsetTetapName as $item) {
      $AsetTetap[] = $item["AsetTetap"];
    }
    // END ASET TETAP NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0
    ];

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if (strlen($tahunTerakhir) > 4) {
      $tahunTerakhir = date("Y");
    }
    for ($thn = $tahunTerakhir-5; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $chartAsetTetap = array();

    // START MAIN FUNCTION
    foreach ($AsetTetap as $value) {
      $chartAsetTetap[$i]["name"] = $value;

      $data_array  = array();
      for ($perthn = 1; $perthn <= 5; $perthn++) {
        $getValue  =  $viewAsetTetap->select("SUM(Total) as Total")
        ->where("AsetTetap", $value)
        ->whereIn("GroupData", $this->mydata["group_data"])
        ->where("Period", "ADJ-" . substr($listTahun[$perthn], -2))
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, (int)$getValue["Total"] / 1000000000);
        }
      }

      $chartAsetTetap[$i]["data"] = $data_array;

      // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
      for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
        $totalPerCategories[$totalCJM] += $data_array[$totalCJM];
      }

      $i++;
    }

    // END MAIN FUNCTION

    $LastFiveYears = [];
    for ($i = 1; $i <= 5; $i++) {
      $LastFiveYears[] = "$listTahun[$i]";
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartAsetTetap"]          = json_encode($chartAsetTetap);
    $data["chartCategories"]         = json_encode($LastFiveYears);
    $data["tableAsetTetap"]          = $chartAsetTetap;
    $data["tableCategories"]         = $LastFiveYears;
    $data["tableTotalPerCategories"] = $totalPerCategories;

    if ($chartType == "bar") {
      return $this->blade->render("yayasan/pages/laporan_keuangan/aset_tetap_chart", $data);
    } else if ($chartType == "pie") {
      return redirect()->to('/');
    }
    
  }

  public function FilterChartAsetTetapKonsolidasi()
  {
    $tahunTerakhir   = $this->request->getPost('parameter');
    $chartType       = $this->request->getPost('chart');

    if (empty($chartType)) {
      $chartType = "bar";
    }

    // PANGGIL MODEL
    $viewAsetTetap = new KeuCapex();

    // GET ASET TETAP NAME
    $getAsetTetapName = [
      0 => "Tanah",
      1 => "Gedung & CIP",
      2 => "Peralatan dan Prasarana"
    ];
    foreach ($getAsetTetapName as $item) {
      $AsetTetap[] = $item;
    }
    // END ASET TETAP NAME

    // VARIABEL UNTUK INDEXING
    $i = 0;

    // VARIABEL PER CATEGORIES
    $totalPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];
    $totalAPBPerCategories      = [
      0 => 0,
      1 => 0,
      2 => 0,
      3 => 0,
      4 => 0,
      5 => 0
    ];

    // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
    $thn = 0;
    $listTahun = [];
    if (strlen($tahunTerakhir) > 4) {
      $tahunTerakhir = date("Y");
    }
    for ($thn = $tahunTerakhir - 5; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $chartAsetTetapKonsolidasi = array();

    // START MAIN FUNCTION
    foreach ($AsetTetap as $value) {
      $chartAsetTetapKonsolidasi[$i]["name"] = $value;

      $data_array     = array();
      $dataArrayFinal = array();
      for ($perthn = 0; $perthn <= 5; $perthn++) {
        $getValue = $viewAsetTetap->select("SUM(capex_value) as Total")
        ->where("status", "1")
        ->where("capex_name", "Investasi " . $value)
        ->whereIn("capex_group", $this->mydata['group_type'])
        ->where("capex_value is NOT NULL", NULL, FALSE)
        ->where("capex_value <>", 0)
        ->where("capex_period", $listTahun[$perthn] . "-12")
        ->first();

        if (empty($getValue["Total"])) {
          $getValue  =  $viewAsetTetap->select('SUM(capex_value) as Total')
          ->where("status", "1")
          ->where("capex_name", "Investasi " . $value)
          ->whereIn("capex_group", $this->mydata['group_type'])
          ->where("capex_value is NOT NULL", NULL, FALSE)
          ->where("capex_value <>", 0)
          ->where("LEFT(capex_period, 4)", $listTahun[$perthn])
          ->groupBy("capex_period")
          ->orderBy("capex_period", "DESC")
          ->first();

          if (empty($getValue["Total"])) {
            array_push($data_array, 0);
          } else {
            array_push($data_array, (int)$getValue["Total"]);
          }
        } else {
          array_push($data_array, (int)$getValue["Total"]);
        }
      }

      for ($perthn = 0; $perthn <= 4; $perthn++) {
        if ($data_array[$perthn + 1] != 0) {
          $hitung = $data_array[$perthn + 1] - $data_array[$perthn];
          array_push($dataArrayFinal, $hitung / 1000000000);
        } else {
          array_push($dataArrayFinal, 0);
        }
      }

      $chartAsetTetapKonsolidasi[$i]["data"] = $dataArrayFinal;

      // GET DATA APB
      $Budget    = new Budget();

      switch ($value) {
        case "Gedung & CIP":
          $budget_type = "3";
          break;
        case "Peralatan dan Prasarana":
          $budget_type = "4";
          break;
        case "Tanah":
          $budget_type = "5";
          break;
        default:
          $budget_type = "tidak_valid";
          break;
      }

      $dataAPB_array  = array();
      for ($perthn = 1; $perthn <= 5; $perthn++) {
        $getBudget = $Budget->select("SUM(budget_value) as Total")
        ->where("budget_type", $budget_type)
          ->whereIn("budget_group", ["1", "2"])
          ->where("budget_period", $listTahun[$perthn] . "-12")
          ->first();

        if (empty($getBudget["Total"])) {
          array_push($dataAPB_array, 0);
        } else {
          array_push($dataAPB_array, (int)$getBudget["Total"] / 1000000000);
        }
      }

      $chartAsetTetapKonsolidasi[$i]["dataAPB"] = $dataAPB_array;
      // GET DATA APB

      // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
      for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
        $totalPerCategories[$totalCJM + 1] += $dataArrayFinal[$totalCJM];
        $totalAPBPerCategories[$totalCJM + 1] += $dataAPB_array[$totalCJM];
      }

      $getValueTahunKeEnam = $viewAsetTetap->select("SUM(capex_value) as Total")
      ->where("status", "1")
      ->where("capex_name", "Investasi " . $value)
      ->whereIn("capex_group", $this->mydata['group_type'])
      ->where("capex_value is NOT NULL", NULL, FALSE)
      ->where("capex_value <>", 0)
      ->where("capex_period", $listTahun[0] . "-12")
      ->first();
      if (empty($getValueTahunKeEnam["Total"])) {
        $valueKe6 = 0;
      } else {
        $valueKe6 = $getValueTahunKeEnam["Total"];
      }
      $totalPerCategories[0] += (int)$valueKe6 / 1000000000;

      $getAPBTahunKeEnam = $Budget->select("SUM(budget_value) as Total")
      ->where("budget_type", $budget_type)
        ->whereIn("budget_group", ["1", "2"])
        ->where("budget_period", $listTahun[0] . "-12")
        ->first();
      if (empty($getAPBTahunKeEnam["Total"])) {
        $APBKe6 = 0;
      } else {
        $APBKe6 = $getAPBTahunKeEnam["Total"];
      }
      $totalAPBPerCategories[0] += (int)$APBKe6 / 1000000000;

      $i++;
    }
    // END MAIN FUNCTION

    $LastFiveYears = [];
    for ($i = 1; $i <= 5; $i++) {
      $LastFiveYears[] = "$listTahun[$i]";
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartAsetTetapKonsolidasi"]  = json_encode($chartAsetTetapKonsolidasi);
    $data["chartCategories"]            = json_encode($LastFiveYears);
    $data["tableAsetTetap"]             = $chartAsetTetapKonsolidasi;
    $data["tableCategories"]            = $LastFiveYears;
    $data["tableTotalPerCategories"]    = $totalPerCategories;
    $data["tableTotalAPBPerCategories"] = $totalAPBPerCategories;

    if ($chartType == "bar") {
      return $this->blade->render("yayasan/pages/laporan_keuangan/aset_tetap_konsolidasi_chart", $data);
    } else if ($chartType == "pie") {
      return redirect()->to('/');
    }
  }
  // 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211

}
