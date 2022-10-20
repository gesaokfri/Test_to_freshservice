<?php

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;

// TRIGGER MAHASISWA
use App\Models\Trigger\Mahasiswa\DataMahasiswaBulan;
use App\Models\Trigger\Mahasiswa\MsMahasiswa;
// TRIGGER MAHASISWA

class IntegrationMahasiswaController extends BaseController
{
  public function Index()
  {
    $this->TriggerMahasiswa();
  }

  public function TriggerMahasiswa()
  {

    $DataMahasiswaBulan = new DataMahasiswaBulan();
    $MsMahasiswa        = new MsMahasiswa();

    ini_set('memory_limit', '1024M'); // Setting to 1024M
    ini_set('sqlsrv.ClientBufferMaxKBSize', '1524288'); // Setting to 1512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '1524288'); // Setting to 1512M
    ini_set('max_execution_time', '0'); // Setting to UNLIMITED

    $getMahasiswa = $DataMahasiswaBulan
    ->where("Year", date("Y"))
    ->where("Month", date("n"))
    ->findAll();

    foreach ($getMahasiswa as $value) {
      $firstCheck = $MsMahasiswa
      ->where("Year", $value["Year"])
      ->where("Month", $value["Month"])
      ->where("AdmitTerm", $value["AdmitTerm"])
      ->where("StudentID", $value["StudentID"])
      ->where("AcadProg", $value["AcadProg"])
      ->first();
      
      if (!empty($firstCheck)) {
        $data = [
          "Year"      => $value["Year"],
          "Month"     => $value["Month"],
          "AdmitTerm" => $value["AdmitTerm"],
          "StudentID" => $value["StudentID"],
          "AcadProg"  => $value["AcadProg"],
          "EffDt"     => $value["EffDt"],
          "Status"    => $value["Status"]
        ];
        $MsMahasiswa->protect(false);
        $MsMahasiswa->update($firstCheck["ID"], $data);
        $MsMahasiswa->protect(true);
      } else {        
        $data = [
          "Year"      => $value["Year"],
          "Month"     => $value["Month"],
          "AdmitTerm" => $value["AdmitTerm"],
          "StudentID" => $value["StudentID"],
          "AcadProg"  => $value["AcadProg"],
          "EffDt"     => $value["EffDt"],
          "Status"    => $value["Status"]
        ];

        $MsMahasiswa->protect(false);
        $MsMahasiswa->save($data);
        $MsMahasiswa->protect(true);
      }
    }

    ini_set('memory_limit', '512M');
    ini_set('sqlsrv.ClientBufferMaxKBSize', '524288'); // Setting to 512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '524288'); // Setting to 512M - for pdo_sqlsrv

  }
}
