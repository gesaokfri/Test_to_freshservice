<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

use App\Models\Dashboard\LaporanBeasiswa\ViewBeasiswa;
use App\Models\Dashboard\LaporanBeasiswa\ViewBeasiswaPenerimaan;
use App\Models\Dashboard\LaporanBeasiswa\ViewBeasiswaPembayaran;
use App\Models\Dashboard\LaporanBeasiswa\ViewBeasiswaAdministration;
use App\Models\Dashboard\LaporanBeasiswa\ViewDonatur;
use App\Models\Dashboard\LaporanBeasiswa\ViewDonaturTahun;
use CodeIgniter\Config\View;

class LaporanBeasiswaController extends BaseController
{
  public $mydata;
  public function __construct()
  {
    $this->mydata["id_menu"] = "6";
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

    $this->mydata["beasiswa"] = [
      1 => "Penerimaan",
      2 => "Pembayaran",
      3 => "Administration"
    ];
  }

  public function Index()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      $Beasiswa = new ViewBeasiswa();
      $data["beasiswaCode"] = $Beasiswa->select("BeasiswaCode")->orderBy("BeaAmount", "DESC")->findAll();
      $tahunBeasiswa = new ViewBeasiswaPenerimaan();
      $data["beasiswaYear"] = $tahunBeasiswa->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->findAll();
      $Donatur = new ViewDonatur();
      $data["beasiswaDonatur"] = $Donatur->select("DonaturName")->orderBy("BeaAmount", "DESC")->findAll();
      $tahunDonatur = new ViewDonaturTahun();
      $data["donaturYear"] = $tahunDonatur->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->findAll();

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("pages.laporan_beasiswa.index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }
  
  public function ChartJenisBeasiswa() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];
      
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // GET PROGRAM SELECTED, TAHUN TERAKHIR SELECTED
        $programSelected = $this->request->getPost("name");
        $tahun           = $this->request->getPost("year");

        // PANGGIL MODEL
        $Beasiswa                   = new ViewBeasiswa();
        $viewBeasiswaPenerimaan     = new ViewBeasiswaPenerimaan();
        $viewBeasiswaPembayaran     = new ViewBeasiswaPembayaran();
        $viewBeasiswaAdministration = new ViewBeasiswaAdministration();

        // CEK APAKAH MEMILIH BEASISWA CODE
        if ($programSelected == "") {
          $getBeasiswaCode = $Beasiswa->select("BeasiswaCode")->orderBy("BeaAmount", "DESC")->findAll(5);

          foreach ($getBeasiswaCode as $item) {
            $BeasiswaCode[] = $item["BeasiswaCode"];
          }
        } else {
          foreach ($programSelected as $item) {
            $BeasiswaCode[] = $item;
          }
        }

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // VARIABEL UNTUK CHART
        $chartJenisBeasiswa = array();

        // START MAIN FUNCTION
        if (!empty($BeasiswaCode)) {

          if (count($BeasiswaCode) > 1) {
            if ($tahun == "") {
              foreach ($this->mydata["beasiswa"] as $value) {
                $chartJenisBeasiswa[$i]["name"] = $value;

                $data_array = array();
                switch ($value) {
                  case "Penerimaan":
                    foreach ($BeasiswaCode as $item) {
                      $getValue = $viewBeasiswaPenerimaan->select("SUM(BeaAmount) as BeaAmount")->where(["BeasiswaCode" => $item])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  case "Pembayaran":
                    foreach ($BeasiswaCode as $item2) {
                      $getValue = $viewBeasiswaPembayaran->select("SUM(BeaAmount) as BeaAmount")->where(["BeasiswaCode" => $item2])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  case "Administration":
                    foreach ($BeasiswaCode as $item3) {
                      $getValue = $viewBeasiswaAdministration->select("SUM(BeaAmount) as BeaAmount")->where(["BeasiswaCode" => $item3])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  default:
                    return redirect()->to("/universitas");
                }
                $chartJenisBeasiswa[$i]["data"] = $data_array;

                $i++;
              }
            } else {
              foreach ($this->mydata["beasiswa"] as $value) {
                $chartJenisBeasiswa[$i]["name"] = $value;

                $data_array = array();
                switch ($value) {
                  case "Penerimaan":
                    foreach ($BeasiswaCode as $item) {
                      $getValue = $viewBeasiswaPenerimaan->select("BeaAmount")->where(["BeasiswaCode" => $item, "Tahun" => $tahun])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  case "Pembayaran":
                    foreach ($BeasiswaCode as $item2) {
                      $getValue = $viewBeasiswaPembayaran->select("BeaAmount")->where(["BeasiswaCode" => $item2, "Tahun" => $tahun])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  case "Administration":
                    foreach ($BeasiswaCode as $item3) {
                      $getValue = $viewBeasiswaAdministration->select("BeaAmount")->where(["BeasiswaCode" => $item3, "Tahun" => $tahun])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
                      }
                    }
                    break;
                  default:
                    return redirect()->to("/universitas");
                }
                $chartJenisBeasiswa[$i]["data"] = $data_array;

                $i++;
              }
            }
          } else {

            // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
            $thn = 0;
            $listTahun = [];
            if ($tahun == "") {
              $tahunBeasiswa = new ViewBeasiswaPenerimaan();
              $tahunTerbaruBeasiswa = $tahunBeasiswa->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->first();
              $tahun = $tahunTerbaruBeasiswa["Tahun"];
            }
            for ($thn = ($tahun - 4); $thn <= $tahun; $thn++) {
              $listTahun[] = $thn;
            }

            foreach ($this->mydata["beasiswa"] as $value) {
              $chartJenisBeasiswa[$i]["name"] = $value;

              $data_array = array();
              switch ($value) {
                case "Penerimaan":
                  foreach ($BeasiswaCode as $item) {
                    foreach ($listTahun as $item2) {
                      $getValue = $viewBeasiswaPenerimaan->select("BeaAmount")->where(["BeasiswaCode" => $item, "Tahun" => $item2])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, round((int)$getValue["BeaAmount"] / 1000000, 1));
                      }
                    }
                  }
                  break;
                case "Pembayaran":
                  foreach ($BeasiswaCode as $item3) {
                    foreach ($listTahun as $item4) {
                      $getValue = $viewBeasiswaPembayaran->select("BeaAmount")->where(["BeasiswaCode" => $item3, "Tahun" => $item4])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, round((int)$getValue["BeaAmount"] / 1000000, 1));
                      }
                    }
                  }
                  break;
                case "Administration":
                  foreach ($BeasiswaCode as $item5) {
                    foreach ($listTahun as $item6) {
                      $getValue = $viewBeasiswaAdministration->select("BeaAmount")->where(["BeasiswaCode" => $item5, "Tahun" => $item6])->first();
                      if (empty($getValue)) {
                        array_push($data_array, 0);
                      } else {
                        array_push($data_array, round((int)$getValue["BeaAmount"] / 1000000, 1));
                      }
                    }
                  }
                  break;
                default:
                  return redirect()->to("/universitas");
              }
              $chartJenisBeasiswa[$i]["data"] = $data_array;

              $i++;
            }

          }
          
        }
        // END MAIN FUNCTION

        // CATEGORIES CHART
        if (count($BeasiswaCode) > 1) {
          $categories = $BeasiswaCode;
        } else {
          $categories = $listTahun;
          $data["tableListTahun"] = $listTahun;
        }

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartJenisBeasiswa"] = json_encode($chartJenisBeasiswa);
        $data["chartCategories"]    = json_encode($categories);
        $data["tableJenisBeasiswa"] = $chartJenisBeasiswa;
        $data["tableBeaCode"]       = $BeasiswaCode;
        $data["tableYear"]          = $tahun;

        if (count($BeasiswaCode) > 1) {
          return $this->blade->render("pages.laporan_beasiswa.jenis.chart", $data);
        } elseif (count($BeasiswaCode) == 1) {
          return $this->blade->render("pages.laporan_beasiswa.jenis.line-chart", $data);
        }

      } else {
        return redirect()->to('/universitas');
      }

    }
  }

  public function ChartSumberDanaBeasiswa()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // GET DONATUR NAME SELECTED, TAHUN TERAKHIR SELECTED
        $donaturSelected = $this->request->getPost("name");
        $tahun           = $this->request->getPost("year");

        // PANGGIL MODEL
        $Donatur     = new ViewDonatur();
        $viewDonatur = new ViewDonaturTahun();

        // CEK APAKAH MEMILIH BEASISWA CODE
        if ($donaturSelected == "") {
          $getDonaturName = $Donatur->select("DonaturName")->orderBy("BeaAmount", "DESC")->findAll(8);

          foreach ($getDonaturName as $item) {
            $DonaturName[] = $item["DonaturName"];
          }
        } else {
          foreach ($donaturSelected as $item) {
            $DonaturName[] = $item;
          }
        }

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // VARIABEL UNTUK CHART
        $chartSumberDana = array();
        $chartColor      = array();

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn = 0;
        $listTahun = [];
        if ($tahun == "") {
          $tahunTerbaruDonatur = $viewDonatur->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->first();
          $tahun = $tahunTerbaruDonatur["Tahun"];
        }
        for ($thn = ($tahun - 4); $thn <= $tahun; $thn++) {
          $listTahun[] = $thn;
        }

        // START MAIN FUNCTION
        if (!empty($DonaturName)) {
          foreach ($DonaturName as $value) {
            $chartSumberDana[$i]["name"] = $value;
            $chartColor[] = $this->mydata["color"][$i];

            $data_array = array();
            for ($perthn=0; $perthn <= 4; $perthn++) {
              $getValue = $viewDonatur->select("BeaAmount")->where(["DonaturName" => $value, "Tahun" => $listTahun[$perthn]])->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, (int)$getValue["BeaAmount"] / 1000000);
              }
            }
          
            $chartSumberDana[$i]["data"] = $data_array;

            $i++;
          }
        }
        // END MAIN FUNCTION

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartSumberDana"]            = json_encode($chartSumberDana);
        $data["chartColor"]                 = json_encode($chartColor);
        $data["chartCategories"]            = json_encode($listTahun);
        $data["tableListTahun"]             = $listTahun;
        $data["tableSumberDana"]            = $chartSumberDana;

        return $this->blade->render("pages.laporan_beasiswa.sumber_dana.chart", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }


}