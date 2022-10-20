<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;
use App\Models\Budget;
use App\Models\KeuNeraca;
use App\Models\KeuLabaRugi;
use App\Models\KeuCashflow;
use App\Models\KeuCapex;
use App\Models\ViewProdi;
use App\models\Yayasan\LaporanKeuangan\ViewAsetTetap;
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKas;
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKasQuarter;

class LaporanKeuanganController extends BaseController
{

  public $mydata;
  private $db;

  public function __construct() {
    $this->mydata['id_menu']    = '4';
    
    $this->mydata['color'] = [
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

    $this->mydata["month"] = [
      0 => "JAN",
      1 => "FEB",
      2 => "MAR",
      3 => "APR",
      4 => "MAY",
      5 => "JUN",
      6 => "JUL",
      7 => "AUG",
      8 => "SEP",
      9 => "OCT",
      10 => "NOV",
    ];

    $this->mydata['group_type'] = '2'; // 1 = Yayasan,  2 = Unika
    $this->db = db_connect();
  }

  public function Index(){
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

        return $this->blade->render("pages/laporan_keuangan/index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function FilterKonsolidasiPBYAJ(){
        $filter_type    = $this->request->getPost('Filter');
        $tahunTerakhir  = @$this->request->getPost('Year');
        $quarter_get    = @$this->request->getPost('Quarter');
        $fromMonth      = @$this->request->getPost('filter_date1_chartRealisasi_Kenaikan_PB');
        $endMonth       = @$this->request->getPost('filter_date2_chartRealisasi_Kenaikan_PB');

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
          $chartPBYAJ = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartPBYAJ[0]["name"] = "Pendapatan";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                array_push($data_array, round($getValue["total"]/1000000000));
              }
            }
            $chartPBYAJ[0]["data"] = $data_array;

            //Beban
            $chartPBYAJ[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                array_push($data_array, round($getValue["total"]/1000000000));
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
          return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_tahun", $data);
          
      }
      else if($filter_type=="quarter"){
          // VARIABEL UNTUK CHART
          $chartPBYAJ = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartPBYAJ[0]["name"] = "Pendapatan";
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();


              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]*-1);
              }
            }
            $chartPBYAJ[0]["data"] = $data_array;

            //Beban
            $chartPBYAJ[1]["name"] = "Beban";
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                              ->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartPBYAJ[1]["data"] = $data_array;
          // END MAIN FUNCTION

          $ListQuarter = ['Q1 '.$tahunTerakhir,'Q2 '.$tahunTerakhir,'Q3 '.$tahunTerakhir,'Q4 '.$tahunTerakhir];

          // DATA YANG AKAN DIKIRIM KE CHART
          $data["chartPBYAJ"]              = json_encode($chartPBYAJ);
          $data["ListQuarter"]             = json_encode($ListQuarter);
          return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_quarter", $data);
          
      }
      else if($filter_type=="quater_komparasi"){
           // VARIABEL UNTUK CHART
          $chartPBYAJ = array();

          // START MAIN FUNCTION
            //Pendapatan
            $chartPBYAJ[0]["name"] = "Pendapatan";
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();

              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]*-1);
              }
            }
            $chartPBYAJ[0]["data"] = $data_array;

            //Beban
            $chartPBYAJ[1]["name"] = "Beban";
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                              ->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array,$getValue["total"]);
              }
            }
            $chartPBYAJ[1]["data"] = $data_array;
          // END MAIN FUNCTION

           $LastFiveYears = [];
            for ($i = 0; $i <= 4; $i++) {
              $LastFiveYears[] = [
                $quarter_get." $listTahun[$i]"
              ];
            } 

          // DATA YANG AKAN DIKIRIM KE CHART
          $data["chartPBYAJ"]            = json_encode($chartPBYAJ);
          $data["LastFiveYears"]         = json_encode($LastFiveYears);
          return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_quarter_komparasi", $data);
          
      }
      else if($filter_type=="tahun_bulan"){
          $date1  = $this->request->getPost('From');
          $date2  = $this->request->getPost('To');

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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
            $chartPBYAJ[0]["data"] = $data_array;


            //Beban
            $chartPBYAJ[1]["name"] = "Beban";
            $data_array  = array();
            for ($perthn = 0; $perthn < $countMonth; $perthn++) {
              $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                              ->where("status","1")
                              ->where("laba_rugi_name", "Beban")
                              ->where("group_type","2")
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
          return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_tahun", $data);
      }
  }

  public function FilterKenaikanPB(){
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $year_before."-12")
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
        return $this->blade->render("pages/laporan_keuangan/kenaikan_pb_chart_tahun", $data);
          
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
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
                  $dt1 = ($tahunTerakhir-1)."-".$getMonthQuarter[0];
                  $dt2 = ($tahunTerakhir-1)."-".$getMonthQuarter[1];
                  $dt3 = ($tahunTerakhir-1)."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Pendapatan")
                                ->where("group_type","1")
                                ->where("laba_rugi_group",$this->mydata['group_type'])
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
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
                  $dt1 = ($tahunTerakhir-1)."-".$getMonthQuarter[0];
                  $dt2 = ($tahunTerakhir-1)."-".$getMonthQuarter[1];
                  $dt3 = ($tahunTerakhir-1)."-".$getMonthQuarter[2];
                  $getQuarterBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->where("laba_rugi_group",$this->mydata['group_type'])
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
        return $this->blade->render("pages/laporan_keuangan/kenaikan_pb_chart_quarter", $data);
          
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
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
                $kalkulasi   = round((($total-$totalBefore)/$totalBefore)*100);
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
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
                $kalkulasi   = round((($total-$totalBefore)/$totalBefore)*100);
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
        return $this->blade->render("pages/laporan_keuangan/kenaikan_pb_chart_quarter_komparasi", $data);
          
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
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
                              ->where("laba_rugi_group",$this->mydata['group_type'])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->where("laba_rugi_group", $this->mydata['group_type'])
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
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
        return $this->blade->render("pages/laporan_keuangan/kenaikan_pb_chart_tahun", $data);
          
      }
  }

  public function FilterChartRealisasiAPB(){
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
      ->where("laba_rugi_group", $this->mydata['group_type'])
      ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
      ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Pendapatan")
        ->where("group_type", "1")
        ->where("laba_rugi_group", $this->mydata['group_type'])
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
      ->where("laba_rugi_group", $this->mydata['group_type'])
      ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
      ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->where("laba_rugi_group", $this->mydata['group_type'])
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
      ->where("budget_group", "1") // 1 UNIKA 2 YAYASAN
      ->where("budget_value is NOT NULL", NULL, FALSE)
      ->where("budget_period", $listTahun[$perthn] . "-12")
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
      ->where("budget_group", "1") // 1 UNIKA 2 YAYASAN
      ->where("budget_value is NOT NULL", NULL, FALSE)
      ->where("budget_period", $listTahun[$perthn] . "-12")
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
          return $this->blade->render("pages/laporan_keuangan/realisasi_chart", $data);
  }

  public function FilterChartPosisiKeuangan(){
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
    $back_year = $tahunTerakhir - 4;
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
        ->where("neraca_group", $this->mydata['group_type'])
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_period", $listTahun[$perthn] . "-12")
        ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
          ->where("neraca_name", "Aset Lancar")
          ->where("neraca_group", $this->mydata['group_type'])
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
        ->where("neraca_group", $this->mydata['group_type'])
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_period", $listTahun[$perthn] . "-12")
        ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
          ->whereIn("neraca_name", ["Aset Tidak Lancar", "Aset Investasi", "Aset Lain - Lain"])
          ->where("neraca_group", $this->mydata['group_type'])
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
    $chartPosisiKeuangan[1]["data"] = $data_array;
    $tablePosisiKeuangan[1]["data"] = $datatable_array;


    //Liabilitas
    $chartPosisiKeuangan[2]["name"] = "Liabilitas";
    $data_array  = array();
    for ($perthn = 0; $perthn <= 4; $perthn++) {
      $getValue  =  $Neraca->select('sum(neraca_value) as total')
      ->where("status", "1")->where("group_type", "2")
      ->whereIn("neraca_name", ["Liabilitas Jangka Pendek", "Liabilitas Jangka Panjang"])
      ->where("neraca_group", $this->mydata['group_type'])
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn] . "-12")
      ->first();

      if (empty($getValue["total"])) {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "2")
        ->whereIn("neraca_name", ["Liabilitas Jangka Pendek", "Liabilitas Jangka Panjang"])
        ->where("neraca_group", $this->mydata['group_type'])
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
      ->where("neraca_group", $this->mydata['group_type'])
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn] . "-12")
      ->first();

      if (!empty($getValue["total"])) {
        $sum += $getValue["total"];
      } else {
        $getValue  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "2")
        ->whereIn("neraca_name", [$sum_col1, $sum_col2])
        ->where("neraca_group", $this->mydata['group_type'])
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
      ->where("neraca_group", $this->mydata['group_type'])
      ->where("neraca_value is NOT NULL", NULL, FALSE)
      ->where("neraca_period", $listTahun[$perthn] . "-12")->first();

      if ($listTahun[$perthn] >= 2021) {
        $getIntercompany  =  $Neraca->select('sum(neraca_value) as total')
        ->where("status", "1")->where("group_type", "1")
        ->where("neraca_name", "Intercompany")
        ->where("neraca_group", $this->mydata['group_type'])
        ->where("neraca_quarter_value is NULL", NULL, FALSE)
        ->where("neraca_value is NOT NULL", NULL, FALSE)
        ->where("neraca_period", $listTahun[$perthn] . "-12")
        ->first();

        if (empty($getIntercompany["total"])) {
          $getIntercompany  =  $Neraca->select('sum(neraca_value) as total')
          ->where("status", "1")->where("group_type", "1")
          ->where("neraca_name", "Intercompany")
          ->where("neraca_group", $this->mydata['group_type'])
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
        ->where("neraca_group", $this->mydata['group_type'])
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
    return $this->blade->render("pages/laporan_keuangan/posisi_keuangan_chart", $data);
  }

  public function FilterChartKasSetaraKas(){
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

    // GET KAS NAME
    $KasSetaraKas = [
      0 => "Bank",
      1 => "Deposito Berjangka",
      2 => "Kas"
    ];
    // END KAS NAME

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
    for ($thn = $tahunTerakhir - 5; $thn <= $tahunTerakhir; $thn++) {
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
          ->where("neraca_group", $this->mydata['group_type'])
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
            ->where("neraca_group", $this->mydata['group_type'])
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
          $totalPerCategories[$totalCJM + 1] += $data_array[$totalCJM];
        }

        $getValueTahunKeEnam = $viewKasSetaraKas->select("SUM(neraca_value) as Total")
        ->where("status", "1")
        ->where("group_type", "1")
        ->where("neraca_name", $value)
        ->where("neraca_group", $this->mydata['group_type'])
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
        return $this->blade->render("pages/laporan_keuangan/kassetarakas_chart", $data);
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
          ->where("neraca_group", $this->mydata['group_type'])
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

        $chartKasSetaraKas[$i]["data"] = $data_array;

        // INI UNTUK MENGHITUNG TOTAL PER CATEGORIES
        for ($totalCJM = 0; $totalCJM <= 3; $totalCJM++) {
          $totalQPerCategories[$totalCJM + 1] += $data_array[$totalCJM];
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
        ->where("neraca_group", $this->mydata['group_type'])
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
        return $this->blade->render("pages/laporan_keuangan/kassetarakas_chart", $data);
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
          ->where("neraca_group", $this->mydata['group_type'])
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
          $totalPerCategories[$totalCJM + 1] += $data_array[$totalCJM];
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
        ->where("neraca_group", $this->mydata['group_type'])
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
        return $this->blade->render("pages/laporan_keuangan/kassetarakas_chart", $data);
      } else if ($chartType == "pie") {
        return redirect()->to('/');
      }
    }
  }

  public function FilterChartCashflow(){
        $db      = $this->db;
        $getYear = @$this->request->getPost("Year");

        if(!empty($getYear)) {
          $tahunTerakhir = $getYear;
        } else {
          $tahunTerakhir = date('Y');
        }

        // PANGGIL MODEL
        $cashflow  = new KeuCashflow();

        $data_id         = array();
        $data_net_profit = array();
        $data_net_loss   = array();
        $categories      = array();

        $period_param   = $tahunTerakhir."-13";

        if ($tahunTerakhir == date('Y')) {
            if (date("m") == "01") {
              $period_param = date("Y", strtotime("-1 Year")) . "-13";
            } else {
              $period_param = date("Y") . "-" . date("m", strtotime("-1 Month")); 
            }
        }
        
        $qry = "select sum(cashflow_value) as total FROM tr_keu_cashflow WHERE status='1' and cashflow_group='".$this->mydata['group_type']."' and cashflow_period ='".$period_param."'";

        //Start Sum All Pendapatan Fakultas
          $AllPendapatanFakultas = 0;
          $qryPendapatanFakultas = $qry." and fakultas is not null and cashflow_name like 'Pendapatan%'";
          $resPendapatanFakultas = $db->query($qryPendapatanFakultas);
          $getPendapatanFakultas = $resPendapatanFakultas->getRow();
          $AllPendapatanFakultas = $getPendapatanFakultas->total;

          if ($AllPendapatanFakultas < 0) {
            $AllPendapatanFakultas = $AllPendapatanFakultas*-1;
          }
        //End Sum All Pendapatan Fakultas

        //Start Surplus Non Fakultas
          $getSurplusNonFakultas    = 0;
          $qryNonFakultas           = $qry." and fakultas is null and cashflow_name like 'Pendapatan%'";
          $resPendapatanNonFakultas = $db->query($qryNonFakultas);
          $getPendapatanNonFakultas = $resPendapatanNonFakultas->getRow();

          if($getPendapatanNonFakultas->total < 0) {
            $PendapatanNonFakultas = $getPendapatanNonFakultas->total * -1;
          } else {
            $PendapatanNonFakultas = $getPendapatanNonFakultas->total;
          }

          $qry_bebanNonFakultas = $qry." and fakultas is null and  cashflow_name like 'Beban%'";
          $resBebanNonFakultas  = $db->query($qry_bebanNonFakultas);
          $getBebanNonFakultas  = $resBebanNonFakultas->getRow();

          $getSurplusNonFakultas = $PendapatanNonFakultas - $getBebanNonFakultas->total;
        //End Surplus Non Fakultas


        $data_cashflow_categories = $cashflow->select('distinct(fakultas)')
                                    ->where("status","1")
                                    ->where("cashflow_group",$this->mydata['group_type'])
                                    ->where("fakultas is NOT NULL", NULL, FALSE)
                                    ->findAll();
        foreach($data_cashflow_categories as $cg) {
          array_push($data_id,$cg['fakultas']);
          array_push($categories,fakultas($cg['fakultas']));

            //Pendapatan
            $universitas        = 0;
            $query_pendapatan   = $qry." and fakultas='".$cg['fakultas']."' and cashflow_name like 'Pendapatan%'";
            $resValuePendapatan = $db->query($query_pendapatan);
            $getValuePendapatan = $resValuePendapatan->getRow();
            $Pendapatan = 0;
            
            if (!empty($getValuePendapatan)) {

              if($getValuePendapatan->total < 0){
                $Pendapatan = $getValuePendapatan->total * -1;
              } else {
                $Pendapatan = $getValuePendapatan->total;
              }

              //Nilai Universitas
              if (empty($AllPendapatanFakultas)) {
                $universitas = 0;
              } else {
                $universitas = $Pendapatan/$AllPendapatanFakultas*$getSurplusNonFakultas;
              }
            }

            //Beban
            $query_beban   = $qry." and fakultas='".$cg['fakultas']."' and cashflow_name like 'Beban%'";
            $resValueBeban = $db->query($query_beban);
            $getValueBeban = $resValueBeban->getRow();

            //Start Surplus  Fakultas
            $getSurplusFakultas = 0;
            $getSurplusFakultas = $Pendapatan - $getValueBeban->total;
            //End Surplus  Fakultas

            //Nilai Profit or Lost
            $profitLoss = 0;
            $profitLoss = $getSurplusFakultas+$universitas;

            //Profit
            if($profitLoss>0){
              array_push($data_net_profit,$profitLoss);
            }
            else {
              array_push($data_net_profit,null); 
            } 

            //Loss
            if($profitLoss<0){
              array_push($data_net_loss,-abs($profitLoss));
            }
            else {
              array_push($data_net_loss,null); 
            }
            
        }

        //$data['data_pendapatan']  = json_encode($data_pendapatan);
        //$data['data_beban']       = json_encode($data_beban);
        //$data['data_pecahan']     = json_encode($data_pecahan);
        $data['data_id']          = json_encode($data_id);
        $data['data_net_loss']    = json_encode($data_net_loss);
        $data['data_net_profit']  = json_encode($data_net_profit);
        $data['categories']       = json_encode($categories);
        $data['year']             = $tahunTerakhir;
      

        return $this->blade->render("pages/laporan_keuangan/cashflow_chart", $data);
  } 

  public function FilterChartCashflowDetail(){
    $db            = $this->db;
    $parameter     = $this->request->getPost("parameter");
    $tahunTerakhir = $this->request->getPost("year");

        // PANGGIL MODEL
        $cashflow  = new KeuCashflow();

        $data_net_profit = array();
        $data_net_loss   = array();
        $categories      = array();

        $period_param   = $tahunTerakhir."-13";

        if ($tahunTerakhir == date('Y')) {
          if (date("m") == "01") {
            $period_param = date("Y", strtotime("-1 Year")) . "-13";
          } else {
            $period_param = date("Y") . "-" . date("m", strtotime("-1 Month")); 
          }
        }

        $qry = "select sum(cashflow_value) as total FROM tr_keu_cashflow WHERE status='1' and cashflow_group='".$this->mydata['group_type']."' and cashflow_period ='".$period_param."' 
          and fakultas='".$parameter."' ";

        //Start Sum All Pendapatan Fakultas
          $AllPendapatanFakultas       = 0;
          $qryPendapatanFakultas       =  "select sum(cashflow_value) as total FROM tr_keu_cashflow WHERE status='1' and cashflow_group='".$this->mydata['group_type']."' 
             and cashflow_period ='".$period_param."' and fakultas is not null 
             and cashflow_name like 'Pendapatan%'";
          $resPendapatanFakultas       = $db->query($qryPendapatanFakultas);
          $getPendapatanFakultas       = $resPendapatanFakultas->getRow();     
          $AllPendapatanFakultas       = $getPendapatanFakultas->total;

          if($AllPendapatanFakultas < 0){
            $AllPendapatanFakultas = $AllPendapatanFakultas * -1;
          } else {
            $AllPendapatanFakultas = $AllPendapatanFakultas;
          }
        //End Sum All Pendapatan Fakultas

        //Start Surplus Non Fakultas
          $qryNonFakultas = "select sum(cashflow_value) as total FROM tr_keu_cashflow WHERE status='1' and cashflow_group='".$this->mydata['group_type']."' and cashflow_period='".$period_param."' and fakultas is null";
          $getSurplusNonFakultas     = 0;
          $qryPendapatanFakultas     = $qryNonFakultas." and cashflow_name like 'Pendapatan%'";
          $resPendapatanNonFakultas  = $db->query($qryPendapatanFakultas);
          $getPendapatanNonFakultas  = $resPendapatanNonFakultas->getRow(); 

          if($getPendapatanNonFakultas->total < 0){
            $PendapatanNonFakultas = $getPendapatanNonFakultas->total * -1;
          } else {
            $PendapatanNonFakultas = $getPendapatanNonFakultas->total;
          }

          $qry_bebanNonFakultas = $qryNonFakultas." and cashflow_name like 'Beban%'";
          $resBebanNonFakultas  = $db->query($qry_bebanNonFakultas);
          $getBebanNonFakultas  = $resBebanNonFakultas->getRow();

          $getSurplusNonFakultas     = $PendapatanNonFakultas - $getBebanNonFakultas->total;
        //End Surplus Non Fakultas

        $data_cashflow_categories = $cashflow->select('distinct(remark)')
                                    ->where("status","1")
                                    ->where("cashflow_group",$this->mydata['group_type'])
                                    ->where("fakultas",$parameter)
                                    ->orderBy('remark','asc')
                                    ->findAll();
        foreach($data_cashflow_categories as $cg){
           array_push($categories,$cg['remark']);

           //Pendapatan
           $universitas    = 0;
           $qry_pendapatan = $qry." and remark='".$cg['remark']."' and cashflow_name like 'Pendapatan%'";
           $resPendapatan       = $db->query($qry_pendapatan);
           $getValuePendapatan  = $resPendapatan->getRow();
           $Pendapatan = 0;

           if (!empty($getValuePendapatan)) {
            
              if($getValuePendapatan->total < 0){
                $Pendapatan = $getValuePendapatan->total * -1;
              } else {
                $Pendapatan = $getValuePendapatan->total;
              }

              //Nilai Universitas
              if (empty($AllPendapatanFakultas)) {
                $universitas = 0;
              } else {
                $universitas = $Pendapatan/$AllPendapatanFakultas*$getSurplusNonFakultas;
              }
           }

           //Beban
           $qry_beban     = $qry." and remark='".$cg['remark']."' and cashflow_name like 'Beban%'";
           $resBeban      = $db->query($qry_beban);
           $getValueBeban = $resBeban->getRow();

           //Start Surplus Prodi
            $getSurplusProdi = 0;
            $getSurplusProdi = $Pendapatan - $getValueBeban->total;
            //End Surplus  Prodi

            //Nilai Profit or Lost
            $profitLoss = 0;
            $profitLoss = $getSurplusProdi+$universitas;

            //Profit
            if ($profitLoss>0) {
              array_push($data_net_profit,$profitLoss);
            } else {
              array_push($data_net_profit,null); 
            } 

            //Loss
            if ($profitLoss<0) {
              array_push($data_net_loss,-abs($profitLoss));
            } else {
              array_push($data_net_loss,null); 
            }
        }

      //Start Table Cashflow Detail 
        $data_cashflow_name = $cashflow->select('cashflow_name, cashflow_number')
                               ->where("status","1")->where("cashflow_group",$this->mydata['group_type'])
                               ->where("fakultas",$parameter)->where("cashflow_period",$period_param)
                               ->groupBy("cashflow_name, cashflow_number")
                               ->orderBy("cashflow_number","asc")
                               ->findAll();

        //For Table   
        foreach ($data_cashflow_name as $cg) {
           $data['TableCashflowValue'][$cg['cashflow_name']] = $cashflow->select('cashflow_value,remark')
                             ->where("status","1")->where("cashflow_group",$this->mydata['group_type'])
                             ->where("fakultas",$parameter)->where("cashflow_name",$cg['cashflow_name'])
                             ->where("cashflow_period",$period_param)->whereIn('remark',$categories)
                             ->orderBy('remark','asc')
                             ->findAll(); 
        }
      //End Table Cashflow Detail

      //$data['data_pendapatan']       = json_encode($data_pendapatan);
      //$data['data_beban']            = json_encode($data_beban);
      //$data['data_pecahan']     = json_encode($data_pecahan);
      $data['data_net_loss']         = json_encode($data_net_loss);
      $data['data_net_profit']       = json_encode($data_net_profit);
      $data['categories']            = json_encode($categories);
      $data['tableProdi']            = $categories;
      $data['TableCashflow']         = $data_cashflow_name;
      $data['year']                  = $tahunTerakhir;

    return $this->blade->render("pages/laporan_keuangan/cashflow_chart_detail", $data);
  }

  public function FilterChartAsetTetap(){
    $tahunTerakhir  = $this->request->getPost('Year');

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
    for ($thn = $tahunTerakhir-4; $thn <= $tahunTerakhir; $thn++) {
      $listTahun[] = $thn;
    }

    // VARIABEL UNTUK CHART
    $chartAsetTetap = array();

    // START MAIN FUNCTION
    foreach ($AsetTetap as $value) {
      $chartAsetTetap[$i]["name"] = $value;
      $chartColor[] = $this->mydata['color'][$i];

      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  =  $viewAsetTetap->select("SUM(Total) as Total")
        ->where("AsetTetap", $value)
        ->where("GroupData", "02")
        ->where("Period", "ADJ-" . substr($listTahun[$perthn], -2))
        ->first();

        array_push($data_array, $getValue["Total"] / 1000000000);
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
    for ($i = 0; $i <= 4; $i++) {
      $LastFiveYears[] = "$listTahun[$i]";
    }

    // DATA YANG AKAN DIKIRIM KE CHART
    $data["chartAsetTetap"]          = json_encode($chartAsetTetap);
    $data["chartColor"]              = json_encode($chartColor);
    $data["chartCategories"]         = json_encode($LastFiveYears);
    $data["tableAsetTetap"]          = $chartAsetTetap;
    $data["tableCategories"]         = $LastFiveYears;
    $data["tableTotalPerCategories"] = $totalPerCategories;

    return $this->blade->render("pages/laporan_keuangan/aset_tetap_chart", $data);
  }

  public function FilterChartCapex(){
        $tahunTerakhir  = $this->request->getPost('Year');

        // PANGGIL MODEL
        $capex  = new KeuCapex();

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
        if ($tahunTerakhir == "") {
          $tahunTerakhir = date("Y");
        }
        for ($thn = $tahunTerakhir-4; $thn <= $tahunTerakhir; $thn++) {
          $listTahun[] = $thn;
        }
          
          $chartCapexData = array();
          $tableCapexData = array();
          $data_capex_categories = $capex
          ->select('capex_name,capex_number')
          ->where("status","1")
          ->where("capex_group",$this->mydata['group_type'])
          ->groupBy("capex_name,capex_number")
          ->whereNotIn("capex_name", ["Tanah", "Gedung & CIP", "Investasi Gedung & CIP", "Investasi Tanah", "Investasi Peralatan dan Prasarana"])
          ->findAll();
          $i=0;                           
          foreach($data_capex_categories as $cg){
              $data_array       = array();
              $datatable_array  = array();
              $chartCapexData[$i]['name'] = $cg['capex_name'];
              $tableCapexData[$i]['name'] = $cg['capex_name'];
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                  $getValue = $capex->select('sum(capex_value) as total')
                                    ->where("status","1") 
                                    ->where("capex_group",$this->mydata['group_type'])
                                    ->where("capex_name",$cg['capex_name'])
                                    ->where("capex_number",$cg['capex_number'])
                                    ->where("capex_value is NOT NULL", NULL, FALSE)
                                    ->where("capex_period", $listTahun[$perthn]."-12")
                                    ->first();
                  if (empty($getValue["total"])) {
                    $getValue  =  $capex->select('sum(capex_value) as total')
                    ->where("status", "1")
                    ->where("capex_group", $this->mydata['group_type'])
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
                    array_push($data_array,$getValue["total"]/1000000000);
                    array_push($datatable_array, $getValue["total"]/1000000000);
                  }
              }
              $chartCapexData[$i]['data'] = $data_array;
              $tableCapexData[$i]['data'] = $datatable_array;                         
              $dataColor[]                = $this->mydata['color'][$i];
              for ($totalPerIndex = 0; $totalPerIndex <= 4; $totalPerIndex++) {
                $totalPerCategories[$totalPerIndex+1] += $data_array[$totalPerIndex];
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
        
        return $this->blade->render("pages/laporan_keuangan/capex_chart", $data);
  }

  public function PendapatanBebanTable(){
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

          // VARIABEL UNTUK CHART
          $TablePendapatanvsBeban = array();
          $TablePendapatanvsBebanPersen = array();

          if($filter_type=="tahun"){
            // START MAIN FUNCTION
              //Pendapatan
              $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
              $data_array         = array();
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Pendapatan")
                                  ->where("group_type","1")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                  ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Pendapatan")
                  ->where("group_type", "1")
                  ->where("laba_rugi_group", $this->mydata['group_type'])
                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                  ->where("laba_rugi_value <>", 0)
                  ->where("LEFT(laba_rugi_period, 4)", $listTahun[$perthn])
                  ->groupBy("laba_rugi_period")
                  ->orderBy("laba_rugi_period", "DESC")
                  ->first();

                  if (empty($getValue)) {
                    array_push($data_array, 0);
                  } else {
                    array_push($data_array,$getValue["total"]);
                  }
                } else {
                  array_push($data_array,$getValue["total"]);
                }
              }
              $TablePendapatanvsBeban[0]["real"]  = $data_array;

              //Persen
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
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"];
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
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Beban")
                  ->where("group_type", "2")
                  ->where("laba_rugi_group", $this->mydata['group_type'])
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
                  array_push($data_array, $getValue["total"]);
                }
              }
              $TablePendapatanvsBeban[1]["real"] = $data_array;

              //Persen
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $TablePendapatanvsBeban[1]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[1]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[1]["real"][$i];
                }

                if(!empty($total) && $totalBefore > 0){
                  $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
            // END MAIN FUNCTION

            // DATA YANG AKAN DIKIRIM KE CHART
            $data["tableListHeader"] = $listTahun;
          }

          else if($filter_type=="quarter"){
            // START MAIN FUNCTION
              //Pendapatan
              $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
              $data_array         = array();
              for ($quarter = 1; $quarter <= 4; $quarter++) {
                $getMonthQuarter = month_quarter($quarter);
                $dt1 = $tahunTerakhir."-".$getMonthQuarter[0];
                $dt2 = $tahunTerakhir."-".$getMonthQuarter[1];
                $dt3 = $tahunTerakhir."-".$getMonthQuarter[2];
                $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Pendapatan")
                                  ->where("group_type","1")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array,($getValue["total"]*-1));
                }
              }
              $TablePendapatanvsBeban[0]["real"]  = $data_array;

              //Persen Pendapatan
              $i=0;
              $data_array_persen  = array();
              for ($quarter = 1; $quarter <= 4; $quarter++) {
                $totalBefore  = 0;
                $total        = 0;
                $kalkulasi    = 0;
                if($i==0){
                    $getMonthQuarter = month_quarter("Q4");
                    $dt1 = ($tahunTerakhir-1)."-".$getMonthQuarter[0];
                    $dt2 = ($tahunTerakhir-1)."-".$getMonthQuarter[1];
                    $dt3 = ($tahunTerakhir-1)."-".$getMonthQuarter[2];
                    $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Pendapatan")
                                  ->where("group_type","1")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"]*-1;
                    }
                    $total = $TablePendapatanvsBeban[0]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[0]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[0]["real"][$i];
                }


                if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

              //Beban
              $TablePendapatanvsBeban[1]["name"] = "Beban";
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $getValue["total"]);
                }
              }
              $TablePendapatanvsBeban[1]["real"] = $data_array;

              //Persen Beban
              $i=0;
              $data_array_persen  = array();
              for ($quarter = 1; $quarter <= 4; $quarter++) {
                $totalBefore  = 0;
                $total        = 0;
                $kalkulasi    = 0;
                if($i==0){
                    $getMonthQuarter = month_quarter("Q4");
                    $dt1 = ($tahunTerakhir-1)."-".$getMonthQuarter[0];
                    $dt2 = ($tahunTerakhir-1)."-".$getMonthQuarter[1];
                    $dt3 = ($tahunTerakhir-1)."-".$getMonthQuarter[2];
                    $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Beban")
                                  ->where("group_type","2")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"];
                    }
                    $total = $TablePendapatanvsBeban[1]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[1]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[1]["real"][$i];
                }

                if(!empty($total) && !empty($totalBefore)){
                 $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
            // END MAIN FUNCTION

             $ListQuarter = ['Q1 '.$tahunTerakhir,'Q2 '.$tahunTerakhir,'Q3 '.$tahunTerakhir,'Q4 '.$tahunTerakhir];

            // DATA YANG AKAN DIKIRIM KE CHART
            $data["tableListHeader"] = $ListQuarter;
          }

          else if($filter_type=="quater_komparasi"){
            // START MAIN FUNCTION
              //Pendapatan
              $TablePendapatanvsBeban[0]["name"] = "Pendapatan";
              $data_array         = array();
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                $getMonthQuarter = month_quarter($quarter_get);
                $dt1 = $listTahun[$perthn]."-".$getMonthQuarter[0];
                $dt2 = $listTahun[$perthn]."-".$getMonthQuarter[1];
                $dt3 = $listTahun[$perthn]."-".$getMonthQuarter[2];
                $getValue  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Pendapatan")
                                  ->where("group_type","1")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {                                                                  
                  array_push($data_array,$getValue["total"]*-1);
                }
              }
              $TablePendapatanvsBeban[0]["real"]  = $data_array;



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
                    $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Pendapatan")
                                  ->where("group_type","1")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"]*-1;
                    }
                    $total = $TablePendapatanvsBeban[0]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[0]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[0]["real"][$i];
                }

                if(!empty($total) && $totalBefore > 0){
                  $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[0]["persen"] = $data_array_persen;

              //Beban
              $TablePendapatanvsBeban[1]["name"] = "Beban";
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                ->first();
                if (empty($getValue)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array,$getValue["total"]);
                }
              }
              $TablePendapatanvsBeban[1]["real"] = $data_array;

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
                    $getValueYearBefore  =  $LabaRugi->select('sum(laba_rugi_quarter_value) as total')
                                  ->where("status","1")
                                  ->where("laba_rugi_name", "Beban")
                                  ->where("group_type","2")
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->whereIn('laba_rugi_period',[$dt1, $dt2, $dt3])
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"];
                    }
                    $total = $TablePendapatanvsBeban[1]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[1]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[1]["real"][$i];
                }

                if(!empty($total) && $totalBefore > 0){
                  $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
            // END MAIN FUNCTION

            $ListYearQuarter = [
                                $quarter_get." ".$listTahun[0],$quarter_get." ".$listTahun[1],
                                $quarter_get." ".$listTahun[2],$quarter_get." ".$listTahun[3],$quarter_get." ".$listTahun[4]
                               ];

            // DATA YANG AKAN DIKIRIM KE CHART
            $data["tableListHeader"] = $ListYearQuarter;

          }

          else if($filter_type=="tahun_bulan"){
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
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                  ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Pendapatan")
                  ->where("group_type", "1")
                  ->where("laba_rugi_group", $this->mydata['group_type'])
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
                                  ->where("laba_rugi_group",$this->mydata['group_type'])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period",$yearMonth_before)
                                  ->first();
                    if (!empty($getValueYearBefore)) {
                      $totalBefore = $getValueYearBefore["total"];
                    }
                    $total = $TablePendapatanvsBeban[0]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[0]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[0]["real"][$i];
                }

                if(!empty($total) && $totalBefore > 0){
                  $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Beban")
                  ->where("group_type", "2")
                  ->where("laba_rugi_group", $this->mydata['group_type'])
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
                  array_push($data_array, $getValue["total"]);
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
                                ->where("laba_rugi_group",$this->mydata['group_type'])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $yearMonth_before)
                                ->first();
                  if (!empty($getValueYearBefore)) {
                    $totalBefore = $getValueYearBefore["total"];
                  }
                  $total = $TablePendapatanvsBeban[1]["real"][$i];
                }
                else {
                   $totalBefore = $TablePendapatanvsBeban[1]["real"][$i-1];
                   $total       = $TablePendapatanvsBeban[1]["real"][$i];
                }

                if(!empty($total) && $totalBefore > 0){
                  $kalkulasi = round((($total-$totalBefore)/$totalBefore)*100);
                }
                array_push($data_array_persen,$kalkulasi);
                $i++;
              }
              $TablePendapatanvsBeban[1]["persen"] = $data_array_persen;
            // END MAIN FUNCTION

            // DATA YANG AKAN DIKIRIM KE CHART
  
            $data["tableListHeader"] = $ListYearMonth;
          }



        // DATA YANG AKAN DIKIRIM KE CHART
        $data["countMonth"]                 = @$countMonth;
        $data["filter_type"]                = $filter_type;
        $data["tablePendapatanBeban"]       = $TablePendapatanvsBeban;
        return $this->blade->render("pages/laporan_keuangan/pendapatan_beban_table", $data);
  }

  public function piutangAging(){
      $db           = $this->db;
      $jenjang      = $this->request->getPost('jenjang');
      $prodi        = $this->request->getPost('prodi');
      $tahun        = $this->request->getPost('year');

      if ($tahun == "") {
        $tahun = date('Y');
      }

      $firstWhere = "WHERE LEFT(PERIODE,4)='" . $tahun . "'";

      $checkLastMonth = $db->query("SELECT TOP 1 PERIODE FROM [dashboard_atmajaya].[dbo].[tr_piutang_aging] ".$firstWhere." ORDER BY PERIODE DESC")->getRow();

      $getMonth = explode("-", $checkLastMonth->PERIODE);
      $getMonth = bulan_indo($getMonth[1]);
      $data["latestMonth"] = "Data Bulan " . $getMonth . " Tahun " . $tahun;
      $data['table'] = "";

      if($checkLastMonth) {
        $where = "WHERE LEFT(PERIODE, 7)='" . substr($checkLastMonth->PERIODE, 0, 7) . "'";

        if($prodi != "") {
          $where .= " AND NAMA_PRODI='" . $prodi . "'";
        }
        if($jenjang != "") {
          $where .= " AND ACAD_CAREER='" . $jenjang . "'";
        }
 
        $query = $db->query("SELECT SUM ( Hutang ) AS Total, Aging 
                            FROM [dashboard_atmajaya].[dbo].[tr_piutang_aging] ".$where."
                            GROUP BY  Aging
                            ORDER BY case when Aging = '0 / BELUM JATUH TEMPO' then 1
                              when Aging = '1-90' then 2
                              when Aging = '91-180' then 3
                              when Aging = '181-360' then 4
                              else 5
                            end ASC");
  
        foreach ($query->getResult() as $item) {
          $data["table"] .= "<tr>
                              <td>".(($item->Aging == '0 / BELUM JATUH TEMPO') ? '0' : $item->Aging)."</td>
                              <td>".number_format($item->Total/1000000000)."</td>
                            </tr>";
        }
      } else {
        echo "<tr>
                <td colspan='2'>Data tahun ".$tahun." tidak ditemukan</td>;
              </tr>";
      }

      return json_encode($data);

  }

}
