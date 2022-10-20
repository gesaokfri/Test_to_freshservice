<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\KeuLabaRugi;

use App\Models\View\Unika\Keuangan\ViewProdiPiutangAging;
use App\Models\Yayasan\Portal\ViewSdmReport;

class HomeController extends BaseController
{

  public function Index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      // pass data for filtering
      for ($i = date('Y'); $i >= 2019 ; $i--) {
        $data['tahun'][] = $i;
      }
      return $this->blade->render("pages.portal.home", $data);
    }
  }

  public function Res()
  {
    return $this->blade->render("pages.portal.res");
  }

  public function headcountChart()
  {
    $sdmReport = new ViewSdmReport();

    $getTipeFilter    = $this->request->getPost("tipe");
    $getTahunTerakhir = $this->request->getPost("tahun");
    $getQuarter       = $this->request->getPost("quarter");

    // if - set default tahun jika tidak memilih
    if (empty($getTipeFilter)) {
      $getTipeFilter = "tahun";
    }

    // if - menyesuaikan dengan tahun ini jika tidak memilih tahun
    if ($getTahunTerakhir == "") {
      $getTahunTerakhir = date("Y");
    }

    $i = 0;

    // get - 5 tahun kebelakang dari tahun terpilih
    $listTahun = [];
    $passFiveYears = $getTahunTerakhir - 4; // get 5 tahung kebelakang
    for ($i = $passFiveYears; $i <= $getTahunTerakhir; $i++) {
      if ($i <= "2018") { continue; }
      $listTahun[] = $i;
    }

    // if - jika filter terpilih tipe tahun
    if ($getTipeFilter == "tahun") {
      
      // parent array, untuk menampung data tiap tahun 5x
      foreach ($listTahun as $key => $value) {

        if ($value == date('Y')) {

          $result = $sdmReport
          ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
          ->where("Bulan", date('m'))
          ->where("Tahun", $value)
          ->first();

          if(empty($result['Pria']) && empty($result['Wanita'])) {

            if(date('m') == '01') {
              $bulan = 12;
              $tahun = $value-1;
            } else {
              $bulan = sprintf("%02d", (date('m')-1));
              $tahun = date('Y');
            }

            $result = $sdmReport
            ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
            ->where("Bulan", $bulan)
            ->where("Tahun", $tahun)
            ->first();

            if (empty($result["Pria"]) && empty($result["Wanita"])) {

              $bulan = date('m', strtotime("-2 month"));
              $tahun = date('Y');

              $result = $sdmReport
              ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
              ->where("Bulan", $bulan)
              ->where("Tahun", $tahun)
              ->first();

              if (empty($result["Pria"]) && empty($result["Wanita"])) {

                $bulan = date('m', strtotime("-3 month"));
                $tahun = date('Y');

                $result = $sdmReport
                ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
                ->where("Bulan", $bulan)
                ->where("Tahun", $tahun)
                ->first();

              }
            }
          }
          
        } else {

          $result = $sdmReport
          ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
          ->where("Bulan", "12")
          ->where("Tahun", $value)
          ->first();
          
        }
        
        // jika result query ada
        if(!empty($result['Pria']) && !empty($result['Wanita'])) {
            // pass chart data
            $dataPria   [$key] = $result['Pria'];
            $dataWanita [$key] = $result['Wanita'];
            $dataTotalPW[$key] = $result['Pria'] + $result['Wanita'];
            $lastFiveYears[] = [
                "$value", "Total - ". "$dataTotalPW[$key]"
            ];
        // jika result query tidak ada, maka data di-Nol kan
        } else {
            // pass chart data
            $dataPria     [$key] = null;
            $dataWanita   [$key] = null;
            $dataTotalPW  [$key] = null;
            $lastFiveYears[]     = [
                null
            ];
        }
      }

      // set array data untuk chart
      $data['wanita']      = json_encode($dataWanita);
      $data['pria']        = json_encode($dataPria);
      $data["xCategories"] = json_encode($lastFiveYears);
      
    } else if ($getTipeFilter == "quarter") {
      $checkBulan = $sdmReport
      ->select("Bulan")
      ->where("Tahun", $getTahunTerakhir)
      ->groupBy("Bulan")
      ->findAll();
  
      $listQuarter = [];
      if(in_array("03", array_column($checkBulan, 'Bulan'))) {
        $listQuarter[] = "03";
      }
      if(in_array("06", array_column($checkBulan, 'Bulan'))) {
        $listQuarter[] = "06";
      }
      if(in_array("09", array_column($checkBulan, 'Bulan'))) {
        $listQuarter[] = "09";
      }
      if(in_array("12", array_column($checkBulan, 'Bulan'))) {
        $listQuarter[] = "12";
      }
      
      // parent array, untuk menampung data tiap tahun 5x
      foreach ($listQuarter as $key => $value) {
        $result = $sdmReport
        ->select("(SELECT SUM(Pria)) AS Pria, (SELECT SUM(Wanita)) AS Wanita")
        ->where("Bulan", $value)
        ->where("Tahun", $getTahunTerakhir)
        ->first();

        // jika result query ada
        if(!empty($result['Pria']) && !empty($result['Wanita'])) {
          // pass chart data
          $dataPria   [$key] = $result['Pria'];
          $dataWanita [$key] = $result['Wanita'];
          $dataTotalPW[$key] = $result['Pria'] + $result['Wanita'];
          $loopQuarter = $key + 1;
          $quarter[] = [
              "$dataTotalPW[$key]", "Q".$loopQuarter." - "."$getTahunTerakhir"
          ];
        } else {
            // pass chart data
            $dataPria   [$key] = null;
            $dataWanita [$key] = null;
            $dataTotalPW[$key] = null;
            $loopQuarter = $key + 1;
            $quarter[] = [
                "$dataTotalPW[$key]", "Q".$loopQuarter." - "."$getTahunTerakhir"
            ];
        }
            
      }
      // set - array data untuk chart
      $data['wanita']      = json_encode($dataWanita);
      $data['pria']        = json_encode($dataPria);
      $data["xCategories"] = json_encode($quarter);
    }


    
    return $this->blade->render("pages.portal.chart", $data);

  }

  public function pbKonsolidasiChart()
  {
    $filter_type    = $this->request->getPost('Filter');
    $tahunTerakhir  = $this->request->getPost('Year');
    $quarter_get    = $this->request->getPost('Quarter');

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
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
        ->first();

        if (empty($getValue["total"])) {
          $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Pendapatan")
          ->where("group_type", "1")
          ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
      $chartPBYAJ[0]["data"] = $data_array;

      //Beban
      $chartPBYAJ[1]["name"] = "Beban";
      $data_array  = array();
      for ($perthn = 0; $perthn <= 4; $perthn++) {
        $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
        ->where("status", "1")
        ->where("laba_rugi_name", "Beban")
        ->where("group_type", "2")
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
        ->where("laba_rugi_period", $listTahun[$perthn] . "-12")
        ->first();
        if (empty($getValue["total"])) {
          $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
          ->where("status", "1")
          ->where("laba_rugi_name", "Beban")
          ->where("group_type", "2")
          ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();


        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, $getValue["total"]*-1);
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
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, $getValue["total"]);
        }
      }
      $chartPBYAJ[1]["data"] = $data_array;
      // END MAIN FUNCTION

      $ListQuarter = ['Q1 ' . $tahunTerakhir, 'Q2 ' . $tahunTerakhir, 'Q3 ' . $tahunTerakhir, 'Q4 ' . $tahunTerakhir];

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartPBYAJ"]              = json_encode($chartPBYAJ);
      $data["ListQuarter"]             = json_encode($ListQuarter);
      return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_quarter", $data);
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
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();

        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, $getValue["total"]*-1);
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
        ->whereIn("laba_rugi_group", ["1", "2", "3"])
        ->whereIn('laba_rugi_period', [$dt1, $dt2, $dt3])
        ->first();
        if (empty($getValue)) {
          array_push($data_array, 0);
        } else {
          array_push($data_array, $getValue["total"]);
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
      return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_quarter_komparasi", $data);
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                              ->whereIn("laba_rugi_group", ["1", "2", "3"])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
          return $this->blade->render("pages/laporan_keuangan/konsolidasi_pb_yaj_chart_tahun", $data);
    }
  }

  public function kenaikanPbKonsolidasiChart()
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                              ->whereIn("laba_rugi_group", ["1", "2", "3"])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                              ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                              ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();

              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Pendapatan")
                ->where("group_type", "1")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                              ->whereIn("laba_rugi_group", ["1", "2", "3"])
                              ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                              ->where("laba_rugi_period",$ListYearMonth[$perthn])
                              ->first();
              if (empty($getValue["total"])) {
                $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                ->where("status", "1")
                ->where("laba_rugi_name", "Beban")
                ->where("group_type", "2")
                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
        $data["countMonth"]                 = @$countMonth;
        $data["chartKenaikanPB"]            = json_encode($chartKenaikanPB);
        $data["LastFiveYears"]              = json_encode($ListYearMonth);
        return $this->blade->render("pages/laporan_keuangan/kenaikan_pb_chart_tahun", $data);
          
      }
  }

  public function pbKonsolidasiTable()
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                  ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Pendapatan")
                  ->where("group_type", "1")
                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                $getValue  = $LabaRugi->select('sum(laba_rugi_value) as total')
                                ->where("status","1")
                                ->where("laba_rugi_name", "Beban")
                                ->where("group_type","2")
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period", $listTahun[$perthn]."-12")
                                ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Beban")
                  ->where("group_type", "2")
                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                  ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                  ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                  ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Pendapatan")
                  ->where("group_type", "1")
                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
                                ->where("laba_rugi_value is NOT NULL", NULL, FALSE)
                                ->where("laba_rugi_period",$ListYearMonth[$perthn])
                                ->first();
                if (empty($getValue["total"])) {
                  $getValue  =  $LabaRugi->select('sum(laba_rugi_value) as total')
                  ->where("status", "1")
                  ->where("laba_rugi_name", "Beban")
                  ->where("group_type", "2")
                  ->whereIn("laba_rugi_group", ["1", "2", "3"])
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
                                ->whereIn("laba_rugi_group", ["1", "2", "3"])
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

  public function ProdiPiutangAging(){
    $parameter    = $this->request->getPost("parameter");
    $prodi        = new ViewProdiPiutangAging;

    $ListProdi    = $prodi->select('NAMA_PRODI')->groupBy('NAMA_PRODI')->where("ACAD_CAREER",$parameter)->findAll();
    echo "<option value=''>Pilih Prodi</option>";
    foreach($ListProdi as $list){
        if(empty($list['NAMA_PRODI']) || $list['NAMA_PRODI']==NULL || $list['NAMA_PRODI']=="NULL"){ }
        else {
          echo "<option value='".$list['NAMA_PRODI']."'>".$list['NAMA_PRODI']."</option>";
        }
    }
  }

}
