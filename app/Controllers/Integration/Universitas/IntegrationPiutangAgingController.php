<?php

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;

// TRIGGER PIUTANG AGING
use App\Models\Trigger\PiutangAging\PiutangAging;
use App\Models\Trigger\PiutangAging\TRPiutangAging;
// TRIGGER PIUTANG AGING

class IntegrationPiutangAgingController extends BaseController
{
  public function Index()
  {
    $this->TriggerPiutangAging();
  }

  public function TriggerPiutangAging()
  {

    $PiutangAging   = new PiutangAging();
    $TRPiutangAging = new TRPiutangAging();

    $getPiutangAging = $PiutangAging
    ->where("LEFT (PERIODE, 7)", date("Y-m", strtotime("-1 Month")))
    ->findAll();

    foreach ($getPiutangAging as $value) {

      $firstCheck = $TRPiutangAging
        ->where("AGING", $value["AGING"])
        ->where("ACAD_CAREER", $value["ACAD_CAREER"])
        ->where("NAMA_PRODI", $value["NAMA_PRODI"])
        ->where("PERIODE", $value["PERIODE"])
        ->first();

      if (!empty($firstCheck)) {

        $data = [
          "AGING"       => $value["AGING"],
          "ACAD_CAREER" => $value["ACAD_CAREER"],
          "NAMA_PRODI"  => $value["NAMA_PRODI"],
          "HUTANG"      => $value["HUTANG"],
          "PERIODE"     => $value["PERIODE"]
        ];

        $TRPiutangAging->protect(false);
        $TRPiutangAging->update($firstCheck["ID"], $data);
        $TRPiutangAging->protect(true);
      } else {

        $data = [
          "AGING"       => $value["AGING"],
          "ACAD_CAREER" => $value["ACAD_CAREER"],
          "NAMA_PRODI"  => $value["NAMA_PRODI"],
          "HUTANG"      => $value["HUTANG"],
          "PERIODE"     => $value["PERIODE"]
        ];

        $TRPiutangAging->protect(false);
        $TRPiutangAging->save($data);
        $TRPiutangAging->protect(true);
      }
    }

  }
}
