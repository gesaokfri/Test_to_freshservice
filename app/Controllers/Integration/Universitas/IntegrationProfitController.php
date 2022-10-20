<?php

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;
use App\Models\KeuNeraca;
use App\Models\KeuLabaRugi;
use App\Models\KeuCapex;
use App\Models\KeuCashflow;

class IntegrationProfitController extends BaseController
{

  public  $mydata;
  private $db;

  public function __construct() {
    $this->db = db_connect();

    //Fakultas Array
    $this->Data_fakultas = array(
          "211"=>"FE - Manajemen",
          "212"=>"FE - Akuntansi",
          "213"=>"FE - Ekonomi Pembangunan",
          "214"=>"FE - PPAK",
          "215"=>"FE - Magister Manajemen",
          "216"=>"FE - Magister Akuntansi",
          "217"=>"FE - Magister Ekonomi Terapan",
          "221"=>"FIABIKOM - Administrasi Bisnis",
          "222"=>"FIABIKOM - Ilmu Komunikasi",
          "223"=>"FIABIKOM - Hospitality",
          "224"=>"FIABIKOM - Magister Administrasi Bisnis",
          "231"=>"FPB - Bahasa Inggris",
          "232"=>"FPB - Pendidikan Keagamaan Katolik",
          "233"=>"FPB - Bimbingan Konseling",
          "234"=>"FPB - PGSD",
          "235"=>"FPB - Magister LTBI",
          "236"=>"FPB - Doktor LTBI",
          "237"=>"FPB - PGSD PJJ",
          "238"=>"FPB - Pendidikan Profesi Guru",
          "241"=>"FT - Mesin",
          "242"=>"FT - Elektro",
          "243"=>"FT - Industri",
          "244"=>"FT - Magister Teknik Mesin",
          "245"=>"FT - Magister Teknik Elektro",
          "246"=>"FT - Program Profesi Insinyur",
          "247"=>"FT - Manufaktur",
          "248"=>"FT - Sistem Informasi",
          "251"=>"FH - Ilmu Hukum",
          "252"=>"FH - Magister Ilmu Hukum",
          "261"=>"FK - PSSK",
          "262"=>"FK - PSPD",
          "264"=>"FK - Farmasi",
          "265"=>"FK - Magister Biomedik",
          "271"=>"FP - Psikologi",
          "272"=>"FP - Magister Psikologi Profesi",
          "273"=>"FP - Magister Psikologi",
          "274"=>"FP - Doktor Psikologi",
          "281"=>"FTB - Biologi",
          "282"=>"FTB - Teknologi Pangan",
          "283"=>"FTB - Magister Bioteknologi"
          );

    //Convert array to string
    $getKodeFakultas="";
    foreach($this->Data_fakultas as $key => $value) { 
      $getKodeFakultas .= "'".$key."',";
    } 
    $this->getKodeFakultas= rtrim($getKodeFakultas,',');


    //Fakultas Secretariat Array
    $this->Data_fakultas_secre = array(
          "21"=>"FE",
          "22"=>"FIABIKOM",
          "23"=>"FPB",
          "24"=>"FT",
          "25"=>"FH",
          "26"=>"FK",
          "27"=>"FP",
          "28"=>"FTB"
          );
    $getKodeSecre="";
    foreach($this->Data_fakultas_secre as $key => $value) { 
      $getKodeSecre .= "'".$key."',";
    } 
    $this->getKodeSecre= rtrim($getKodeSecre,',');


    //Count Prodi per Array
    $this->count_prodi_fk = array();
    foreach($this->Data_fakultas as $val){
      $fk_prodi = explode("-",$val);
      $fks      = trim($fk_prodi[0]);
      if (array_key_exists($fks,$this->count_prodi_fk)){
        $this->count_prodi_fk[$fks] += 1;
      }
      else {
        $this->count_prodi_fk[$fks] = 1;
      }
    }

  }
  
  public function Index() { 
      $thn   = date('y');
      $group = "02"; //01 = Yayasan, 02 = Unika
      
      if(date('m')=="01"){
        $DataList = array(
          'DEC-' . $thn,
          'ADJ-' . $thn,
        );
      }
      else {
        $DataList = array(
          date('M', strtotime("-1 Month")).'-' . $thn,
        );
      }
  
      // $DataList = array(
      //   'JAN-' . $thn,
      //   'FEB-' . $thn,
      //   'MAR-' . $thn,
      //   'APR-' . $thn,
      //   'MAY-' . $thn,
      //   'JUN-' . $thn,
      //   'JUL-' . $thn,
      //   'AUG-' . $thn,
      //   'SEP-' . $thn,
      //   'OCT-' . $thn,
      //   'NOV-' . $thn,
      //   'DEC-' . $thn,
      //   'ADJ-' . $thn,
      // );

      foreach($DataList as $list){
        $this->getProfitPendapatan($list,$group);     
        $this->getProfitBeban($list,$group); 
        $this->getProfitPendapatanNonFakultas($list,$group);     
        $this->getProfitBebanNonFakultas($list,$group);     
        //echo $list."<br/>";
      }
  }

  public function getProfitPendapatan($monthYEAR_req,$group_req) { 
    $db          = $this->db;
    $group       = $group_req; //01 = Yayasan, 02 = Unika
    $period      = $monthYEAR_req; // JAN-18
    $period_exp  = explode("-",$period); //Explode Period

    $query = "select ( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR ) AS total, bl.PERIOD_NAME, cc.SEGMENT1 AS group_data,cd.DESCRIPTION,cc.SEGMENT4,cc.SEGMENT5,cc.SEGMENT3
      FROM StagingData.dbo.GL_CODE_DESC AS cd
        RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
        RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
      WHERE
        cd.FLEX_VALUE BETWEEN '4101001' and '4303026' and cc.SEGMENT4 in (".$this->getKodeFakultas.") 
        and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";

    $arrayPendapatanJasa = array("4101001","4101002","4101003","4101004","4101005","4101006","4101007",
    "4101008","4101009","4101010","4101011","4101012","4101013","4102002","4104001","4104003","4104004","4106001","4106002","4106003","4302002");
    $arrayPotonganJasa = array("4103001","4103002","4103003","4103004","4103005","4103006","4103007","4103008","4103009","4103010");

    $arrayPendapatanLainLain = array("4105001","4105002","4105003","4105019","4106004","4106005","4301001","4301002","4301003","4301004","4302001","4302004","4302005","4303001","4303002","4303003","4303004","4303005","4303006","4303007","4303008","4303009","4303012","4303019");
    
    $query .= " and bl.PERIOD_NAME='".$period."'";
    
    $getList       = $db->query($query);
    $periodConvert = "20".$period_exp[1]."-".getMonthNumberCashflow($period_exp[0]);
    $arrData=[];
    
    foreach ($getList->getResult() as $list) { 
       if (array_key_exists($list->SEGMENT4,$this->Data_fakultas)){

          if(empty($arrData[$list->SEGMENT4])){
              $Newdata =  array (
                            'Pendapatan Jasa'      => 0,
                            'Pendapatan Lain-Lain' => 0
                          );
              $arrData[$list->SEGMENT4] = $Newdata;
          }

          //Pendapatan Lain-Lain
          $find_coa1 = in_array($list->SEGMENT5, $arrayPendapatanLainLain);
          $find_coa2 = in_array($list->SEGMENT5, $arrayPendapatanJasa);
          $find_coa3 = in_array($list->SEGMENT5, $arrayPotonganJasa);
          if ($find_coa1 !== false) { // Pendapatan Lain Lain
            if(!empty($arrData[$list->SEGMENT4])){ 
              $total =  $arrData[$list->SEGMENT4]['Pendapatan Lain-Lain'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Pendapatan Lain-Lain : " . int_to_rp($total) . "<br>";

              $arrData[$list->SEGMENT4]['Pendapatan Lain-Lain']=$total;
            }
          } elseif ($find_coa2 !== false) { // Pendapatan Jasa
            if(!empty($arrData[$list->SEGMENT4])){
              $total =  $arrData[$list->SEGMENT4]['Pendapatan Jasa'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Pendapatan Jasa : " . int_to_rp($total) . "<br>";

              $arrData[$list->SEGMENT4]['Pendapatan Jasa']=$total;
            }
          } elseif ($find_coa3 !== false) { // Potongan Jasa
            if (!empty($arrData[$list->SEGMENT4])) { 
              $total =  $arrData[$list->SEGMENT4]['Pendapatan Jasa'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Pendapatan Jasa : " . int_to_rp($total) . "<br>";

              $arrData[$list->SEGMENT4]['Pendapatan Jasa']=$total;
            }
          }
          
       } 
    }

    //Start Sum Pendapatan Fakultas Secretariat
      $sumPendapatanSecreatiat = array();
      $query_fk_secretariat = "select sum(( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR )) AS total,cc.SEGMENT3
        FROM StagingData.dbo.GL_CODE_DESC AS cd
          RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
          RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
        WHERE cd.FLEX_VALUE BETWEEN '4101001' and '4303026' and cc.SEGMENT3 in (".$this->getKodeSecre.") and cc.SEGMENT4='000' and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";
      
      $query_fk_secretariat .= " and bl.PERIOD_NAME='".$period. "' GROUP BY cc.SEGMENT3";
      $getListSecreatriat = $db->query($query_fk_secretariat);

      foreach ($getListSecreatriat->getResult() as $list) {  
        $fks_secre = $this->Data_fakultas_secre[$list->SEGMENT3];

        $total_pendapatan_secre = 0;
        if((int)$list->total<0){
          $total_pendapatan_secre = (int)$list->total;
          // echo "&emsp;&emsp;" . $list->total . " | Total Pendapatan Secre : " . int_to_rp($total_pendapatan_secre) . "<br>";
        }
        else {
          $total_pendapatan_secre = (int)$list->total;
          // echo "&emsp;&emsp;" . $list->total . " | Total Pendapatan Secre : " . int_to_rp($total_pendapatan_secre) . "<br>";
        }

        $sumPendapatanSecre[$fks_secre] = (int)$total_pendapatan_secre / (int)$this->count_prodi_fk[$fks_secre];
      }

      foreach ($this->Data_fakultas as $key => $value) {
        $fakultasDesc = explode("-",$this->Data_fakultas[$key]);
        $fks          = trim($fakultasDesc[0]);

        $totalPendapatanSecre = $sumPendapatanSecre[$fks];
        // echo $key . " => " . $value . " = " . $totalPendapatanSecre . "<br>";

        //Pendapatan Secre
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", $fks)
        ->where("remark", trim($fakultasDesc[1]))
        ->where("cashflow_name", "Pendapatan Secre")
        ->where("cashflow_number", "1")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => (int)$totalPendapatanSecre,
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => (int)$totalPendapatanSecre,
            "segment4"        => $key,
            "fakultas"        => $fks,                     //Fakultas
            "remark"          => trim($fakultasDesc[1]),   //Prodi
            "cashflow_name"   => "Pendapatan Secre",
            "cashflow_number" => "1",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }
      }
    //End Sum Pendapatan Fakultas Secretariat

    foreach($arrData as $key => $value ){

      $fakultasDesc = explode("-",$this->Data_fakultas[$key]);
      $fks          = trim($fakultasDesc[0]);

      $total_jasa = (int)$arrData[$key]['Pendapatan Jasa'];
      $total_lain = (int)$arrData[$key]['Pendapatan Lain-Lain'];

      // echo "<hr>";
      // echo "<br>";
      // echo "<br> TOTAL PENDAPATAN JASA " . $total_jasa;
      // echo "<br> TOTAL PENDAPATAN LAIN-LAIN " . $total_lain;
      // echo "<br>" . $period . " " . $this->Data_fakultas[$key];

      //Pendapatan Jasa
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", $fks)
        ->where("remark", trim($fakultasDesc[1]))
        ->where("cashflow_name", "Pendapatan Jasa")
        ->where("cashflow_number", "1")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_jasa,
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $total_jasa,
            "segment4"        => $key,
            "fakultas"        => $fks,                     //Fakultas
            "remark"          => trim($fakultasDesc[1]),   //Prodi
            "cashflow_name"   => "Pendapatan Jasa",
            "cashflow_number" => "1",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Pendapatan Lain-Lain
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", $fks)
        ->where("remark", trim($fakultasDesc[1]))
        ->where("cashflow_name", "Pendapatan Lain-Lain")
        ->where("cashflow_number", "2")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
            "cashflow_value" => $total_lain,
            "status"         => "1",
            "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $total_lain,
            "segment4"        => $key,
            "fakultas"        => $fks,                     //Fakultas
            "remark"          => trim($fakultasDesc[1]),   //Prodi
            "cashflow_name"   => "Pendapatan Lain-Lain",
            "cashflow_number" => "2",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }


      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // echo "<hr>";
    }
  }

  public function getProfitBeban($monthYEAR_req,$group_req) { 
    $db          = $this->db;
    $group       = $group_req; //01 = Yayasan, 02 = Unika
    $period      = $monthYEAR_req; // JAN-18
    $period_exp  = explode("-",$period); //Explode Period


    //Array Beban List
    $arrBebanPegawai         = array("5101002", "5101003", "5101004", "5101005", "5101006", "5102003", "5102004", "5102005", "5102007", "5102008", "5102009", "5102010", "5102012", "5102013", "5102014", "5102015", "5102016", "5103001", "5103002", "5103003", "5103004", "5103005", "5103007", "5103009", "5103010", "5103011", "5103012", "5103013", "5103014", "5103015", "5103016", "5103017", "5103018", "5103019", "5103020", "5103021", "5104002", "5104004", "5104005", "5104006", "5104007", "5104008", "5105001", "5105002", "5105003", "5105004", "5105005", "5106002", "5106004", "5107001", "5201001", "5201002", "5201010", "5201011", "5204004", "5204007", "5406018", "5406019", "5406039", "5406040");

    $arrBebanUtilitas        = array("5406022", "5406023", "5406024", "5406030");

    $arrBebanPemeliharaan    = array("5103006", "5106001", "5106003", "5406008", "5501001", "5501002", "5501003", "5501004", "5501005", "5501006", "5501007", "5501008", "5501009", "5501010", "5501011", "5501020");

    $arrBebanPenyusutan      = array("5601001", "5601002", "5601003", "5601004", "5601005", "5601006", "5601007", "5601008", "5601009", "5601010", "5601011");

    $arrBebanJasaProfesional = array("5201003", "5401001", "5401002", "5401003");

    $arrBebanIklan           = array("5402001");

    $arrBebanPerlengkapanKantorKonsumsi  = array("5201005", "5201006", "5201007", "5201009", "5201014", "5201015", "5201018", "5204005", "5204008", "5204009", "5204010", "5205004", "5404001", "5404002", "5406001", "5406002", "5406003", "5406004", "5406006", "5406010", "5406011", "5406012", "5406013", "5406015", "5406016", "5406017");

    $arrBebanLainLain  = array("5201008", "5201012", "5201013", "5201016", "5201017", "5201019", "5201020", "5201021", "5201022", "5201023", "5201024", "5202001", "5202002", "5202003", "5203001", "5203002", "5203003", "5204001", "5204006", "5204011", "5205001", "5205002", "5205003", "5206001", "5402001", "5403001", "5406020", "5406025", "5406026", "5406028", "5406031", "5406034", "5406035", "5406036", "5406038", "5406998", "5406999");

    $query = "select ( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR ) AS total, bl.PERIOD_NAME, cc.SEGMENT1 AS group_data,cd.DESCRIPTION,cc.SEGMENT4,cd.FLEX_VALUE
      FROM StagingData.dbo.GL_CODE_DESC AS cd
        RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
        RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
      WHERE
        cd.FLEX_VALUE BETWEEN '5101001' and '5604001' and cc.SEGMENT4 in (".$this->getKodeFakultas.") 
        and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";
    
    $query .= " and bl.PERIOD_NAME='".$period."'";

    $getList  = $db->query($query);
    $periodConvert = "20".$period_exp[1]."-".getMonthNumberCashflow($period_exp[0]);
    $arrData=[];

    foreach ($getList->getResult() as $list) {  
       if (array_key_exists($list->SEGMENT4,$this->Data_fakultas)){

          if(empty($arrData[$list->SEGMENT4])){
              $Newdata =  array (
                                'Beban Pegawai'               => 0,
                                'Beban Penyusutan'            => 0,
                                'Beban Pemeliharaan'          => 0,
                                'Beban Iklan'                 => 0,
                                'Beban Utilitas'              => 0,
                                'Beban Jasa profesional'      => 0,
                                'Beban Lain'                  => 0,
                                'Beban Perlengkapan Kantor Dan Konsumsi' => 0
                            );
              $arrData[$list->SEGMENT4] = $Newdata;
          }


          if (in_array($list->FLEX_VALUE, $arrBebanPegawai)){
            $total =  $arrData[$list->SEGMENT4]['Beban Pegawai'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Pegawai : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Pegawai']=$total;
          } 
          else if (in_array($list->FLEX_VALUE, $arrBebanUtilitas)){
            $total =  $arrData[$list->SEGMENT4]['Beban Utilitas'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Utilitas : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Utilitas']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPemeliharaan)){
            $total =  $arrData[$list->SEGMENT4]['Beban Pemeliharaan'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Pemeliharaan : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Pemeliharaan']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPenyusutan)){
            $total =  $arrData[$list->SEGMENT4]['Beban Penyusutan'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Penyusutan : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Penyusutan']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanJasaProfesional)){
            $total =  $arrData[$list->SEGMENT4]['Beban Jasa profesional'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Jasa Profesional : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Jasa profesional']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanIklan)){
            $total =  $arrData[$list->SEGMENT4]['Beban Iklan'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Iklan : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Iklan']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPerlengkapanKantorKonsumsi)){
            $total =  $arrData[$list->SEGMENT4]['Beban Perlengkapan Kantor Dan Konsumsi'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Perlengkapan Kantor Dan Konsumsi : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Perlengkapan Kantor Dan Konsumsi']=$total;
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanLainLain)){
            $total =  $arrData[$list->SEGMENT4]['Beban Lain'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Lain : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Lain']=$total;
          }
          else {
            $total =  $arrData[$list->SEGMENT4]['Beban Lain'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Lain : " . int_to_rp($total) . "<br>";
            $arrData[$list->SEGMENT4]['Beban Lain']=$total;
          }
       } 
    }

    // echo "<pre>";
    // print_r($arrData);
    // echo "</pre>";

    //Start Sum Beban Fakultas Secretariat
      $sumBebanSecreatiat = array();
      $query_fk_secretariat = "select sum(( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR )) AS total,cc.SEGMENT3
        FROM StagingData.dbo.GL_CODE_DESC AS cd
          RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
          RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
        WHERE cd.FLEX_VALUE BETWEEN '5101001' and '5604001' and cc.SEGMENT3 in (".$this->getKodeSecre.") and cc.SEGMENT4='000' and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";
      
      $query_fk_secretariat .= " and bl.PERIOD_NAME='".$period. "' GROUP BY cc.SEGMENT3";
      $getListSecreatriat = $db->query($query_fk_secretariat);

      foreach ($getListSecreatriat->getResult() as $list) {  
        $fks_secre = $this->Data_fakultas_secre[$list->SEGMENT3];

        $total_beban_secre = 0;
        if((int)$list->total<0){
          $total_beban_secre = (int)$list->total;
          // echo "&emsp;&emsp;" . " -> " . $list->total . " | Total Beban Secre : " . int_to_rp($total_beban_secre) . "<br>";
        }
        else {
          $total_beban_secre = (int)$list->total;
          // echo "&emsp;&emsp;" . " -> " . $list->total . " | Total Beban Secre : " . int_to_rp($total_beban_secre) . "<br>";
        }

        $sumBebanSecreatiat[$fks_secre] = (int)$total_beban_secre / (int)$this->count_prodi_fk[$fks_secre];
      }

      foreach ($this->Data_fakultas as $key => $value) {
        $fakultasDesc = explode("-",$this->Data_fakultas[$key]);
        $fks          = trim($fakultasDesc[0]);

        $totalBebanSecre = $sumBebanSecreatiat[$fks];
        // echo $key . " => " . $value . " = " . $totalBebanSecre . "<br>";

        //Beban Secre
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", $fks)
        ->where("remark", trim($fakultasDesc[1]))
        ->where("cashflow_name", "Beban Secre")
        ->where("cashflow_number", "1")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => (int)$totalBebanSecre,
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => (int)$totalBebanSecre,
            "segment4"        => $key,
            "fakultas"        => $fks,                     //Fakultas
            "remark"          => trim($fakultasDesc[1]),   //Prodi
            "cashflow_name"   => "Beban Secre",
            "cashflow_number" => "1",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }
      }
    //End Sum Beban Fakultas Secretariat

    foreach($arrData as $key => $value ){
      $fakultasDesc = explode("-",$this->Data_fakultas[$key]);
      $fks          = trim($fakultasDesc[0]);

      $total_pegawai      = (int)$arrData[$key]['Beban Pegawai'];
      $total_penyusutan   = (int)$arrData[$key]['Beban Penyusutan'];
      $total_pemeliharaan = (int)$arrData[$key]['Beban Pemeliharaan'];
      $total_iklan        = (int)$arrData[$key]['Beban Iklan'];
      $total_utilitas     = (int)$arrData[$key]['Beban Utilitas'];
      $total_jp           = (int)$arrData[$key]['Beban Jasa profesional'];
      $total_bpkk         = (int)$arrData[$key]['Beban Perlengkapan Kantor Dan Konsumsi'];
      $total_lain         = (int)$arrData[$key]['Beban Lain'];

      // echo "<hr>";
      // echo "<br>";
      // echo "<br> TOTAL BEBAN PEGAWAI " . $total_pegawai;
      // echo "<br> TOTAL BEBAN PENYUSUTAN " . $total_penyusutan;
      // echo "<br> TOTAL BEBAN PEMELIHARAAN " . $total_pemeliharaan;
      // echo "<br> TOTAL BEBAN IKLAN " . $total_iklan;
      // echo "<br> TOTAL BEBAN UTILITAS " . $total_utilitas;
      // echo "<br> TOTAL BEBAN JASA PROFESIONAL " . $total_jp;
      // echo "<br> TOTAL BEBAN PERLENGKAPAN KANTOR DAN KONSUMSI " . $total_bpkk;
      // echo "<br> TOTAL BEBAN LAIN " . $total_lain;
      // echo "<br> TOTAL BEBAN " . ($total_pegawai + $total_penyusutan + $total_pemeliharaan + $total_iklan + $total_utilitas + $total_jp + $total_bpkk + $total_lain);
      // echo "<br>" . $period . " " . $this->Data_fakultas[$key];

      //Beban Pegawai
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Pegawai")
      ->where("cashflow_number", "3")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_pegawai,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_pegawai,
          "segment4"        => $key,
          "fakultas"        => $fks,                     //Fakultas
          "remark"          => trim($fakultasDesc[1]),   //Prodi
          "cashflow_name"   => "Beban Pegawai",
          "cashflow_number" => "3",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Penyusutan
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Penyusutan")
      ->where("cashflow_number", "4")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_penyusutan,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_penyusutan,
          "segment4"        => $key,
          "fakultas"        => $fks,                     //Fakultas
          "remark"          => trim($fakultasDesc[1]),   //Prodi
          "cashflow_name"   => "Beban Penyusutan",
          "cashflow_number" => "4",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";  


      //Beban Pemeliharaan
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();
   
      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Pemeliharaan")
      ->where("cashflow_number", "5")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_pemeliharaan,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_pemeliharaan,
          "segment4"        => $key,
          "fakultas"        => $fks,                     //Fakultas
          "remark"          => trim($fakultasDesc[1]),   //Prodi
          "cashflow_name"   => "Beban Pemeliharaan",
          "cashflow_number" => "5",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Iklan
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Iklan")
      ->where("cashflow_number", "6")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_iklan,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_iklan,
          "segment4"        => $key,
          "fakultas"        => $fks,                     //Fakultas
          "remark"          => trim($fakultasDesc[1]),   //Prodi
          "cashflow_name"   => "Beban Iklan",
          "cashflow_number" => "6",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Utilitas
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Utilitas")
      ->where("cashflow_number", "7")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_utilitas,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_utilitas,
          "segment4"        => $key,
          "fakultas"        => $fks,                     //Fakultas
          "remark"          => trim($fakultasDesc[1]),   //Prodi
          "cashflow_name"   => "Beban Utilitas",
          "cashflow_number" => "7",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      ///Beban Jasa profesional
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Jasa profesional")
      ->where("cashflow_number", "8")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_jp,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_jp,
          "segment4"        => $key,
          "fakultas"        => $fks,  //Fakultas
          "remark"          => trim($fakultasDesc[1]), //Prodi
          "cashflow_name"   => "Beban Jasa profesional",
          "cashflow_number" => "8",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Perlengkapan Kantor Dan Konsumsi
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Perlengkapan Kantor Dan Konsumsi")
      ->where("cashflow_number", "9")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $total_bpkk,
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_bpkk,
          "segment4"        => $key,
          "fakultas"        => $fks,                                       //Fakultas
          "remark"          => trim($fakultasDesc[1]),                     //Prodi
          "cashflow_name"   => "Beban Perlengkapan Kantor Dan Konsumsi",
          "cashflow_number" => "9",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Lain
      $Insert   = new KeuCashflow;
      $CashFlow = new KeuCashflow();

      // CHECK
      $check = $CashFlow->where("cashflow_period", $periodConvert)
      ->where("cashflow_group", (int)$group)
      ->where("segment4", $key)
      ->where("fakultas", $fks)
      ->where("remark", trim($fakultasDesc[1]))
      ->where("cashflow_name", "Beban Lain - Lain")
      ->where("cashflow_number", "10")
      ->where("status", "1")
      ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value"  => $total_lain,
          "status"          => "1",
          "updated_by"      => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $total_lain,
          "segment4"        => $key,
          "fakultas"        => $fks,  //Fakultas
          "remark"          => trim($fakultasDesc[1]), //Prodi
          "cashflow_name"   => "Beban Lain - Lain",
          "cashflow_number" => "10",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // echo "<hr>";
    }
  }

  public function getProfitPendapatanNonFakultas($monthYEAR_req,$group_req) { 
    $db          = $this->db;
    $group       = $group_req; //01 = Yayasan, 02 = Unika
    $period      = $monthYEAR_req; // JAN-18
    $period_exp  = explode("-",$period); //Explode Period


    $query = "select ( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR ) AS total, bl.PERIOD_NAME, cc.SEGMENT1 AS group_data,cd.DESCRIPTION,cc.SEGMENT4,cc.SEGMENT5 
      FROM StagingData.dbo.GL_CODE_DESC AS cd
        RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
        RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
      WHERE
        cd.FLEX_VALUE BETWEEN '4101001' and '4303026' and cc.SEGMENT4 not in (" . $this->getKodeFakultas . ") and cc.SEGMENT3 not in (" . $this->getKodeSecre . ") and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";

    $arrayPendapatanJasa = array("4101002", "4101012", "4103004", "4104001", "4104002", "4106001", "4106003");
    $arraySegment4PendapatanJasa = array("000", "311", "381", "382", "312");

    $arrayPendapatanLainLain = array("4105019", "4106005", "4301001", "4301002", "4301003", "4302001", "4302004", "4302005", "4303001", "4303002", "4303003", "4303004", "4303006", "4303007", "4303008", "4303009", "4303012", "4303019", "4303022");
    $arraySegment4PendapatanLainLain = array("681", "685", "686", "687", "312", "688", "322", "000");

    $query .= " and bl.PERIOD_NAME='".$period."'";

    $getList  = $db->query($query);
    $periodConvert = "20".$period_exp[1]."-".getMonthNumberCashflow($period_exp[0]);
    $arrData=[];
    
    foreach ($getList->getResult() as $list) {  
          if(empty($arrData[$list->SEGMENT4])){
              $Newdata =  array (
                            'Pendapatan Jasa'      => 0,
                            'Pendapatan Lain-Lain' => 0
                          );
              $arrData[$list->SEGMENT4] = $Newdata;
          }

      //Pendapatan Lain-Lain
      $find_coa1 = in_array($list->SEGMENT5, $arrayPendapatanLainLain);
      $find_coa2 = in_array($list->SEGMENT5, $arrayPendapatanJasa);
      if ($find_coa1 !== false) { // Pendapatan Lain Lain
        if (!empty($arrData[$list->SEGMENT4])) {
          if (in_array($list->SEGMENT4, $arraySegment4PendapatanLainLain)) {
            $total =  $arrData[$list->SEGMENT4]['Pendapatan Lain-Lain'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Pendapatan Lain-Lain : " . int_to_rp($total) . "<br>";
  
            $arrData[$list->SEGMENT4]['Pendapatan Lain-Lain'] = $total;
          }
        }
      } elseif ($find_coa2 !== false) { // Pendapatan Jasa
        if (!empty($arrData[$list->SEGMENT4])) {
          if (in_array($list->SEGMENT4, $arraySegment4PendapatanJasa)) {
            $total =  $arrData[$list->SEGMENT4]['Pendapatan Jasa'] + (int)$list->total;
            // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Pendapatan Jasa : " . int_to_rp($total) . "<br>";
  
            $arrData[$list->SEGMENT4]['Pendapatan Jasa'] = $total;
          }
        }
      }
    }

    foreach($arrData as $key => $value ){
      //Pendapatan Jasa
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // echo "<hr>";
        // echo "<br>";
        // echo "<br> TOTAL PENDAPATAN JASA NON FAKULTAS " . (int)$arrData[$key]['Pendapatan Jasa'];
        // echo "<br> TOTAL PENDAPATAN LAIN-LAIN NON FAKULTAS " . (int)$arrData[$key]['Pendapatan Lain-Lain'];
        // echo "<br>" . $period;

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Pendapatan Jasa")
        ->where("cashflow_number", "1")
        ->where("status", "1")
        ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value"  => $arrData[$key]['Pendapatan Jasa'],
          "status"          => "1",
          "updated_by"      => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $arrData[$key]['Pendapatan Jasa'],
          "segment4"        => $key,
          "fakultas"        => NULL,
          "cashflow_name"   => "Pendapatan Jasa",
          "cashflow_number" => "1",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Pendapatan Lain-Lain
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Pendapatan Lain-Lain")
        ->where("cashflow_number", "2")
        ->where("status", "1")
        ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Pendapatan Lain-Lain'],
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $arrData[$key]['Pendapatan Lain-Lain'],
          "segment4"        => $key,
          "fakultas"        => NULL,
          "cashflow_name"   => "Pendapatan Lain-Lain",
          "cashflow_number" => "2",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }


      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // echo "<hr>";
    }
  }

  public function getProfitBebanNonFakultas($monthYEAR_req,$group_req) { 
    $db          = $this->db;
    $group       = $group_req; //01 = Yayasan, 02 = Unika
    $period      = $monthYEAR_req; // JAN-18
    $period_exp  = explode("-",$period); //Explode Period

    //Array Beban List
    $arrBebanPegawai      = array("5101002", "5101004", "5101005", "5101006", "5102003", "5102005", "5102009", "5102010", "5102013", "5102014", "5102015", "5102016", "5103001", "5103002", "5103004", "5103007", "5103009", "5103010", "5103011", "5103016", "5103017", "5103019", "5103020", "5103021", "5104002", "5104004", "5104005", "5104006", "5104007", "5104008", "5105001", "5105002", "5105004", "5105005", "5106002", "5106004", "5107001", "5406018", "5406019", "5406039", "5406040");
    $arraySegment4Pegawai = array("000", "411", "412", "311", "312", "313", "321", "322", "323", "331", "332", "333", "341", "342", "343", "352", "353", "354", "355", "361", "362", "381", "382", "391", "392", "393", "602", "192", "325", "356", "383", "601", "685", "471", "472", "324", "351", "473", "474", "191", "686");

    $arrBebanUtilitas        = array("5406022", "5406023", "5406024", "5406030");
    $arraySegment4Utilitas   = array("000", "322", "191", "311", "324", "361", "382", "471", "362");

    $arrBebanPemeliharaan      = array("5106001", "5106003", "5406008", "5501001", "5501002", "5501003", "5501004", "5501005", "5501006", "5501007", "5501008", "5501009", "5501020");
    $arraySegment4Pemeliharaan = array("393", "000", "321", "322", "381", "325", "686", "391", "392", "361");

    $arrBebanPenyusutan      = array("5601002", "5601003", "5601005", "5601006", "5601007", "5601008", "5601009", "5601011", "5601013");
    $arraySegment4Penyusutan = array("000", "321", "322", "313", "323", "362", "365", "361", "312", "342", "343", "383", "392", "393", "381", "391", "363", "364");

    $arrBebanJasaProfesional      = array("5201003", "5401002", "5401003");
    $arraySegment4JasaProfesional = array("000", "361", "321", "354", "391", "688");

    $arrBebanIklan      = array("5204003", "5402001", "5402002");
    $arraySegment4Iklan = array("382", "000", "361", "685", "311", "471");

    $arrBebanPerlengkapanKantorKonsumsi      = array("5201005", "5201007", "5201018", "5204008", "5406001", "5406004", "5406006", "5406010", "5406011", "5406012", "5406013", "5406015", "5406016", "5406017");
    $arraySegment4PerlengkapanKantorKonsumsi = array("000", "686", "688", "311", "312", "355", "361", "313", "321", "322", "324", "381", "382", "471", "685", "391", "392");

    $arrBebanLainLain  = array("5201020", "5201021", "5201022", "5201024", "5202001", "5202002", "5202003", "5203001", "5203002", "5203003", "5406020", "5406025", "5406026", "5406031", "5406032", "5406034", "5406035", "5406036", "5406037", "5406038", "5406041", "5406998", "5602001");
    $arraySegmentLainLain = array("000", "361", "688", "685", "686", "687", "322", "422", "321", "324", "381", "382", "383", "471", "363", "311");

    

    $query = "select ( bl.BEGIN_BALANCE_DR - bl.BEGIN_BALANCE_CR ) + ( bl.PERIOD_NET_DR - bl.PERIOD_NET_CR ) AS total, bl.PERIOD_NAME, cc.SEGMENT1 AS group_data,cd.DESCRIPTION,cc.SEGMENT4,cd.FLEX_VALUE
      FROM StagingData.dbo.GL_CODE_DESC AS cd
        RIGHT JOIN StagingData.dbo.GL_CODE_COMBINATIONS AS cc ON cc.SEGMENT5= cd.FLEX_VALUE
        RIGHT JOIN StagingData.dbo.AJ_GL_BALANCES_V AS bl ON bl.CODE_COMBINATION_ID= cc.CODE_COMBINATION_ID 
      WHERE
        cd.FLEX_VALUE BETWEEN '5101001' and '5604001' and cc.SEGMENT4 not in (".$this->getKodeFakultas.") 
        and cc.SEGMENT3 not in (".$this->getKodeSecre.") and cc.SEGMENT1='".$group."' and bl.ACTUAL_FLAG= 'A'";

    $query .= " and bl.PERIOD_NAME='".$period."'";
   
    $getList  = $db->query($query);
    $periodConvert = "20".$period_exp[1]."-".getMonthNumberCashflow($period_exp[0]);
    $arrData=[];
    

    foreach ($getList->getResult() as $list) {  
          if(empty($arrData[$list->SEGMENT4])){
              $Newdata =  array (
                                'Beban Pegawai'               => 0,
                                'Beban Penyusutan'            => 0,
                                'Beban Pemeliharaan'          => 0,
                                'Beban Iklan'                 => 0,
                                'Beban Utilitas'              => 0,
                                'Beban Jasa profesional'      => 0,
                                'Beban Lain'                  => 0,
                                'Beban Perlengkapan Kantor Dan Konsumsi' => 0
                            );
              $arrData[$list->SEGMENT4] = $Newdata;
          }


          if (in_array($list->FLEX_VALUE, $arrBebanPegawai)){
            if (in_array($list->SEGMENT4, $arraySegment4Pegawai)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Pegawai'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Pegawai : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Pegawai']=$total;
            }
          } 
          else if (in_array($list->FLEX_VALUE, $arrBebanUtilitas)){
            if (in_array($list->SEGMENT4, $arraySegment4Utilitas)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Utilitas'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Utilitas : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Utilitas']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPemeliharaan)){
            if (in_array($list->SEGMENT4, $arraySegment4Pemeliharaan)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Pemeliharaan'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Pemeliharaan : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Pemeliharaan']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPenyusutan)){
            if (in_array($list->SEGMENT4, $arraySegment4Penyusutan)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Penyusutan'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Penyusutan : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Penyusutan']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanJasaProfesional)){
            if (in_array($list->SEGMENT4, $arraySegment4JasaProfesional)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Jasa profesional'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Jasa Profesional : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Jasa profesional']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanIklan)){
            if (in_array($list->SEGMENT4, $arraySegment4Iklan)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Iklan'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Iklan : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Iklan']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanPerlengkapanKantorKonsumsi)){
            if (in_array($list->SEGMENT4, $arraySegment4PerlengkapanKantorKonsumsi)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Perlengkapan Kantor Dan Konsumsi'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Perlengkapan Kantor Dan Konsumsi : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Perlengkapan Kantor Dan Konsumsi']=$total;
            }
          }
          else if (in_array($list->FLEX_VALUE, $arrBebanLainLain)){
            if (in_array($list->SEGMENT4, $arraySegmentLainLain)) {
              $total =  $arrData[$list->SEGMENT4]['Beban Lain'] + (int)$list->total;
              // echo "&emsp;&emsp;" . $list->DESCRIPTION . " -> " . $list->total . " | Total Beban Lain : " . int_to_rp($total) . "<br>";
              $arrData[$list->SEGMENT4]['Beban Lain']=$total;
            }
          }
    }

    // echo "<pre>";
    // print_r($arrData);
    // echo "</pre>";

    foreach($arrData as $key => $value ){

      //Beban Pegawai
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

      // echo "<hr>";
      // echo "<br>";
      // echo "<br> TOTAL BEBAN PEGAWAI " . $arrData[$key]['Beban Pegawai'];
      // echo "<br> TOTAL BEBAN PENYUSUTAN " . $arrData[$key]['Beban Penyusutan'];
      // echo "<br> TOTAL BEBAN PEMELIHARAAN " . $arrData[$key]['Beban Pemeliharaan'];
      // echo "<br> TOTAL BEBAN IKLAN " . $arrData[$key]['Beban Iklan'];
      // echo "<br> TOTAL BEBAN UTILITAS " . $arrData[$key]['Beban Utilitas'];
      // echo "<br> TOTAL BEBAN JASA PROFESIONAL " . $arrData[$key]['Beban Jasa profesional'];
      // echo "<br> TOTAL BEBAN PERLENGKAPAN KANTOR DAN KONSUMSI " . $arrData[$key]['Beban Perlengkapan Kantor Dan Konsumsi'];
      // echo "<br> TOTAL BEBAN LAIN " . $arrData[$key]['Beban Lain'];
      // echo "<br>" . $period;

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Pegawai")
        ->where("cashflow_number", "3")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Pegawai'],
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Pegawai'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Pegawai",
            "cashflow_number" => "3",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Penyusutan
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Penyusutan")
        ->where("cashflow_number", "4")
        ->where("status", "1")
        ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Penyusutan'],
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $arrData[$key]['Beban Penyusutan'],
          "segment4"        => $key,
          "fakultas"        => NULL,
          "cashflow_name"   => "Beban Penyusutan",
          "cashflow_number" => "4",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Pemeliharaan
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Pemeliharaan")
        ->where("cashflow_number", "5")
        ->where("status", "1")
        ->first();

      if (!empty($check)) {
        $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Pemeliharaan'],
          "status"         => "1",
          "updated_by"     => "system"
        ]);
      } else {
        $generate = "csh_" . date('ymdhis') . "_" . uniqid();
        $data     = [
          "cashflow_id"     => $generate,
          "cashflow_period" => $periodConvert,
          "cashflow_group"  => (int)$group,
          "cashflow_value"  => $arrData[$key]['Beban Pemeliharaan'],
          "segment4"        => $key,
          "fakultas"        => NULL,
          "cashflow_name"   => "Beban Pemeliharaan",
          "cashflow_number" => "5",
          "status"          => "1",
          "created_by"      => "system",
          "updated_by"      => "system"
        ];
        $Insert->save($data);
      }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Iklan
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Iklan")
        ->where("cashflow_number", "6")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Iklan'],
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Iklan'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Iklan",
            "cashflow_number" => "6",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

        //Beban Utilitas
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Utilitas")
        ->where("cashflow_number", "7")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value"  => $arrData[$key]['Beban Utilitas'],
          "status"          => "1",
          "updated_by"      => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Utilitas'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Utilitas",
            "cashflow_number" => "7",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";


       ///Beban Jasa profesional
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Jasa profesional")
        ->where("cashflow_number", "8")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Jasa profesional'],
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Jasa profesional'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Jasa profesional",
            "cashflow_number" => "8",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }


      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";


      //Beban Perlengkapan Kantor Dan Konsumsi
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Perlengkapan Kantor Dan Konsumsi")
        ->where("cashflow_number", "9")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Perlengkapan Kantor Dan Konsumsi'],
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Perlengkapan Kantor Dan Konsumsi'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Perlengkapan Kantor Dan Konsumsi",
            "cashflow_number" => "9",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);
        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";

      //Beban Lain
        $Insert   = new KeuCashflow;
        $CashFlow = new KeuCashflow();

        // CHECK
        $check = $CashFlow->where("cashflow_period", $periodConvert)
        ->where("cashflow_group", (int)$group)
        ->where("segment4", $key)
        ->where("fakultas", NULL)
        ->where("cashflow_name", "Beban Lain - Lain")
        ->where("cashflow_number", "10")
        ->where("status", "1")
        ->first();

        if (!empty($check)) {
          $CashFlow->update($check["cashflow_id"], [
          "cashflow_value" => $arrData[$key]['Beban Lain'],
          "status"         => "1",
          "updated_by"     => "system"
          ]);
        } else {
          $generate = "csh_".date('ymdhis')."_".uniqid();
          $data     = [
            "cashflow_id"     => $generate,
            "cashflow_period" => $periodConvert,
            "cashflow_group"  => (int)$group,
            "cashflow_value"  => $arrData[$key]['Beban Lain'],
            "segment4"        => $key,
            "fakultas"        => NULL,
            "cashflow_name"   => "Beban Lain - Lain",
            "cashflow_number" => "10",
            "status"          => "1",
            "created_by"      => "system",
            "updated_by"      => "system"
          ];
          $Insert->save($data);

        }

      // echo "<pre>";
      // print_r($data);
      // echo "</pre>";
      // echo "<hr>";
    }
  }

}