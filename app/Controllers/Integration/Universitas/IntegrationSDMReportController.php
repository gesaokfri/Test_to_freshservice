<?php

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;

// TRIGGER SDM REPORT
use App\Models\Trigger\SDMReport\SDM_Report;
use App\Models\Trigger\SDMReport\TRSDMReport;
// TRIGGER SDM REPORT

class IntegrationSDMReportController extends BaseController
{
  private $db;

  public function __construct()
  {
    $this->db = db_connect();
  }

  public function Index()
  {
    $this->TriggerSDMReport();
  }

  public function TriggerSDMReport()
  {
    $SDM_Report  = new SDM_Report();
    $TRSDMReport = new TRSDMReport();

    $getSDMReport = $SDM_Report
    ->where("YEAR (LastTime)", date("Y"))
    ->findAll();

    foreach ($getSDMReport as $value) {
      $db  = $this->db;

      $firstCheck = $db->query(
        "SELECT * FROM tr_sdm_report 
        WHERE \"Unit\" = '" . $value["Unit"] . "'
        AND \"Detail\" = '" . $value["Detail"] . "'
        AND \"Kontrak\" = '" . $value["Kontrak"] . "'
        AND \"Tetap\" = '" . $value["Tetap"] . "'
        AND \"Jumlah\" = '" . $value["Jumlah"] . "'
        AND \"PHK_Mengundurkan_Diri\" = '" . $value["PHK_Mengundurkan_Diri"] . "'
        AND \"PHK_Pensiun\" = '" . $value["PHK_Pensiun"] . "'
        AND \"PHK_Meninggal_Dunia\" = '" . $value["PHK_Meninggal_Dunia"] . "'
        AND \"PHK_Diberhentikan\" = '" . $value["PHK_Diberhentikan"] . "'
        AND \"PHK_Akhir_Kontrak\" = '" . $value["PHK_Akhir_Kontrak"] . "'
        AND \"PHK_Lain_Lain\" = '" . $value["PHK_Lain_Lain"] . "'
        AND \"Asisten_Mahasiswa\" = '" . $value["Asisten_Mahasiswa"] . "'
        AND \"Jumlah_Honorer\" = '" . $value["Jumlah_Honorer"] . "'
        AND \"Mhs_Aktif\" = '" . $value["Mhs_Aktif"] . "'
        AND \"Agama_Hindu\" = '" . $value["Agama_Hindu"] . "'
        AND \"Agama_Budha\" = '" . $value["Agama_Budha"] . "'
        AND \"Agama_Kristen\" = '" . $value["Agama_Kristen"] . "'
        AND \"Agama_Katolik\" = '" . $value["Agama_Katolik"] . "'
        AND \"Agama_Islam\" = '" . $value["Agama_Islam"] . "'
        AND \"Jumlah_SKS\" = '" . $value["Jumlah_SKS"] . "'
        AND \"Kelamin_Pria\" = '" . $value["Kelamin_Pria"] . "'
        AND \"Kelamin_Wanita\" = '" . $value["Kelamin_Wanita"] . "'
        AND \"JA_TP\" = '" . $value["JA_TP"] . "'
        AND \"JA_AA\" = '" . $value["JA_AA"] . "'
        AND \"JA_L\" = '" . $value["JA_L"] . "'
        AND \"JA_LK\" = '" . $value["JA_LK"] . "'
        AND \"JA_GB\" = '" . $value["JA_GB"] . "'
        AND \"JA_PRO\" = '" . $value["JA_PRO"] . "'
        AND \"JA_TP_Honorer\" = '" . $value["JA_TP_Honorer"] . "'
        AND \"JA_AA_Honorer\" = '" . $value["JA_AA_Honorer"] . "'
        AND \"JA_L_Honorer\" = '" . $value["JA_L_Honorer"] . "'
        AND \"JA_LK_Honorer\" = '" . $value["JA_LK_Honorer"] . "'
        AND \"JA_GB_Honorer\" = '" . $value["JA_GB_Honorer"] . "'
        AND \"JA_PRO_Honorer\" = '" . $value["JA_PRO_Honorer"] . "'
        AND \"Pend_S1\" = '" . $value["Pend_S1"] . "'
        AND \"Pend_S2\" = '" . $value["Pend_S2"] . "'
        AND \"Pend_S3\" = '" . $value["Pend_S3"] . "'
        AND \"Pend_SP1\" = '" . $value["Pend_SP1"] . "'
        AND \"Pend_SP2\" = '" . $value["Pend_SP2"] . "'
        AND \"Pend_S1_Honorer\" = '" . $value["Pend_S1_Honorer"] . "'
        AND \"Pend_S2_Honorer\" = '" . $value["Pend_S2_Honorer"] . "'
        AND \"Pend_S3_Honorer\" = '" . $value["Pend_S3_Honorer"] . "'
        AND \"Pend_SP1_Honorer\" = '" . $value["Pend_SP1_Honorer"] . "'
        AND \"Pend_SP2_Honorer\" = '" . $value["Pend_SP2_Honorer"] . "'
        AND \"Studi_Lanjut\" = '" . $value["Studi_Lanjut"] . "'
        AND \"CDT\" = '" . $value["CDT"] . "'
        AND \"Usia<30\" = " . $value["Usia<30"] . "
        AND \"Usia30-35\" = '" . $value["Usia30-35"] . "'
        AND \"Usia35-40\" = '" . $value["Usia35-40"] . "'
        AND \"Usia40-45\" = '" . $value["Usia40-45"] . "'
        AND \"Usia45-50\" = '" . $value["Usia45-50"] . "'
        AND \"Usia50-55\" = '" . $value["Usia50-55"] . "'
        AND \"Usia55-60\" = '" . $value["Usia55-60"] . "'
        AND \"Usia60-65\" = '" . $value["Usia60-65"] . "'
        AND \"Usia>=65\" = '" . $value["Usia>=65"] . "'
        AND \"MasaKerja<3\" = '" . $value["MasaKerja<3"] . "'
        AND \"MasaKerja3-5\" = '" . $value["MasaKerja3-5"] . "'
        AND \"MasaKerja5-10\" = '" . $value["MasaKerja5-10"] . "'
        AND \"MasaKerja10-20\" = '" . $value["MasaKerja10-20"] . "'
        AND \"MasaKerja20-25\" = '" . $value["MasaKerja20-25"] . "'
        AND \"MasaKerja25-30\" = '" . $value["MasaKerja25-30"] . "'
        AND \"MasaKerja30-40\" = '" . $value["MasaKerja30-40"] . "'
        AND \"MasaKerja>40\" = '" . $value["MasaKerja>40"] . "'
        AND \"LastTime\" = '" . $value["LastTime"] . "'"
        )->getResultArray();

      if (!empty($firstCheck)) {

        $data = [
          "Unit"                  => $value["Unit"],
          "Detail"                => $value["Detail"],
          "Kontrak"               => $value["Kontrak"],
          "Tetap"                 => $value["Tetap"],
          "Jumlah"                => $value["Jumlah"],
          "PHK_Mengundurkan_Diri" => $value["PHK_Mengundurkan_Diri"],
          "PHK_Pensiun"           => $value["PHK_Pensiun"],
          "PHK_Meninggal_Dunia"   => $value["PHK_Meninggal_Dunia"],
          "PHK_Diberhentikan"     => $value["PHK_Diberhentikan"],
          "PHK_Akhir_Kontrak"     => $value["PHK_Akhir_Kontrak"],
          "PHK_Lain_Lain"         => $value["PHK_Lain_Lain"],
          "Asisten_Mahasiswa"     => $value["Asisten_Mahasiswa"],
          "Jumlah_Honorer"        => $value["Jumlah_Honorer"],
          "Mhs_Aktif"             => $value["Mhs_Aktif"],
          "Agama_Hindu"           => $value["Agama_Hindu"],
          "Agama_Budha"           => $value["Agama_Budha"],
          "Agama_Kristen"         => $value["Agama_Kristen"],
          "Agama_Katolik"         => $value["Agama_Katolik"],
          "Agama_Islam"           => $value["Agama_Islam"],
          "Jumlah_SKS"            => $value["Jumlah_SKS"],
          "Kelamin_Pria"          => $value["Kelamin_Pria"],
          "Kelamin_Wanita"        => $value["Kelamin_Wanita"],
          "JA_TP"                 => $value["JA_TP"],
          "JA_AA"                 => $value["JA_AA"],
          "JA_L"                  => $value["JA_L"],
          "JA_LK"                 => $value["JA_LK"],
          "JA_GB"                 => $value["JA_GB"],
          "JA_PRO"                => $value["JA_PRO"],
          "JA_TP_Honorer"         => $value["JA_TP_Honorer"],
          "JA_AA_Honorer"         => $value["JA_AA_Honorer"],
          "JA_L_Honorer"          => $value["JA_L_Honorer"],
          "JA_LK_Honorer"         => $value["JA_LK_Honorer"],
          "JA_GB_Honorer"         => $value["JA_GB_Honorer"],
          "JA_PRO_Honorer"        => $value["JA_PRO_Honorer"],
          "Pend_S1"               => $value["Pend_S1"],
          "Pend_S2"               => $value["Pend_S2"],
          "Pend_S3"               => $value["Pend_S3"],
          "Pend_SP1"              => $value["Pend_SP1"],
          "Pend_SP2"              => $value["Pend_SP2"],
          "Pend_S1_Honorer"       => $value["Pend_S1_Honorer"],
          "Pend_S2_Honorer"       => $value["Pend_S2_Honorer"],
          "Pend_S3_Honorer"       => $value["Pend_S3_Honorer"],
          "Pend_SP1_Honorer"      => $value["Pend_SP1_Honorer"],
          "Pend_SP2_Honorer"      => $value["Pend_SP2_Honorer"],
          "Studi_Lanjut"          => $value["Studi_Lanjut"],
          "CDT"                   => $value["CDT"],
          "Usia<30"               => $value["Usia<30"],
          "Usia30-35"             => $value["Usia30-35"],
          "Usia35-40"             => $value["Usia35-40"],
          "Usia40-45"             => $value["Usia40-45"],
          "Usia45-50"             => $value["Usia45-50"],
          "Usia50-55"             => $value["Usia50-55"],
          "Usia55-60"             => $value["Usia55-60"],
          "Usia60-65"             => $value["Usia60-65"],
          "Usia>=65"              => $value["Usia>=65"],
          "MasaKerja<3"           => $value["MasaKerja<3"],
          "MasaKerja3-5"          => $value["MasaKerja3-5"],
          "MasaKerja5-10"         => $value["MasaKerja5-10"],
          "MasaKerja10-20"        => $value["MasaKerja10-20"],
          "MasaKerja20-25"        => $value["MasaKerja20-25"],
          "MasaKerja25-30"        => $value["MasaKerja25-30"],
          "MasaKerja30-40"        => $value["MasaKerja30-40"],
          "MasaKerja>40"          => $value["MasaKerja>40"],
          "LastTime"              => $value["LastTime"],
        ];

        $TRSDMReport->protect(false);
        $TRSDMReport->update($firstCheck[0]["ID"], $data);
        $TRSDMReport->protect(true);
      } else {

        $data = [
          "Unit"                  => $value["Unit"],
          "Detail"                => $value["Detail"],
          "Kontrak"               => $value["Kontrak"],
          "Tetap"                 => $value["Tetap"],
          "Jumlah"                => $value["Jumlah"],
          "PHK_Mengundurkan_Diri" => $value["PHK_Mengundurkan_Diri"],
          "PHK_Pensiun"           => $value["PHK_Pensiun"],
          "PHK_Meninggal_Dunia"   => $value["PHK_Meninggal_Dunia"],
          "PHK_Diberhentikan"     => $value["PHK_Diberhentikan"],
          "PHK_Akhir_Kontrak"     => $value["PHK_Akhir_Kontrak"],
          "PHK_Lain_Lain"         => $value["PHK_Lain_Lain"],
          "Asisten_Mahasiswa"     => $value["Asisten_Mahasiswa"],
          "Jumlah_Honorer"        => $value["Jumlah_Honorer"],
          "Mhs_Aktif"             => $value["Mhs_Aktif"],
          "Agama_Hindu"           => $value["Agama_Hindu"],
          "Agama_Budha"           => $value["Agama_Budha"],
          "Agama_Kristen"         => $value["Agama_Kristen"],
          "Agama_Katolik"         => $value["Agama_Katolik"],
          "Agama_Islam"           => $value["Agama_Islam"],
          "Jumlah_SKS"            => $value["Jumlah_SKS"],
          "Kelamin_Pria"          => $value["Kelamin_Pria"],
          "Kelamin_Wanita"        => $value["Kelamin_Wanita"],
          "JA_TP"                 => $value["JA_TP"],
          "JA_AA"                 => $value["JA_AA"],
          "JA_L"                  => $value["JA_L"],
          "JA_LK"                 => $value["JA_LK"],
          "JA_GB"                 => $value["JA_GB"],
          "JA_PRO"                => $value["JA_PRO"],
          "JA_TP_Honorer"         => $value["JA_TP_Honorer"],
          "JA_AA_Honorer"         => $value["JA_AA_Honorer"],
          "JA_L_Honorer"          => $value["JA_L_Honorer"],
          "JA_LK_Honorer"         => $value["JA_LK_Honorer"],
          "JA_GB_Honorer"         => $value["JA_GB_Honorer"],
          "JA_PRO_Honorer"        => $value["JA_PRO_Honorer"],
          "Pend_S1"               => $value["Pend_S1"],
          "Pend_S2"               => $value["Pend_S2"],
          "Pend_S3"               => $value["Pend_S3"],
          "Pend_SP1"              => $value["Pend_SP1"],
          "Pend_SP2"              => $value["Pend_SP2"],
          "Pend_S1_Honorer"       => $value["Pend_S1_Honorer"],
          "Pend_S2_Honorer"       => $value["Pend_S2_Honorer"],
          "Pend_S3_Honorer"       => $value["Pend_S3_Honorer"],
          "Pend_SP1_Honorer"      => $value["Pend_SP1_Honorer"],
          "Pend_SP2_Honorer"      => $value["Pend_SP2_Honorer"],
          "Studi_Lanjut"          => $value["Studi_Lanjut"],
          "CDT"                   => $value["CDT"],
          "Usia<30"               => $value["Usia<30"],
          "Usia30-35"             => $value["Usia30-35"],
          "Usia35-40"             => $value["Usia35-40"],
          "Usia40-45"             => $value["Usia40-45"],
          "Usia45-50"             => $value["Usia45-50"],
          "Usia50-55"             => $value["Usia50-55"],
          "Usia55-60"             => $value["Usia55-60"],
          "Usia60-65"             => $value["Usia60-65"],
          "Usia>=65"              => $value["Usia>=65"],
          "MasaKerja<3"           => $value["MasaKerja<3"],
          "MasaKerja3-5"          => $value["MasaKerja3-5"],
          "MasaKerja5-10"         => $value["MasaKerja5-10"],
          "MasaKerja10-20"        => $value["MasaKerja10-20"],
          "MasaKerja20-25"        => $value["MasaKerja20-25"],
          "MasaKerja25-30"        => $value["MasaKerja25-30"],
          "MasaKerja30-40"        => $value["MasaKerja30-40"],
          "MasaKerja>40"          => $value["MasaKerja>40"],
          "LastTime"              => $value["LastTime"],
          "LastUser"              => $value["LastUser"],
        ];

        $TRSDMReport->protect(false);
        $TRSDMReport->save($data);
        $TRSDMReport->protect(true);
      }
    }
  }
}
