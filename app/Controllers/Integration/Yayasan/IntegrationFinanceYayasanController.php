<?php

// RAIHAN

namespace App\Controllers\Integration\Yayasan;

use App\Controllers\BaseController;

use App\Models\KeuNeraca;
use App\Models\KeuLabaRugi;
use App\Models\KeuCapex;

use App\Models\Trigger\LaporanKeuangan\ViewPendapatan;
use App\Models\Trigger\LaporanKeuangan\ViewBeban;
use App\Models\Trigger\LaporanKeuangan\ViewBebanQuarter;

use App\Models\Yayasan\LaporanKeuangan\ViewAsetTetap;
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKas;
use App\Models\Yayasan\LaporanKeuangan\ViewKasSetaraKasQuarter;
use App\Models\Yayasan\LaporanKeuangan\ViewTrendPendInvestasi;
use App\Models\Yayasan\LaporanKeuangan\ViewInvestasi;
use App\Models\Yayasan\LaporanKeuangan\ViewInvestasiQuarter;

class IntegrationFinanceYayasanController extends BaseController
{

  public  $mydata;
  private $db;

  public function __construct()
  {
    $this->db = db_connect();
  }

  public function Index()
  {
    $thn   = date('y'); // 17, 18, 19, 20, 21, 22
    $group = "01"; // 01 = Yayasan, 02 = Unika, 03 = Rumah Sakit
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

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

    foreach ($DataList as $list) {
      if (substr($list, 0, -3) == "DEC") {
        $this->getPendapatanQuarter($list, $group);
        $this->getBebanQuarter($list, $group);
        $this->getKasSetaraKasQuarter($list, $group);
        $this->getInvestasiQuarter($list, $group);

      } elseif (substr($list, 0, -3) == "ADJ") {
        $this->getPendapatanTahun($list, $group);
        $this->getBebanTahun($list, $group);
        $this->getKasSetaraKasTahun($list, $group);
        $this->getInvestasiTahun($list, $group);

        $this->getAsetLancar($list,$group);
        $this->getAsetTidakLancar($list,$group);
        $this->getAsetLainLain($list,$group);
        $this->getAsetInvestasi($list,$group);
        $this->getLiabilitasJangkaPendek($list,$group);
        $this->getLiabilitasJangkaPanjang($list,$group);
        $this->getModal($list,$group);
        $this->getIntercompany($list,$group);
        $this->getTrendPendapatanInvestasi($list,$group);
        $this->getInvestasiCapex($list,$group);
        $this->getGedungCIP($list,$group);
        $this->getCapex($list,$group);
      } else {
        $this->getPendapatanQuarter($list, $group);
        $this->getBebanQuarter($list, $group);
        $this->getKasSetaraKasQuarter($list, $group);
        $this->getInvestasiQuarter($list, $group);
        $this->getPendapatanTahun($list, $group);
        $this->getBebanTahun($list, $group);
        $this->getKasSetaraKasTahun($list, $group);
        $this->getInvestasiTahun($list, $group);

        $this->getAsetLancar($list,$group);
        $this->getAsetTidakLancar($list,$group);
        $this->getAsetLainLain($list,$group);
        $this->getAsetInvestasi($list,$group);
        $this->getLiabilitasJangkaPendek($list,$group);
        $this->getLiabilitasJangkaPanjang($list,$group);
        $this->getModal($list,$group);
        $this->getIntercompany($list,$group);
        $this->getTrendPendapatanInvestasi($list, $group);
        $this->getInvestasiCapex($list, $group);
        $this->getGedungCIP($list,$group);
        $this->getCapex($list,$group);
      }   
      //echo "&emsp;" . $groupName . $list . "<br/>";
    }

  }

  public function getPendapatanQuarter($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $db = $this->db;
    $getList  = $db->query("
    select 
      SUM ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR ) AS Total,  glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,
	    glbalance.PERIOD_NAME AS Period
    FROM
      [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
      INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
      INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
    WHERE
      GLCODEDESC.FLEX_VALUE BETWEEN '4101001' 
      AND '4303027' 
      AND glcodecombinations.SEGMENT1='" . $group . "' 
      AND glbalance.PERIOD_NAME= '" . $period . "'
      AND glbalance.ACTUAL_FLAG = 'A' 
    GROUP BY
      glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME");

    $total = 0;

    foreach ($getList->getResult() as $list) {
      // echo "Total Pendapatan (Penambahan) : " . int_to_rp($total) . "<br>";
      $total += $list->Total;
      // echo $list->Descr . " -> " . $list->Total. " | Total Pendapatan (Penambahan) setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    $grand_total = $total;
    // echo "<br>";
    // echo int_to_rp($grand_total);
    // echo "<br> TOTAL PENDAPATAN " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Pendapatan",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_quarter_value" => (int)$grand_total,
        "status"                  => "1",
        "updated_by"              => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"            => $generate,
        "laba_rugi_period"        => $periodConvert,
        "laba_rugi_group"         => (int)$group,
        "laba_rugi_quarter_value" => (int)$grand_total,
        "laba_rugi_name"          => "Pendapatan",
        "laba_rugi_type"          => "1",
        "group_type"              => "1",
        "status"                  => "1",
        "created_by"              => "system",
        "updated_by"              => "system"
      ];

      $LabaRugi->save($data);
    }

  }

  public function getPendapatanTahun($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $db = $this->db;
    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 as COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 BETWEEN '4101001' and '4303027' 
      and glcodecombinations.SEGMENT1='" . $group . "' 
      and glbalance.PERIOD_NAME= '" . $period . "' 
      and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    $excludeArray = array();
    foreach ($getList->getResult() as $list) {

      if (!in_array($list->COA, $excludeArray)) {
        //echo "Total Pendapatan : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        //echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      }
      
    }

    $grand_total = $total * -1;
    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL PENDAPATAN " . $groupName . $period;

    $LabaRugi      = new KeuLabaRugi;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Pendapatan",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$grand_total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"       => $generate,
        "laba_rugi_period"   => $periodConvert,
        "laba_rugi_group"    => (int)$group,
        "laba_rugi_value"    => (int)$grand_total,
        "laba_rugi_name"     => "Pendapatan",
        "laba_rugi_type"     => "1",
        "group_type"         => "1",
        "status"             => "1",
        "created_by"         => "system",
        "updated_by"         => "system"
      ];

      $LabaRugi->save($data);
    }

  }

  public function getBebanQuarter($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $db = $this->db;
    $getList  = $db->query("
    select 
      SUM ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR ) AS Total, glcodecombinations.SEGMENT5 as COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 BETWEEN '5101001' and '5604001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    $excludeCOA = array("5106003", "5406029", "5406032", "5406034", "5406041", "5601013", "5602001");
    foreach ($getList->getResult() as $list) {
      if (!in_array($list->COA, $excludeCOA)) {
        // echo "Total Beban : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      } else {
        if (substr($list->Period, -2) != "20") {
          // echo "Total Beban : " . int_to_rp($total) . "<br>";
          $total += $list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total Beban setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BEBAN " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Beban",
      "laba_rugi_type"   => "1",
      "group_type"       => "2",
      "status"           => "1",
    ])->where("laba_rugi_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_quarter_value" => (int)$total,
        "status"                  => "1",
        "updated_by"              => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"            => $generate,
        "laba_rugi_period"        => $periodConvert,
        "laba_rugi_group"         => (int)$group,
        "laba_rugi_quarter_value" => (int)$total,
        "laba_rugi_name"          => "Beban",
        "laba_rugi_type"          => "1",
        "group_type"              => "2",
        "status"                  => "1",
        "created_by"              => "system",
        "updated_by"              => "system"
      ];

      $LabaRugi->save($data);
    }

  }

  public function getBebanTahun($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $db = $this->db;
    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 as COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 BETWEEN '5101001' and '5604001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    $excludeCOA = array("5106003", "5406029", "5406032", "5406034", "5406041", "5601013", "5602001");
    foreach ($getList->getResult() as $list) {
      if (!in_array($list->COA, $excludeCOA)) {
        //echo "Total Beban : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        //echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      } else {
        if (substr($list->Period, -2) != "20") {
          //echo "Total Beban : " . int_to_rp($total) . "<br>";
          $total += $list->Total;
          //echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total Beban setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BEBAN " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Beban",
      "laba_rugi_type"   => "1",
      "group_type"       => "2",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Beban",
        "laba_rugi_type"   => "1",
        "group_type"       => "2",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }
    
  }

  public function getAsetLancar($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 AS COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE
      (glcodecombinations.SEGMENT5 BETWEEN '1101001' and '1201001' and glcodecombinations.SEGMENT5 NOT IN ('1106998') and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '1107001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME");

    $total = 0;

    $excludeArray = array("1109001");

    foreach ($getList->getResult() as $list) {
      if ($list->COA == "1201001") {
        if (substr($period, -2) >= 21) {
          // echo "Total Aset Lancar : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }
      } else {
        if (!in_array($list->COA, $excludeArray)) {
          // echo "Total Aset Lancar : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        } else {
          // echo "Total Aset Lancar : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Aset Lancar " . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Aset Lancar",
      "neraca_type"   => "2",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Aset Lancar",
        "neraca_type"     => "2",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }

  }

  public function getAsetTidakLancar($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $getList  = $db->query("
    select
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 as COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      glcodecombinations.SEGMENT5 BETWEEN '1201002' and '1401000' 
      and glcodecombinations.SEGMENT1='" . $group . "' 
      and glbalance.PERIOD_NAME= '" . $period . "' 
      and glbalance.ACTUAL_FLAG= 'A'
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    foreach ($getList->getResult() as $list) {

      if ($list->COA == "1201002" || $list->COA == "1312002") {

        if ($list->COA == "1201002") {
          if (substr($period, -2) >= 21) {
            // echo "Total Aset Tidak Lancar : " . int_to_rp($total) . "<br>";
            $total += (int)$list->Total;
            // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
          }
        } else {
          $excludeYear = array("18", "19", "20", "21");
          if (!in_array(substr($period, -2), $excludeYear)) {
            // echo "Total Aset Tidak Lancar : " . int_to_rp($total) . "<br>";
            $total += (int)$list->Total;
            // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
          }
        }

      } elseif ($list->COA == "1313899") {

        $excludeYear = array("20");
        if (!in_array(substr($period, -2), $excludeYear)) {
          // echo "Total Aset Tidak Lancar : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }

      } elseif ($list->COA == "1304002") {

        $excludeYear = array("18");
        if (!in_array(substr($period, -2), $excludeYear)) {
          // echo "Total Aset Tidak Lancar : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }

      } else {
        // echo "Total Aset Tidak Lancar : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      }

    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Aset Tidak Lancar " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Aset Tidak Lancar",
      "neraca_type"   => "2",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Aset Tidak Lancar",
        "neraca_type"     => "2",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];
      $Neraca->save($data);
    }

  }

  public function getAsetLainLain($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "Rumah Sakit ";
    } else {
      $groupName = "";
    }

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 AS COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 = '1401001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '1401002' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1401003' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1401004' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;

    $excludeArray = array("1401003");

    foreach ($getList->getResult() as $list) {
      if (!in_array($list->COA, $excludeArray)) {
        // echo "Total Aset Lain - Lain : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      } else {
        if (substr($period, -2) < 21) {
          // echo "Total Aset Lain - Lain : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Aset Lain - Lain " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Aset Lain - Lain",
      "neraca_type"   => "2",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Aset Lain - Lain",
        "neraca_type"     => "2",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }
  }

  public function getAsetInvestasi($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "Rumah Sakit ";
    } else {
      $groupName = "";
    }

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 AS COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  RIGHT JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 = '1201001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1201002' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1202001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1202002' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period ."' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '1202003' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;

    $excludeArray = array("1201002");

    foreach ($getList->getResult() as $list) {
      if (!in_array($list->COA, $excludeArray)) {
        // echo "Total Investasi : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      } else {
        if (substr($period, -2) != "18") {
          // echo "Total Investasi : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Investasi " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Aset Investasi",
      "neraca_type"   => "2",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Aset Investasi",
        "neraca_type"     => "2",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }
  }

  public function getLiabilitasJangkaPendek($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT1 AS GroupData, glcodecombinations.SEGMENT5 AS COA, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      ( glcodecombinations.SEGMENT5 BETWEEN '2101001' and '2108099' 
      or glcodecombinations.SEGMENT5 = '2109001' ) 
      and glcodecombinations.SEGMENT5 NOT IN ('2103998') 
      and glcodecombinations.SEGMENT1='" . $group . "' 
      and glbalance.PERIOD_NAME= '" . $period . "' 
      and glbalance.ACTUAL_FLAG= 'A'
    GROUP BY
      glcodecombinations.SEGMENT1, glcodecombinations.SEGMENT5, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    foreach ($getList->getResult() as $list) {
      // echo "Total Liabilitas Jangka Pendek : " . int_to_rp($total) . "<br>";
      $total += (int)$list->Total;
      // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Liabilitas Jangka Pendek " . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Liabilitas Jangka Pendek",
      "neraca_type"   => "2",
      "group_type"    => "2",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total*-1,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total*-1,
        "neraca_name"     => "Liabilitas Jangka Pendek",
        "neraca_type"     => "2",
        "group_type"      => "2",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }

  }

  public function getLiabilitasJangkaPanjang($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      glcodecombinations.SEGMENT5 BETWEEN '2201001' and '2204001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A'
    GROUP BY
      glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    foreach ($getList->getResult() as $list) {
      // echo "Total Liabilitas Jangka Panjang : " . int_to_rp($total) . "<br>";
      $total += (int)$list->Total;
      // echo "&emsp;&emsp;" . $list->Descr . " -> " . int_to_rp($list->Total) . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Liabilitas Jangka Panjang " . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Liabilitas Jangka Panjang",
      "neraca_type"   => "2",
      "group_type"    => "2",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total*-1,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total*-1,
        "neraca_name"     => "Liabilitas Jangka Panjang",
        "neraca_type"     => "2",
        "group_type"      => "2",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];
    
      $Neraca->save($data);
    }
    
  }

  public function getModal($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) { $groupName = "Yayasan "; } elseif ($group == 02) { $groupName = "UNIKA "; } elseif ($group == 03) { $groupName = "Rumah Sakit "; } else { $groupName = ""; }

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 AS COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 = '3302001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '3301001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '3303001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '3304001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '3304002' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
      or (glcodecombinations.SEGMENT5 = '3305001' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;

    $excludeCOA = array("3301001", "3302001");

    foreach ($getList->getResult() as $list) {
      if (!in_array($list->COA, $excludeCOA)) {
        // echo "Total Modal : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
      } else {
        if (substr($period, -2) >= 21) {
          // echo "Total Modal : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
        // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
        }
      }
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Modal " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Aset Neto",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total*-1,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total*-1,
        "neraca_name"     => "Aset Neto",
        "neraca_type"     => "1",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];
  
      $Neraca->save($data);
    }

  }

  public function getIntercompany($monthYEAR_req, $group_req)
  {
    $db       = $this->db;
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req;
    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "Rumah Sakit ";
    } else {
      $groupName = "";
    }

    $getList  = $db->query("
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE 
      (glcodecombinations.SEGMENT5 = '1106998' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A') 
      or (glcodecombinations.SEGMENT5 = '2103998' and glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A')
    GROUP BY
      glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION,	glbalance.PERIOD_NAME");

    $total = 0;
    foreach ($getList->getResult() as $list) {
      // echo "Total Intercompany : " . int_to_rp($total) . "<br>";
      $total += (int)$list->Total;
      // echo "&emsp;&emsp;" . $list->Descr . " -> " . $list->Total . " | Total setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>" . "<br>";
    // echo int_to_rp($total);
    // echo "<br>Total Intercompany " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Intercompany",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Intercompany",
        "neraca_type"     => "1",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }
  }

  public function getKasSetaraKasQuarter($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewKasSetaraKas = new ViewKasSetaraKasQuarter();

    // BANK
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Bank")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Bank : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Bank setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BANK " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Bank",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Bank",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }

    // DEPOSITO BERJANGKA
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Deposito Berjangka")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Deposito Berjangka : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Deposito Berjangka setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL DEPOSITO BERJANGKA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Deposito Berjangka",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Deposito Berjangka",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }

    // KAS
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Kas")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Kas : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Kas setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL KAS " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Kas",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Kas",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }

  }
  
  public function getKasSetaraKasTahun($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewKasSetaraKas = new ViewKasSetaraKas();

    // BANK
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Bank")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      // echo "Total Bank : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      // echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Bank setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BANK " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Bank",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Bank",
        "neraca_type"     => "1",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }

    // DEPOSITO BERJANGKA
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Deposito Berjangka")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Deposito Berjangka : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Deposito Berjangka setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL DEPOSITO BERJANGKA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Deposito Berjangka",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Deposito Berjangka",
        "neraca_type"     => "1",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }

    // KAS
    $getList  = $viewKasSetaraKas->select("KasName, SUM(Total) AS Total, Descr, Period")
    ->where("KasName", "Kas")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("KasName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      // echo "Total Kas : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      // echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Kas setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL KAS " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Kas",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"       => $generate,
        "neraca_period"   => $periodConvert,
        "neraca_group"    => (int)$group,
        "neraca_value"    => (int)$total,
        "neraca_name"     => "Kas",
        "neraca_type"     => "1",
        "group_type"      => "1",
        "status"          => "1",
        "created_by"      => "system",
        "updated_by"      => "system"
      ];

      $Neraca->save($data);
    }

  }

  public function getTrendPendapatanInvestasi($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewTrendPendInves = new ViewTrendPendInvestasi();

    // BUNGA DEPOSITO BERJANGKA
    $getList  = $viewTrendPendInves->select("LabaRugiName, SUM(Total) AS Total, Descr, Period")
    ->where("LabaRugiName", "Bunga Deposito Berjangka")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("LabaRugiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Bunga Deposito Berjangka : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Bunga Deposito Berjangka setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BUNGA DEPOSITO BERJANGKA " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Bunga Deposito Berjangka",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Bunga Deposito Berjangka",
        "laba_rugi_type"   => "1",
        "group_type"       => "1",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }

    // BUNGA OBLIGASI
    $getList  = $viewTrendPendInves->select("LabaRugiName, SUM(Total) AS Total, Descr, Period")
    ->where("LabaRugiName", "Bunga Obligasi")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("LabaRugiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Bunga Obligasi : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Bunga Obligasi setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL BUNGA OBLIGASI " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Bunga Obligasi",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Bunga Obligasi",
        "laba_rugi_type"   => "1",
        "group_type"       => "1",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }

    // LABA REKSADANA
    $getList  = $viewTrendPendInves->select("LabaRugiName, SUM(Total) AS Total, Descr, Period")
    ->where("LabaRugiName", "Laba Reksadana")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("LabaRugiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Laba Reksadana : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Laba Reksadana setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL LABA REKSADANA " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Laba Reksadana",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Laba Reksadana",
        "laba_rugi_type"   => "1",
        "group_type"       => "1",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }

    // CAPITAL GAIN
    $getList  = $viewTrendPendInves->select("LabaRugiName, SUM(Total) AS Total, Descr, Period")
    ->where("LabaRugiName", "Capital Gain")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("LabaRugiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Capital Gain : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Capital Gain setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL CAPITAL GAIN " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Capital Gain",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Capital Gain",
        "laba_rugi_type"   => "1",
        "group_type"       => "1",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }

    // LABA RUGI ANAK PERUSAHAAN
    $getList  = $viewTrendPendInves->select("LabaRugiName, SUM(Total) AS Total, Descr, Period")
    ->where("LabaRugiName", "Laba (rugi) anak perusahaan")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("LabaRugiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Laba (rugi) anak perusahaan : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Laba (rugi) anak perusahaan setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL LABA RUGI ANAK PERUSAHAAN " . $groupName . $period;
    // die();

    $LabaRugi      = new KeuLabaRugi();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $LabaRugi->where([
      "laba_rugi_period" => $periodConvert,
      "laba_rugi_group"  => (int)$group,
      "laba_rugi_name"   => "Laba (rugi) anak perusahaan",
      "laba_rugi_type"   => "1",
      "group_type"       => "1",
      "status"           => "1",
    ])->where("laba_rugi_quarter_value", NULL)->first();

    if ($check) {
      $LabaRugi->update($check["laba_rugi_id"], [
        "laba_rugi_value" => (int)$total,
        "status"          => "1",
        "updated_by"      => "system"
      ]);
    } else {
      $generate = "lbg_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "laba_rugi_id"     => $generate,
        "laba_rugi_period" => $periodConvert,
        "laba_rugi_group"  => (int)$group,
        "laba_rugi_value"  => (int)$total,
        "laba_rugi_name"   => "Laba (rugi) anak perusahaan",
        "laba_rugi_type"   => "1",
        "group_type"       => "1",
        "status"           => "1",
        "created_by"       => "system",
        "updated_by"       => "system"
      ];

      $LabaRugi->save($data);
    }
  }

  public function getInvestasiQuarter($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewInvestasi = new ViewInvestasiQuarter();

    // DEPOSITO BERJANGKA
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Deposito Berjangka")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Deposito Berjangka : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Deposito Berjangka setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL DEPOSITO BERJANGKA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi Deposito Berjangka",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Investasi Deposito Berjangka",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }

    // SURAT BERHARGA
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Surat Berharga")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Surat Berharga : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Surat Berharga setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL SURAT BERHARGA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi Surat Berharga",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Investasi Surat Berharga",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }

    // PT ANAK PERUSAHAAN
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Pt - Anak Perusahaan")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total PT - Anak Perusahaan : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total PT - Anak Perusahaan setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL PT ANAK PERUSAHAAN " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi PT - Anak Perusahaan",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_quarter_value" => (int)$total,
        "status"               => "1",
        "updated_by"           => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"            => $generate,
        "neraca_period"        => $periodConvert,
        "neraca_group"         => (int)$group,
        "neraca_quarter_value" => (int)$total,
        "neraca_name"          => "Investasi PT - Anak Perusahaan",
        "neraca_type"          => "1",
        "group_type"           => "1",
        "status"               => "1",
        "created_by"           => "system",
        "updated_by"           => "system"
      ];

      $Neraca->save($data);
    }
  }

  public function getInvestasiTahun($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewInvestasi = new ViewInvestasi();

    // DEPOSITO BERJANGKA
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Deposito Berjangka")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Deposito Berjangka : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Deposito Berjangka setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL DEPOSITO BERJANGKA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi Deposito Berjangka",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"     => $generate,
        "neraca_period" => $periodConvert,
        "neraca_group"  => (int)$group,
        "neraca_value"  => (int)$total,
        "neraca_name"   => "Investasi Deposito Berjangka",
        "neraca_type"   => "1",
        "group_type"    => "1",
        "status"        => "1",
        "created_by"    => "system",
        "updated_by"    => "system"
      ];

      $Neraca->save($data);
    }

    // SURAT BERHARGA
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Surat Berharga")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Surat Berharga : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Surat Berharga setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL SURAT BERHARGA " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi Surat Berharga",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"     => $generate,
        "neraca_period" => $periodConvert,
        "neraca_group"  => (int)$group,
        "neraca_value"  => (int)$total,
        "neraca_name"   => "Investasi Surat Berharga",
        "neraca_type"   => "1",
        "group_type"    => "1",
        "status"        => "1",
        "created_by"    => "system",
        "updated_by"    => "system"
      ];

      $Neraca->save($data);
    }

    // PT ANAK PERUSAHAAN
    $getList  = $viewInvestasi->select("InvestasiName, SUM(Total) AS Total, Descr, Period")
    ->where("InvestasiName", "Pt - Anak Perusahaan")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("InvestasiName, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total PT - Anak Perusahaan : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total PT - Anak Perusahaan setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL PT ANAK PERUSAHAAN " . $groupName . $period;
    // die();

    $Neraca        = new KeuNeraca;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Neraca->where([
      "neraca_period" => $periodConvert,
      "neraca_group"  => (int)$group,
      "neraca_name"   => "Investasi PT - Anak Perusahaan",
      "neraca_type"   => "1",
      "group_type"    => "1",
      "status"        => "1",
    ])->where("neraca_quarter_value", NULL)->first();

    if ($check) {
      $Neraca->update($check["neraca_id"], [
        "neraca_value" => (int)$total,
        "status"       => "1",
        "updated_by"   => "system"
      ]);
    } else {
      $generate = "nrc_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "neraca_id"     => $generate,
        "neraca_period" => $periodConvert,
        "neraca_group"  => (int)$group,
        "neraca_value"  => (int)$total,
        "neraca_name"   => "Investasi PT - Anak Perusahaan",
        "neraca_type"   => "1",
        "group_type"    => "1",
        "status"        => "1",
        "created_by"    => "system",
        "updated_by"    => "system"
      ];

      $Neraca->save($data);
    }
  }

  public function getInvestasiCapex($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18

    if ($group == 01) {
      $groupName = "Yayasan ";
    } elseif ($group == 02) {
      $groupName = "UNIKA ";
    } elseif ($group == 03) {
      $groupName = "RUMAH SAKIT ";
    } else {
      $groupName = "";
    }

    $viewInvestasiCapex = new ViewAsetTetap();

    // GEDUNG & CIP
    $getList  = $viewInvestasiCapex->select("AsetTetap, SUM(Total) AS Total, Descr, Period")
    ->where("AsetTetap", "Gedung & CIP")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("AsetTetap, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Gedung & CIP : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Gedung & CIP setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL GEDUNG & CIP " . $groupName . $period;
    // die();

    $Capex         = new KeuCapex();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Investasi Gedung & CIP",
      "capex_number" => "1",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Investasi Gedung & CIP",
        "capex_number" => "1",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }

    // PERALATAN DAN PRASARANA
    $getList  = $viewInvestasiCapex->select("AsetTetap, SUM(Total) AS Total, Descr, Period")
    ->where("AsetTetap", "Peralatan dan Prasarana")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("AsetTetap, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Peralatan dan Prasarana : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Peralatan dan Prasarana setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL PERALATAN DAN PRASARANA " . $groupName . $period;
    // die();

    $Capex         = new KeuCapex();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Investasi Peralatan dan Prasarana",
      "capex_number" => "1",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Investasi Peralatan dan Prasarana",
        "capex_number" => "1",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }

    // TANAH
    $getList  = $viewInvestasiCapex->select("AsetTetap, SUM(Total) AS Total, Descr, Period")
    ->where("AsetTetap", "Tanah")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("AsetTetap, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Tanah : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Tanah setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL TANAH " . $groupName . $period;
    // die();

    $Capex         = new KeuCapex();
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Investasi Tanah",
      "capex_number" => "1",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Investasi Tanah",
        "capex_number" => "1",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }
  }

  public function getGedungCIP($monthYEAR_req, $group_req)
  {
    $group    = $group_req; //01 = Yayasan, 02 = Unika
    $period   = $monthYEAR_req; // JAN-18
    $viewAsetTetap = new ViewAsetTetap();
    $getList  = $viewAsetTetap->select("AsetTetap, SUM(Total) AS Total, Descr, Period")
    ->where("AsetTetap", "Gedung & CIP")
    ->where("GroupData", $group)
    ->where("Period", $period)
    ->groupBy("AsetTetap, Descr, Period")
    ->findAll();

    $total = 0;

    foreach ($getList as $list) {
      //echo "Total Gedung & CIP : " . int_to_rp($total) . "<br>";
      $total += $list["Total"];
      //echo "&emsp;&emsp;" . $list["Descr"] . " -> " . $list["Total"]. " | Total Gedung & CIP setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL GEDUNG & CIP " . $period;
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Gedung & CIP",
      "capex_number" => "1",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Gedung & CIP",
        "capex_number" => "1",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }

  }

  public function getCapex($monthYEAR_req, $group_req)
  {
    $db     = $this->db;
    $group  = $group_req;      //01 = Yayasan, 02 = Unika
    $period = $monthYEAR_req;  // JAN-18
    if ($group_req == 01) { $groupName = "Yayasan "; } elseif ($group_req == 02) { $groupName = "UNIKA "; } else { $groupName = ""; }

    $query = "
    select 
      SUM (( glbalance.BEGIN_BALANCE_DR - glbalance.BEGIN_BALANCE_CR ) + ( glbalance.PERIOD_NET_DR - glbalance.PERIOD_NET_CR )) AS Total, glcodecombinations.SEGMENT5 AS COA, glcodecombinations.SEGMENT1 AS GroupData, GLCODEDESC.DESCRIPTION AS Descr,	glbalance.PERIOD_NAME AS Period 
    FROM
		  [StagingData].[dbo].[GL_CODE_DESC] AS GLCODEDESC
		  INNER JOIN [StagingData].[dbo].[GL_CODE_COMBINATIONS] AS glcodecombinations ON GLCODEDESC.FLEX_VALUE = glcodecombinations.SEGMENT5
		  INNER JOIN [StagingData].[dbo].[AJ_GL_BALANCES_V] AS glbalance ON glcodecombinations.CODE_COMBINATION_ID = glbalance.CODE_COMBINATION_ID 
	  WHERE
      glcodecombinations.SEGMENT1='" . $group . "' and glbalance.PERIOD_NAME= '" . $period . "' and glbalance.ACTUAL_FLAG= 'A'";

    //Tanah
    $query_tanah = $query . " and glcodecombinations.SEGMENT5 IN ( '1301001', '1301002', '1301003', '1301004', '1301005' ) GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME";
    $getListTanah  = $db->query($query_tanah);
    $total = 0;
    foreach ($getListTanah->getResult() as $list) {
      // echo "Total Tanah : " . int_to_rp($total) . "<br>";
      $total += $list->Total;
      // echo $list->Descr . " -> " . $list->Total
      // . " | Total Tanah setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL TANAH " . $groupName . $period . "<br><br><br>";
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Tanah",
      "capex_number" => "2",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Tanah",
        "capex_number" => "2",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }


    //Gedung
    $query_gedung = $query . " and ((glcodecombinations.SEGMENT5 BETWEEN '1302001' and '1302999') OR (glcodecombinations.SEGMENT5 = '1313001')) GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME";
    $getListGedung  = $db->query($query_gedung);
    $total = 0;
    foreach ($getListGedung->getResult() as $list) {
      // echo "Total Gedung : " . int_to_rp($total) . "<br>";
      $total += $list->Total;
      // echo $list->Descr . " -> " . $list->Total
      //   . " | Total Gedung setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL GEDUNG " . $groupName . $period . "<br><br><br>";
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Bangunan/Gedung",
      "capex_number" => "1",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Bangunan/Gedung",
        "capex_number" => "1",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];

      $Capex->save($data);
    }


    //CIP
    $query_cip = $query . "
    AND glcodecombinations.SEGMENT5 IN ( '1313001', '1313002', '1313007', '1313008', '1313009', '1313011', '1313899', '1313990', '1313993', '1313995', '1313996', '1313997', '1313999', '1314001' )
    GROUP BY glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME";
    $getListCIP  = $db->query($query_cip);
    $total = 0;
    $specialCOA = array("1313001", "1313899");
    foreach ($getListCIP->getResult() as $list) {
      if (substr($period, -2) >= 21) {
        if (in_array($list->COA, $specialCOA)) {
          // echo "Total CIP : " . int_to_rp($total) . "<br>";
          $total += (int)$list->Total;
          // echo $list->Descr . " -> " . $list->Total . " | Total CIP setelah foreach : " . int_to_rp($total) . "<br>";
        }
      } else {
        // echo "Total CIP : " . int_to_rp($total) . "<br>";
        $total += (int)$list->Total;
        // echo $list->Descr . " -> " . $list->Total. " | Total CIP setelah foreach : " . int_to_rp($total) . "<br>";
      }
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL ASET DALAM PENYELESAIAN (CIP) " . $groupName . $period . "<br><br><br>";
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Aset dalam penyelesaian (CIP)",
      "capex_number" => "7",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Aset dalam penyelesaian (CIP)",
        "capex_number" => "7",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];
  
      $Capex->save($data);
    }


    //Peralatan dan Prasarana
    $query_PeralatanPrasarana = $query . "
    AND glcodecombinations.SEGMENT5 IN ( '1303001', '1303002', '1303999', '1305001', '1305002', '1305999', '1306001', '1306002', '1306999', '1307001', '1307002', '1307999', '1308001', '1308002', '1308999', '1309001', '1309002', '1309999', '1310001', '1310002', '1310999', '1311001', '1311002',	'1311999', '1312001','1312002', '1312999', '1315001', '1315002', '1313002', '1313007', '1313008', '1313009', '1313011', '1313990', '1313993', '1313995', '1313997', '1313999', '1314001' )
    GROUP BY glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME";
    $getListPeralatanPrasarana = $db->query($query_PeralatanPrasarana);
    $total = 0;
    foreach ($getListPeralatanPrasarana->getResult() as $list) {
      // echo "Total Peralatan dan Prasarana : " . int_to_rp($total) . "<br>";
      $total += $list->Total;
      // echo $list->Descr . " -> " . $list->Total
      // . " | Total Peralatan dan Prasarana setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL PERALATAN DAN PRASARANA " . $groupName . $period . "<br><br><br>";
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Peralatan dan Prasarana",
      "capex_number" => "5",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Peralatan dan Prasarana",
        "capex_number" => "5",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];
  
      $Capex->save($data);
    }


    //Mesin
    $query_Mesin = $query . " and glcodecombinations.SEGMENT5 in( '1304001', '1304002', '1304999' ) GROUP BY
      glcodecombinations.SEGMENT5, glcodecombinations.SEGMENT1, GLCODEDESC.DESCRIPTION, glbalance.PERIOD_NAME";
    $getListMesin = $db->query($query_Mesin);
    $total = 0;
    foreach ($getListMesin->getResult() as $list) {
      // echo "Total Mesin : " . int_to_rp($total) . "<br>";
      $total += (int)$list->Total;
      // echo $list->Descr . " -> " . $list->Total
      // . " | Total MESIN setelah foreach : " . int_to_rp($total) . "<br>";
    }

    // echo "<hr>";
    // echo "<br>";
    // echo int_to_rp($total);
    // echo "<br> TOTAL MESIN " . $groupName . $period . "<br>";
    // die();

    $Capex         = new KeuCapex;
    $getmonth      = explode('-', $period);
    $periodConvert = "20" . $getmonth[1] . "-" . getMonthNumber($getmonth[0]);

    $check = $Capex->where([
      "capex_period" => $periodConvert,
      "capex_group"  => (int)$group,
      "capex_name"   => "Mesin",
      "capex_number" => "3",
      "status"       => "1",
    ])->first();

    if ($check) {
      $Capex->update($check["capex_id"], [
        "capex_value" => (int)$total,
        "status"      => "1",
        "updated_by"  => "system"
      ]);
    } else {
      $generate = "cpx_" . date('ymdhis') . "_" . uniqid();
      $data     = [
        "capex_id"     => $generate,
        "capex_period" => $periodConvert,
        "capex_group"  => (int)$group,
        "capex_value"  => (int)$total,
        "capex_name"   => "Mesin",
        "capex_number" => "3",
        "status"       => "1",
        "created_by"   => "system",
        "updated_by"   => "system"
      ];
  
      $Capex->save($data);
    }

  }
  
}
