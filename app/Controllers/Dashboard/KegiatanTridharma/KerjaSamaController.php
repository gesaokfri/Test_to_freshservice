<?php

namespace App\Controllers\Dashboard\KegiatanTridharma;

use App\Controllers\BaseController;

use App\Models\Semester;

use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\FacultyInbound;
use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\FacultyOutbound;
use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\ViewFacultyInOut;

use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\StudentInbound;
use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\StudentOutbound;
use App\Models\Dashboard\KegiatanTridharmaPT\KerjaSama\ViewStudentInOut;

use App\Libraries\MyDatatables;

class KerjaSamaController extends BaseController
{
  public $mydata;
  public function __construct()
  {
    $this->mydata["id_menu"] = "20";

    $this->mydata["faculty"] = [
      1 => "Inbound",
      2 => "Outbound"
    ];

    $this->mydata["student"] = [
      1 => "Inbound",
      2 => "Outbound"
    ];
  }

  public function Index()
  {
    if (!$this->session->has("session_id")) {
      return redirect()->to("/");
    } else {
      $data["id_menu"]  = $this->mydata["id_menu"];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("pages.kegiatan_tridharma.kerjasama.index", $data);
      } else {
        return redirect()->to("/universitas");
      }
    }
  }

  public function ChartFaculty()
  {
    if (!$this->session->has("session_id")) {
      return redirect()->to("/");
    } else {
      $data["id_menu"]  = $this->mydata["id_menu"];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // VARIABEL UNTUK CHART
        $chartFacultyInOut = array();

        // VARIABEL UNTUK TOTAL FI FO
        $totalFacultyInOutPerTahun = [
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
        $semester = new Semester();
        $tahunTerbaruSemester = $semester->select("LEFT(kode_semester, 2) as Tahun")->groupBy("LEFT(kode_semester, 2)")->orderBy("Tahun", "DESC")->first();
        $tahunCompare = "20" . $tahunTerbaruSemester["Tahun"];
        for ($thn = ($tahunCompare - 4); $thn <= $tahunCompare; $thn++) {
          $listTahun[] = $thn;
        }

        // START PENGOLAHAN DATA CHART
        foreach ($this->mydata["faculty"] as $key => $item) {
          $chartFacultyInOut[$i]["name"] = $item;

          $data_array = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {

            switch ($key) {
              case 1:
                $FacultyInOut = new FacultyInbound();
                break;

              case 2:
                $FacultyInOut = new FacultyOutbound();
                break;
              
              default:
                return redirect()->to("/universitas");
                break;
            }

            $getFacultyInOut = $FacultyInOut->where("LEFT(tahun_semester, 2)", substr($listTahun[$perthn], -2))->countAllResults();

            if (empty($getFacultyInOut)) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, $getFacultyInOut);
            }

          }

          $chartFacultyInOut[$i]["data"] = $data_array;

          // INI UNTUK MENGHITUNG TOTAL FI FO PER TAHUN
          for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
            $totalFacultyInOutPerTahun[$totalCJMP] += $data_array[$totalCJMP];
          }

          $i++;
        }
        // END PENGOLAHAN DATA CHART

        // START PENGOLAHAN DATA TAHUN CHART
        $LastFiveYears = [];
        for ($i = 0; $i <= 4; $i++) {
          $LastFiveYears[] = [
            "$listTahun[$i]"
          ];
        }
        // END PENGOLAHAN DATA TAHUN CHART

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartFacultyInOut"] = json_encode($chartFacultyInOut);
        $data["chartCategories"]   = json_encode($LastFiveYears);

        return $this->blade->render("pages.kegiatan_tridharma.kerjasama.faculty.chart", $data);
      } else {
        return redirect()->to("/universitas");
      }
    }
  }

  public function ResFaculty()
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("pages.kegiatan_tridharma.kerjasama.faculty.res");
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function DataTablesFaculty()
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $viewFacultyInOut = new ViewFacultyInOut();

      $datatables   = new MyDatatables();
      $columnOrder  = [
        "InbOut", "Tipe", "Institusi", "Negara", "Kegiatan", "NamaVisiting", "DosenPenggerak", "Tahun"
      ];
      $columnSearch = [
        "Tipe", "NamaVisiting", "DosenPenggerak", "Tahun"
      ];

      $getList = $datatables->init_datatables($viewFacultyInOut, $columnOrder, $columnSearch);
      $data    = [];

      foreach ($getList as $list) {
        $row = [];

        $row["InbOut"]         = $list->InbOut;
        $row["Tipe"]           = $list->Tipe;
        $row["Institusi"]      = $list->Institusi;
        $row["Negara"]         = $list->Negara;
        $row["Kegiatan"]       = (empty($list->Kegiatan) ? "-" : $list->Kegiatan);
        $row["namaVisiting"]   = (empty($list->NamaVisiting) ? "-" : $list->NamaVisiting);
        $row["dosenPenggerak"] = $list->DosenPenggerak;
        $row["Tahun"]          = (empty($list->Tahun) ? "-" : "20" . substr($list->Tahun, 0, 2));

        $data[] = $row;
      }

      return $datatables->response($data);
    }
  }

  public function ChartStudent()
  {
    if (!$this->session->has("session_id")) {
      return redirect()->to("/");
    } else {
      $data["id_menu"]  = $this->mydata["id_menu"];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // VARIABEL UNTUK CHART
        $chartStudentInOut = array();

        // VARIABEL UNTUK TOTAL SI SO
        $totalStudentInOutPerTahun = [
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
        $semester = new Semester();
        $tahunTerbaruSemester = $semester->select("LEFT(kode_semester, 2) as Tahun")->groupBy("LEFT(kode_semester, 2)")->orderBy("Tahun", "DESC")->first();
        $tahunCompare = "20" . $tahunTerbaruSemester["Tahun"];
        for ($thn = ($tahunCompare - 4); $thn <= $tahunCompare; $thn++) {
          $listTahun[] = $thn;
        }

        // START PENGOLAHAN DATA CHART
        foreach ($this->mydata["student"] as $key => $item) {
          $chartStudentInOut[$i]["name"] = $item;

          $data_array = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {

            switch ($key) {
              case 1:
                $StudentInOut = new StudentInbound();
                $StudentInOutY = "YEAR(tgl_mulai)";
                break;
                
              case 2:
                $StudentInOut = new StudentOutbound();
                $StudentInOutY = "YEAR(tanggal_mulai)";
                break;

              default:
                return redirect()->to("/universitas");
                break;
            }

            $getStudentInOut = $StudentInOut->where($StudentInOutY, $listTahun[$perthn])->countAllResults();

            if (empty($getStudentInOut)) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, $getStudentInOut);
            }
          }

          $chartStudentInOut[$i]["data"] = $data_array;

          // INI UNTUK MENGHITUNG TOTAL FI FO PER TAHUN
          for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
            $totalStudentInOutPerTahun[$totalCJMP] += $data_array[$totalCJMP];
          }

          $i++;
        }
        // END PENGOLAHAN DATA CHART

        // START PENGOLAHAN DATA TAHUN CHART
        $LastFiveYears = [];
        for ($i = 0; $i <= 4; $i++) {
          $LastFiveYears[] = [
            "$listTahun[$i]"
          ];
        }
        // END PENGOLAHAN DATA TAHUN CHART

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartStudentInOut"] = json_encode($chartStudentInOut);
        $data["chartCategories"]   = json_encode($LastFiveYears);

        return $this->blade->render("pages.kegiatan_tridharma.kerjasama.student.chart", $data);
      } else {
        return redirect()->to("/universitas");
      }
    }
  }

  public function ResStudent()
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("pages.kegiatan_tridharma.kerjasama.student.res");
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function DataTablesStudent()
  {

    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $viewStudentInOut = new ViewStudentInOut();

      $datatables   = new MyDatatables();
      $columnOrder  = [
        "InbOut", "Prodi", "Nama", "Institusi", "Negara", "Tahun"
      ];
      $columnSearch = [
        "Nama", "Tahun"
      ];

      $getList = $datatables->init_datatables($viewStudentInOut, $columnOrder, $columnSearch);
      $data    = [];

      foreach ($getList as $list) {
        $row = [];

        $row["InbOut"]    = $list->InbOut;
        $row["Prodi"]     = (empty($list->Prodi) ? "-" : $list->Prodi);
        $row["Nama"]      = (empty($list->Nama) ? "-" : $list->Nama);
        $row["Institusi"] = $list->Institusi;
        $row["Negara"]    = $list->Negara;
        $row["Tahun"]     = (empty($list->Tahun) ? "-" : $list->Tahun);

        $data[] = $row;
      }

      return $datatables->response($data);
    }
  }

}
