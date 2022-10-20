<?php

namespace App\Controllers\Dashboard\KegiatanTridharma;

use App\Controllers\BaseController;

use App\Models\Fakultas;

use App\Models\Dashboard\KegiatanTridharmaPT\Penelitian\ViewJumlahPenelitian;
use App\Models\Dashboard\KegiatanTridharmaPT\Penelitian\ViewTridharmaPenelitianFakultas;
use App\Models\Dashboard\KegiatanTridharmaPT\Penelitian\ViewTridharmaPenelitianProdi;

use App\Models\Dashboard\KegiatanTridharmaPT\Pengabdian\ViewJumlahPengabdian;
use App\Models\Dashboard\KegiatanTridharmaPT\Pengabdian\ViewTridharmaPengabdianFakultas;
use App\Models\Dashboard\KegiatanTridharmaPT\Pengabdian\ViewTridharmaPengabdianProdi;

use App\Libraries\MyDatatables;

class TridharmaController extends BaseController
{

  public $mydata;
  public function __construct() {
    $this->mydata['id_menu'] = '7';
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
  }

  public function Index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $fakultas = new Fakultas();

      $data['fakultas'] = $fakultas->whereNotIn('KodeFakultas', ['00', '50', '99'])->findAll();
      $data['id_menu']  = $this->mydata['id_menu'];
      if(acc_read(session('level_id'),$data['id_menu'])=="1"){
        return $this->blade->render("pages.kegiatan_tridharma.tridharma.index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function jumlahPenelitian() 
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $kodeFakultas = $this->request->getPost('kode_fakultas');
  
        // create session fakultas di section jumlah penelitian
        $_SESSION["jumlahPenelitian"] = [
          "jumlahPenelitianFakultas" => $kodeFakultas,
        ];
  
        return $this->blade->render("pages.kegiatan_tridharma.tridharma.penelitian.jumlah_penelitian");
      } else {
        return redirect()->to('/universitas');
      }

    }

  }

  public function resJumlahPenelitian() 
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas = $_SESSION["jumlahPenelitian"]["jumlahPenelitianFakultas"];
      
      $viewJumlahPenelitian = new ViewJumlahPenelitian();

      if ($kodeFakultas != "") {
        $query = $viewJumlahPenelitian
        ->select('SkemaPenelitian, Judul, Nama, AnggaranDisetujui, Tahun')
        ->where('KodeFakultas', $kodeFakultas);
      } else {
        $query = $viewJumlahPenelitian
        ->select('SkemaPenelitian, Judul, Nama, AnggaranDisetujui, Tahun');
      }

      $datatables   = new MyDatatables();
      $columnOrder  = [
        "SkemaPenelitian", "Judul", "Nama", "AnggaranDisetujui", "Tahun"
      ];
      $columnSearch = [
        "Nama", "SkemaPenelitian", "Judul", "Tahun"
      ];

      $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
      $data    = [];

      foreach ($getList as $list) {
        $row                    = [];
        $row["skemaPenelitian"] = $list->SkemaPenelitian;
        $row["Judul"]           = $list->Judul;
        $row["namaPeneliti"]    = $list->Nama;
        $row["Dana"]            = int_to_rp($list->AnggaranDisetujui/1000000);
        $row["Tahun"]           = $list->Tahun;

        $data[] = $row;
      }

      return $datatables->response($data);
    }

  }

  public function chartJumlahPenelitian()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];
      
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $kodeFakultas = $this->request->getPost("fakultas");
        
        // PANGGIL MODEL
        $Fakultas                        = new Fakultas();
        $viewTridharmaPenelitianFakultas = new ViewTridharmaPenelitianFakultas();
        $viewTridharmaPenelitianProdi    = new ViewTridharmaPenelitianProdi();

        // VARIABEL UNTUK CHART
        $chartColor            = array();
        $chartJumlahPenelitian = array();

        // VARIABEL UNTUK TOTAL PENELITIAN
        $totalJumlahPenelitianPerTahun = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];
        $totalJumlahPenelitianGabunganPerTahun = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn = 0;
        $listTahun = [];
        $tahunTerbaruPenelitian = $viewTridharmaPenelitianFakultas->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->first();
        $tahunCompare           = $tahunTerbaruPenelitian["Tahun"];
        for ($thn = ($tahunCompare - 4); $thn <= $tahunCompare; $thn++) {
          $listTahun[] = $thn;
        }
        
        // START PENGOLAHAN DATA CHART
        if ($kodeFakultas == "") {
          $getNamaFakultas = $Fakultas->select("KodeFakultas, NamaFakultas")->whereNotIn('KodeFakultas', ['00', '50', '99'])->groupBy("KodeFakultas, NamaFakultas")->orderBy("KodeFakultas", "ASC")->findAll();

          foreach ($getNamaFakultas as $item) {
            $chartColor[] = $this->mydata["color"][$i];
            $chartJumlahPenelitian[$i]["name"] = $item["NamaFakultas"];

            $data_penelitian = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getPenelitianFakultas = $viewTridharmaPenelitianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("JumlahPeneliti =", "1")->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getPenelitianFakultas)) {
                array_push($data_penelitian, 0);
              } else {
                array_push($data_penelitian, $getPenelitianFakultas);
              }
            }

            $data_penelitian_gabungan = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getPenelitianGabunganFakultas = $viewTridharmaPenelitianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("JumlahPeneliti >", "1")->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getPenelitianGabunganFakultas)) {
                array_push($data_penelitian_gabungan, 0);
              } else {
                array_push($data_penelitian_gabungan, $getPenelitianGabunganFakultas);
              }
            }

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getTotalPenelitianFakultas = $viewTridharmaPenelitianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getTotalPenelitianFakultas)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getTotalPenelitianFakultas);
              }
            }

            $chartJumlahPenelitian[$i]["data"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL PENELITIAN PER TAHUN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalJumlahPenelitianPerTahun[$totalCJMP] += $data_penelitian[$totalCJMP];
              $totalJumlahPenelitianGabunganPerTahun[$totalCJMP] += $data_penelitian_gabungan[$totalCJMP];
            }

            $i++;
          }

          // START PENGOLAHAN DATA TAHUN CHART
          $LastFiveYears = [];
          for ($i = 0; $i <= 4; $i++) {
            $LastFiveYears[] = [
              "$listTahun[$i]", "($totalJumlahPenelitianPerTahun[$i] " . "Penelitian)", "($totalJumlahPenelitianGabunganPerTahun[$i] " . "Gabungan)"
            ];
          }
          // END PENGOLAHAN DATA TAHUN CHART

        } else {
          $getNamaProdi = $viewTridharmaPenelitianProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata["color"][$i];
            $chartJumlahPenelitian[$i]["name"] = $item["NamaProdi"];

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getTotalPenelitianProdi = $viewTridharmaPenelitianProdi->where("KodeFakultas", $item["KodeFakultas"])->where("NamaProdi", $item["NamaProdi"])->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getTotalPenelitianProdi)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getTotalPenelitianProdi);
              }
            }

            $chartJumlahPenelitian[$i]["data"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL PENELITIAN PER TAHUN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalJumlahPenelitianPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }

          // START PENGOLAHAN DATA TAHUN CHART
          $LastFiveYears = [];
          for ($i = 0; $i <= 4; $i++) {
              $LastFiveYears[] = [
                "$listTahun[$i]", "($totalJumlahPenelitianPerTahun[$i] " . "Total Penelitian)"
              ];
            }
          // END PENGOLAHAN DATA TAHUN CHART

        }
        // END PENGOLAHAN DATA CHART

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartJumlahPenelitian"] = json_encode($chartJumlahPenelitian);
        $data["ChartColor"]            = json_encode($chartColor);
        $data["LastFiveYears"]         = json_encode($LastFiveYears);
        
        if ($kodeFakultas == "") {
          return $this->blade->render("pages.kegiatan_tridharma.tridharma.penelitian.chart_jumlah_penelitian", $data);
        } else {
          return $this->blade->render("pages.kegiatan_tridharma.tridharma.penelitian.chart_jumlah_penelitian_prodi", $data);
        }

      } else {
        return redirect()->to('/universitas');
      }
      
    }
  }

  public function jumlahPengabdian()
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $kodeFakultas = $this->request->getPost('kode_fakultas');

        // create session fakultas di section jumlah pengabdian
        $_SESSION["jumlahPengabdian"] = [
          "jumlahPengabdianFakultas" => $kodeFakultas,
        ];

        return $this->blade->render("pages.kegiatan_tridharma.tridharma.pengabdian.jumlah_pengabdian");
      } else {
        return redirect()->to('/universitas');
      }
      
    }

  }

  public function resJumlahPengabdian() 
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas = $_SESSION["jumlahPengabdian"]["jumlahPengabdianFakultas"];

      $viewJumlahPengabdian = new ViewJumlahPengabdian();

      if ($kodeFakultas != "") {
        $query = $viewJumlahPengabdian
        ->select('SkemaPengabdian, Judul, Nama, AnggaranDisetujui, Tahun')
        ->where('KodeFakultas', $kodeFakultas);
      } else {
        $query = $viewJumlahPengabdian
        ->select('SkemaPengabdian, Judul, Nama, AnggaranDisetujui, Tahun');
      }

      $datatables   = new MyDatatables();
      $columnOrder  = [
        "SkemaPengabdian", "Judul", "Nama", "AnggaranDisetujui", "Tahun"
      ];
      $columnSearch = [
        "SkemaPengabdian", "Judul", "Nama", "Tahun"
      ];

      $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
      $data    = [];

      foreach ($getList as $list) {
        $row                    = [];
        $row["skemaPengabdian"] = $list->SkemaPengabdian;
        $row["Judul"]           = $list->Judul;
        $row["namaPengabdi"]    = $list->Nama;
        $row["Dana"]            = int_to_rp($list->AnggaranDisetujui/1000000);
        $row["Tahun"]           = $list->Tahun;

        $data[] = $row;
      }

      return $datatables->response($data);
    }

  }

  public function chartJumlahPengabdian()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // GET KODE FAKULTAS SELECTED
        $kodeFakultas = $this->request->getPost("fakultas");

        // PANGGIL MODEL
        $Fakultas                        = new Fakultas();
        $viewTridharmaPengabdianFakultas = new ViewTridharmaPengabdianFakultas();
        $viewTridharmaPengabdianProdi    = new ViewTridharmaPengabdianProdi();

        // VARIABEL UNTUK CHART
        $chartColor            = array();
        $chartJumlahPengabdian = array();

        // VARIABEL UNTUK TOTAL Pengabdian
        $totalJumlahPengabdianPerTahun = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];
        $totalJumlahPengabdianGabunganPerTahun = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn = 0;
        $listTahun = [];
        $tahunTerbaruPengabdian = $viewTridharmaPengabdianFakultas->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->first();
        $tahunCompare           = $tahunTerbaruPengabdian["Tahun"];
        for ($thn = ($tahunCompare - 4); $thn <= $tahunCompare; $thn++) {
          $listTahun[] = $thn;
        }

        // KALAU KODE FAKULTAS KOSONG JALANKAN
        if ($kodeFakultas == "") {
          $getNamaFakultas = $Fakultas->select("KodeFakultas, NamaFakultas")->whereNotIn('KodeFakultas', ['00', '50', '99'])->groupBy("KodeFakultas, NamaFakultas")->orderBy("KodeFakultas", "ASC")->findAll();

          foreach ($getNamaFakultas as $item) {
            $chartColor[] = $this->mydata["color"][$i];
            $chartJumlahPengabdian[$i]["name"] = $item["NamaFakultas"];

            $data_pengabdian = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getPengabdianFakultas = $viewTridharmaPengabdianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("JumlahPengabdi =", "1")->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getPengabdianFakultas)) {
                array_push($data_pengabdian, 0);
              } else {
                array_push($data_pengabdian, $getPengabdianFakultas);
              }
            }

            $data_pengabdian_gabungan = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getPengabdianGabunganFakultas = $viewTridharmaPengabdianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("JumlahPengabdi >", "1")->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getPengabdianGabunganFakultas)) {
                array_push($data_pengabdian_gabungan, 0);
              } else {
                array_push($data_pengabdian_gabungan, $getPengabdianGabunganFakultas);
              }
            }

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getTotalPengabdianFakultas = $viewTridharmaPengabdianFakultas->where("KodeFakultas", $item["KodeFakultas"])->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getTotalPengabdianFakultas)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getTotalPengabdianFakultas);
              }
            }

            $chartJumlahPengabdian[$i]["data"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL Pengabdian PER TAHUN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalJumlahPengabdianPerTahun[$totalCJMP] += $data_pengabdian[$totalCJMP];
              $totalJumlahPengabdianGabunganPerTahun[$totalCJMP] += $data_pengabdian_gabungan[$totalCJMP];
            }

            $i++;
          }

          $LastFiveYears = [];
          for ($i = 0; $i <= 4; $i++) {
            $LastFiveYears[] = [
              "$listTahun[$i]", "($totalJumlahPengabdianPerTahun[$i] " . "Pengabdian)", "($totalJumlahPengabdianGabunganPerTahun[$i] " . "Gabungan)"
            ];
          }

        } else {
          $getNamaProdi = $viewTridharmaPengabdianProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata['color'][$i];

            $chartJumlahPengabdian[$i]['name'] = $item['NamaProdi'];

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getTotalPengabdianProdi = $viewTridharmaPengabdianProdi->where("KodeFakultas", $item["KodeFakultas"])->where("NamaProdi", $item["NamaProdi"])->where("Tahun", $listTahun[$perthn])->countAllResults();
              if (is_null($getTotalPengabdianProdi)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getTotalPengabdianProdi);
              }
            }

            $chartJumlahPengabdian[$i]['data'] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL PER TAHUN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalJumlahPengabdianPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }

          $LastFiveYears = [];
          for ($i = 0; $i <= 4; $i++) {
            $LastFiveYears[] = [
              "$listTahun[$i]", "($totalJumlahPengabdianPerTahun[$i] " . "Total Pengabdian)"
            ];
          }

        }

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartJumlahPengabdian"] = json_encode($chartJumlahPengabdian);
        $data["ChartColor"]            = json_encode($chartColor);
        $data["LastFiveYears"]         = json_encode($LastFiveYears);

        if ($kodeFakultas == "") {
          return $this->blade->render("pages.kegiatan_tridharma.tridharma.pengabdian.chart_jumlah_pengabdian", $data);
        } else {
          return $this->blade->render("pages.kegiatan_tridharma.tridharma.pengabdian.chart_jumlah_pengabdian_prodi", $data);
        }
      } else {
        return redirect()->to('/universitas');
      }
      
    }
  }

}
