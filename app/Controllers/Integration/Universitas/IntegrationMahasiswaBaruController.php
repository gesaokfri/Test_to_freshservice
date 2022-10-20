<?php

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;

// TRIGGER MAHASISWA BARU
use App\Models\Trigger\MahasiswaBaru\DashboardJumlahPmbPerTahun;
use App\Models\Trigger\MahasiswaBaru\MsMahasiswaBaru;
// TRIGGER MAHASISWA BARU

class IntegrationMahasiswaBaruController extends BaseController
{
  public function Index()
  {
    $this->TriggerMahasiswaBaru();
  }

  public function TriggerMahasiswaBaru()
  {

    $DashboardJumlahPmbPerTahun = new DashboardJumlahPmbPerTahun();
    $MsMahasiswaBaru            = new MsMahasiswaBaru();

    ini_set('memory_limit', '1024M'); // Setting to 1024M
    ini_set('sqlsrv.ClientBufferMaxKBSize', '1524288'); // Setting to 1512M
    ini_set('pdo_sqlsrv.client_buffer_max_kb_size', '1524288'); // Setting to 1512M

    $getMahasiswaBaru = $DashboardJumlahPmbPerTahun->findAll();

    foreach ($getMahasiswaBaru as $value) {
      $firstCheck = $MsMahasiswaBaru
      ->where("Jenjang", $value["Jenjang"])
      ->where("TahunSemMasuk", $value["TahunSemMasuk"])
      ->where("KodeProdi", $value["KodeProdi"])
      ->first();

      if (!empty($firstCheck)) {
        $data = [
          "Jenjang"          => $value["Jenjang"],
          "TahunSemMasuk"    => $value["TahunSemMasuk"],
          "KodeProdi"        => $value["KodeProdi"],
          "DaftarPil1"       => $value["DaftarPil1"],
          "DaftarPil2"       => $value["DaftarPil2"],
          "JmlLulus"         => $value["JmlLulus"],
          "JmlKonfirmasi"    => $value["JmlKonfirmasi"],
          "TargetPendaftar"  => $value["TargetPendaftar"],
          "TargetLulus"      => $value["TargetLulus"],
          "TargetKonfirmasi" => $value["TargetKonfirmasi"],
          "TargetAktif"      => $value["TargetAktif"],
        ];

        $MsMahasiswaBaru->protect(false);
        $MsMahasiswaBaru->update($firstCheck["ID"], $data);
        $MsMahasiswaBaru->protect(true);
      } else {
        $data = [
          "Jenjang"          => $value["Jenjang"],
          "TahunSemMasuk"    => $value["TahunSemMasuk"],
          "KodeProdi"        => $value["KodeProdi"],
          "DaftarPil1"       => $value["DaftarPil1"],
          "DaftarPil2"       => $value["DaftarPil2"],
          "JmlLulus"         => $value["JmlLulus"],
          "JmlKonfirmasi"    => $value["JmlKonfirmasi"],
          "TargetPendaftar"  => $value["TargetPendaftar"],
          "TargetLulus"      => $value["TargetLulus"],
          "TargetKonfirmasi" => $value["TargetKonfirmasi"],
          "TargetAktif"      => $value["TargetAktif"],
        ];

        $MsMahasiswaBaru->protect(false);
        $MsMahasiswaBaru->save($data);
        $MsMahasiswaBaru->protect(true);
      }
    }

  }
}
