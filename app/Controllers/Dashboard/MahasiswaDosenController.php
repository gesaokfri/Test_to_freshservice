<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;

use App\Models\Fakultas;
use App\Models\Prodi;
use App\Models\Mahasiswa;
use App\Models\MahasiswaBaru;
use App\Models\ViewProdi;

use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaFakultas;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaFakultasTa;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaFakultasStatus;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaFakultasStatusTa;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaProdi;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaProdiTa;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaProdiStatus;
use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswaProdiStatusTa;

use App\Models\Dashboard\MahasiswaDanDosen\Mahasiswa\ViewMahasiswa;

use App\Models\Dashboard\MahasiswaDanDosen\MahasiswaBaru\ViewMahasiswaBaruProdi;
use App\Models\Dashboard\MahasiswaDanDosen\MahasiswaBaru\ViewMahasiswaBaruProdiTa;

use App\Models\Dashboard\MahasiswaDanDosen\Dosen\ViewDosenJabatanAkademik;
use App\Models\Dashboard\MahasiswaDanDosen\Dosen\ViewDosenProdi;
use App\Models\Dashboard\MahasiswaDanDosen\Dosen\ViewDosenFakultas;
use App\Models\Dashboard\MahasiswaDanDosen\KapasitasIdeal;

class MahasiswaDosenController extends BaseController
{
  public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '2';
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
      13 => "#e35d6a",
    ];

    $this->mydata['status_mahasiswa'] = [
      0 => "Aktif",
      1 => "Bolos",
      2 => "Cuti"
    ];
  }

  public function Index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $fakultas      = new Fakultas();
        $mahasiswa     = new Mahasiswa();
        $mahasiswabaru = new MahasiswaBaru();

        $viewMahasiswa = new ViewMahasiswa();

        $data['fakultas'] = $fakultas->whereNotIn('KodeFakultas', ['00', '50', '99'])->findAll();
        $data['angkatan'] = $mahasiswa->select('LEFT(NIM, 4) as TahunAngkatan')->groupBy('LEFT(NIM, 4)')
        ->orderBy('TahunAngkatan', 'DESC')->findAll();
        for ($tahun=date("Y"); $tahun >= 2018; $tahun--) { 
          $data['tahun'][] = $tahun;
        }
        $data['bulan'] = ['1','2','3','4','5','6','7','8','9','10','11', '12'];

        // DEFINE TAHUN TERAKHIR MAHASISWA FROM DATABASE
        $latestYear = $viewMahasiswa->select("MAX(Tahun) as Tahun")->first();
        $data['latestYear'] = $latestYear["Tahun"];
        // DEFINE TAHUN TERAKHIR MAHASISWA FROM DATABASE

        // DEFINE BULAN TERAKHIR MAHASISWA FROM DATABASE
        for ($i = 12; $i >= 1; $i--) {
          $latestMonth = $viewMahasiswa->select("Bulan")
            ->where("Tahun", $latestYear["Tahun"])
            ->where("Bulan", $i)
            ->first();
          if (!empty($latestMonth["Bulan"])) {
            $i = 0;
            $data['latestMonth'] = $latestMonth["Bulan"];
          }
        }
        // DEFINE BULAN TERAKHIR MAHASISWA FROM DATABASE

        $getTahunTerlamaTotalMahasiswa = $mahasiswa->select('LEFT(NIM, 4) as TahunAngkatan')->groupBy('LEFT(NIM, 4)')->orderBy('TahunAngkatan', 'ASC')->first();
        $getTahunTerbaruTotalMahasiswa = $mahasiswa->select('LEFT(NIM, 4) as TahunAngkatan')->groupBy('LEFT(NIM, 4)')->orderBy('TahunAngkatan', 'DESC')->first();
        $tahunTerlamaTotalMahasiswa = $getTahunTerlamaTotalMahasiswa['TahunAngkatan'];
        $tahunTerbaruTotalMahasiswa = $getTahunTerbaruTotalMahasiswa['TahunAngkatan'];
        for ($i = $tahunTerbaruTotalMahasiswa; $i >= ($tahunTerlamaTotalMahasiswa + 4); $i--) {
          $listTahunTerakhirTotalMahasiswa[] = $i;
        }
        $data['tahunCompareTotalMahasiswa'] = $listTahunTerakhirTotalMahasiswa;
        
        $getTahunTerbaruMahasiswaBaru = $mahasiswabaru->select('LEFT(TahunSemMasuk, 2) as TahunAngkatan')->groupBy('LEFT(TahunSemMasuk, 2)')->orderBy('TahunAngkatan', 'DESC')->first();
        $tahunTerbaruMahasiswaBaru = "20" . $getTahunTerbaruMahasiswaBaru['TahunAngkatan'];
        $data['tahunTerbaruTotalMahasiswaBaru'] = $tahunTerbaruMahasiswaBaru;


        $data['status']   = $this->mydata['status_mahasiswa'];

        return $this->blade->render("pages.mahasiswa_dosen.index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function TotalMahasiswa()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $kodeFakultas   = $this->request->getPost('kode_fakultas');
        $data['status'] = $this->request->getPost('status');
        $tahunAngkatan  = $this->request->getPost('tahunangkatan');
        $Tahun          = $this->request->getPost('tahun');
        $Bulan          = $this->request->getPost('bulan');

        // buat session fakultas dan status di section total mahasiswa
        unset($_SESSION["compareTotalMahasiswa"]);
        $_SESSION["totalMahasiswa"] = [
          "totalMahasiswaFakultas"      => $kodeFakultas,
          "totalMahasiswaStatus"        => $data['status'],
          "totalMahasiswaTahunAngkatan" => $tahunAngkatan,
          "totalMahasiswaTahun"         => $Tahun,
          "totalMahasiswaBulan"         => $Bulan
        ];

        $fakultas = new Fakultas();
        $prodi    = new Prodi();

        $viewMahasiswa = new ViewMahasiswa();

        // DEFINE TAHUN TERAKHIR DATABASE
        $latestYear = $viewMahasiswa->select("MAX(Tahun) as Tahun")->first();
        // DEFINE TAHUN TERAKHIR DATABASE

        // DEFINE BULAN TERAKHIR DATABASE
        for ($i = 12; $i >= 1; $i--) {
          $latestMonth = $viewMahasiswa->select("Bulan")
            ->where("Tahun", $latestYear["Tahun"])
            ->where("Bulan", $i)
            ->first();
          if (!empty($latestMonth["Bulan"])) {
            $i = 0;
          }
        }
        // DEFINE BULAN TERAKHIR DATABASE

        // DEFINE TAHUN, BULAN
        if ($Tahun == "") {
          $Tahun = $latestYear["Tahun"]; // Output : 2022
        }
        if ($Bulan == "") {
          $Bulan = $latestMonth["Bulan"];
        }
        // DEFINE TAHUN, BULAN

        // ambil data fakultas selain KodeFakultas 00, 50, 99
        $data['fakultas'] = $fakultas->whereNotIn('KodeFakultas', ['00', '50', '99'])
        ->orderBy("KodeFakultas ASC")
        ->findAll();

        // buat array untuk mengumpulkan jumlah mahasiswa dan fakultas sebagai data card
        $dataCardMahasiswa  = [];

        // buat array untuk mengumpulkan data chart
        $chartColor                = [];
        $chartFakultas             = [];
        $chartJumlahMahasiswa      = [];
        $chartProdi                = [];
        $chartJumlahMahasiswaProdi = [];
        $chartColor                = [];

        $totalJumlahMahasiswa      = 0;
        $totalJumlahMahasiswaProdi = 0;
        $i                         = 0;

        if ($kodeFakultas == '') {
          if ($data['status'] != '') {

            if ($data["status"] == "Aktif") {
              $data["status"] = "AC";
            } elseif ($data["status"] == "Bolos") {
              $data["status"] = "DM";
            } elseif ($data["status"] == "Cuti") {
              $data["status"] = "LA";
            }

            foreach ($data["fakultas"] as $value) {
              $dataCardMahasiswa[$i]['nama_fakultas']    = $value["NamaFakultas"];
              $dataCardMahasiswa[$i]['jumlah_mahasiswa'] = 0;
              $dataCardMahasiswa[$i]['color']            = $this->mydata['color'][$i];

              if ($tahunAngkatan != "") {
                $getMahasiswa = 0;

                $getJumlahMahasiswa = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("KodeFakultasInduk", $value["KodeFakultas"])
                ->where("Status", $data["status"])
                ->countAllResults();
                if (empty($getJumlahMahasiswa)) {
                  $getJumlahMahasiswa = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Tahun)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("KodeFakultasInduk", $value["KodeFakultas"])
                  ->where("Status", $data["status"])
                  ->countAllResults();
                  $getMahasiswa += (empty($getJumlahMahasiswa) ? 0 : $getJumlahMahasiswa);
                } else {
                  $getMahasiswa += $getJumlahMahasiswa;
                }
              } elseif ($tahunAngkatan == "") {
                $getMahasiswa = 0;

                $getJumlahMahasiswa = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("KodeFakultasInduk", $value["KodeFakultas"])
                ->where("Status", $data["status"])
                ->countAllResults();
                if (empty($getJumlahMahasiswa)) {
                  $getJumlahMahasiswa = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("KodeFakultasInduk", $value["KodeFakultas"])
                  ->where("Status", $data["status"])
                  ->countAllResults();
                  $getMahasiswa += (empty($getJumlahMahasiswa) ? 0 : $getJumlahMahasiswa);
                } else {
                  $getMahasiswa += $getJumlahMahasiswa;
                }
              }

              $dataCardMahasiswa[$i]['jumlah_mahasiswa'] += (empty($getMahasiswa) ? 0 : $getMahasiswa);

              $totalJumlahMahasiswa += $dataCardMahasiswa[$i]['jumlah_mahasiswa'];

              // start chartData
              $chartColor[] = $dataCardMahasiswa[$i]['color'];
              $chartFakultas[] = $dataCardMahasiswa[$i]['nama_fakultas'];
              $chartJumlahMahasiswa[] = $dataCardMahasiswa[$i]['jumlah_mahasiswa'];
              // end chartData

              $i++;
            }

          } else {

            // foreach for cardData and chartData
            foreach ($data['fakultas'] as $item) {

              // start cardData
              $dataCardMahasiswa[$i]['nama_fakultas']    = $item["NamaFakultas"];

              if ($tahunAngkatan != "") {
                $getJumlahMahasiswaFakultas = 0;

                $getJumlahAktif    = 0;
                $getJumlahMhsAktif = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "AC")
                ->countAllResults();
                if (empty($getJumlahMhsAktif)) {
                  $getJumlahMhsAktif = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "AC")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                  $getJumlahAktif             += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsAktif;
                  $getJumlahAktif             += $getJumlahMhsAktif;
                }

                $getJumlahBolos    = 0;
                $getJumlahMhsBolos = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "DM")
                ->countAllResults();
                if (empty($getJumlahMhsBolos)) {
                  $getJumlahMhsBolos = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "DM")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                  $getJumlahBolos             += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsBolos;
                  $getJumlahBolos             += $getJumlahMhsBolos;
                }

                $getJumlahCuti    = 0;
                $getJumlahMhsCuti = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "LA")
                ->countAllResults();
                if (empty($getJumlahMhsCuti)) {
                  $getJumlahMhsCuti = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "LA")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                  $getJumlahCuti              += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsCuti;
                  $getJumlahCuti              += $getJumlahMhsCuti;
                }
              } elseif ($tahunAngkatan == "") {
                $getJumlahMahasiswaFakultas = 0;

                $getJumlahAktif    = 0;
                $getJumlahMhsAktif = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "AC")
                ->countAllResults();
                if (empty($getJumlahMhsAktif)) {
                  $getJumlahMhsAktif = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "AC")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                  $getJumlahAktif             += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsAktif;
                  $getJumlahAktif             += $getJumlahMhsAktif;
                }

                $getJumlahBolos    = 0;
                $getJumlahMhsBolos = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "DM")
                ->countAllResults();
                if (empty($getJumlahMhsBolos)) {
                  $getJumlahMhsBolos = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "DM")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                  $getJumlahBolos             += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsBolos;
                  $getJumlahBolos             += $getJumlahMhsBolos;
                }

                $getJumlahCuti    = 0;
                $getJumlahMhsCuti = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("KodeFakultasInduk", $item["KodeFakultas"])
                ->where("Status", "LA")
                ->countAllResults();
                if (empty($getJumlahMhsCuti)) {
                  $getJumlahMhsCuti = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("KodeFakultasInduk", $item["KodeFakultas"])
                  ->where("Status", "LA")
                  ->countAllResults();
                  $getJumlahMahasiswaFakultas += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                  $getJumlahCuti              += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                } else {
                  $getJumlahMahasiswaFakultas += $getJumlahMhsCuti;
                  $getJumlahCuti              += $getJumlahMhsCuti;
                }
              }

              $dataCardMahasiswa[$i]['jumlah_mahasiswa'] = (empty($getJumlahMahasiswaFakultas) ? 0 : $getJumlahMahasiswaFakultas);
              $dataCardMahasiswa[$i]['jumlah_aktif']     = (empty($getJumlahAktif) ? 0 : $getJumlahAktif);
              $dataCardMahasiswa[$i]['jumlah_bolos']     = (empty($getJumlahBolos) ? 0 : $getJumlahBolos);
              $dataCardMahasiswa[$i]['jumlah_cuti']      = (empty($getJumlahCuti) ? 0 : $getJumlahCuti);

              $dataCardMahasiswa[$i]['color']            = $this->mydata['color'][$i];
              // end cardData

              $totalJumlahMahasiswa += $dataCardMahasiswa[$i]['jumlah_mahasiswa'];

              // start chartData
              $chartColor[] = $dataCardMahasiswa[$i]['color'];
              $chartFakultas[] = $dataCardMahasiswa[$i]['nama_fakultas'];
              $chartJumlahMahasiswa[] = $dataCardMahasiswa[$i]['jumlah_mahasiswa'];
              // end chartData

              $i++;
            }
            
          }
        } else {
          if ($data['status'] != '') {

            if ($data["status"] == "Aktif") {
              $data["status"] = "AC";
            } elseif ($data["status"] == "Bolos") {
              $data["status"] = "DM";
            } elseif ($data["status"] == "Cuti") {
              $data["status"] = "LA";
            }

            $getProdi = $prodi->select("NamaProdi, KodeFakultasInduk")
            ->where("KodeFakultasInduk", $kodeFakultas)
            ->groupBy("NamaProdi, KodeFakultasInduk")
            ->orderBy("NamaProdi")
            ->findAll();

            // foreach for cardData and chartData
            foreach ($getProdi as $item) {

              // start cardData
              $dataCardMahasiswa[$i]['NamaProdi'] = $item["NamaProdi"];

              if ($tahunAngkatan != "") {
                $getJumlahMhsProdi = 0;

                $getMahasiswa = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", $data["status"])
                ->countAllResults();
                if (empty($getMahasiswa)) {
                  $getMahasiswa = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", $data["status"])
                  ->countAllResults();
                  $getJumlahMhsProdi += (empty($getMahasiswa) ? 0 : $getMahasiswa);
                } else {
                  $getJumlahMhsProdi += $getMahasiswa;
                }
              } elseif ($tahunAngkatan == "") {
                $getJumlahMhsProdi = 0;

                $getMahasiswa = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", $data["status"])
                ->countAllResults();
                if (empty($getMahasiswa)) {
                  $getMahasiswa = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", $data["status"])
                  ->countAllResults();
                  $getJumlahMhsProdi += (empty($getMahasiswa) ? 0 : $getMahasiswa);
                } else {
                  $getJumlahMhsProdi += $getMahasiswa;
                }
              }

              $dataCardMahasiswa[$i]['JmlMhs'] = (empty($getJumlahMhsProdi) ? 0 : $getJumlahMhsProdi);

              $dataCardMahasiswa[$i]['color'] = $this->mydata['color'][$i];
              // end cardData

              $totalJumlahMahasiswaProdi += $dataCardMahasiswa[$i]['JmlMhs'];

              // start chartData
              $chartColor[] = $dataCardMahasiswa[$i]['color'];
              $chartProdi[] = $dataCardMahasiswa[$i]['NamaProdi'];
              $chartJumlahMahasiswaProdi[] = $dataCardMahasiswa[$i]['JmlMhs'];
              // end chartData

              $i++;
            }
          } else {

            $getProdi = $prodi->select("NamaProdi, KodeFakultasInduk")
            ->where("KodeFakultasInduk", $kodeFakultas)
            ->groupBy("NamaProdi, KodeFakultasInduk")
            ->orderBy("NamaProdi")
            ->findAll();

            // foreach for cardData and chartData
            foreach ($getProdi as $item) {

              // start cardData
              $dataCardMahasiswa[$i]['NamaProdi'] = $item["NamaProdi"];

              if ($tahunAngkatan != "") {
                $getJumlahMahasiswaProdi = 0;

                $getJumlahAktif    = 0;
                $getJumlahMhsAktif = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "AC")
                ->countAllResults();
                if (empty($getJumlahMhsAktif)) {
                  $getJumlahMhsAktif = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "AC")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                  $getJumlahAktif          += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsAktif;
                  $getJumlahAktif          += $getJumlahMhsAktif;
                }

                $getJumlahBolos    = 0;
                $getJumlahMhsBolos = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "DM")
                ->countAllResults();
                if (empty($getJumlahMhsBolos)) {
                  $getJumlahMhsBolos = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "DM")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                  $getJumlahBolos          += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsBolos;
                  $getJumlahBolos          += $getJumlahMhsBolos;
                }

                $getJumlahCuti    = 0;
                $getJumlahMhsCuti = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "LA")
                ->countAllResults();
                if (empty($getJumlahMhsCuti)) {
                  $getJumlahMhsCuti = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2))
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "LA")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                  $getJumlahCuti           += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsCuti;
                  $getJumlahCuti           += $getJumlahMhsCuti;
                }
              } elseif ($tahunAngkatan == "") {
                $getJumlahMahasiswaProdi = 0;

                $getJumlahAktif    = 0;
                $getJumlahMhsAktif = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "AC")
                ->countAllResults();
                if (empty($getJumlahMhsAktif)) {
                  $getJumlahMhsAktif = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "AC")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                  $getJumlahAktif          += (empty($getJumlahMhsAktif) ? 0 : $getJumlahMhsAktif);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsAktif;
                  $getJumlahAktif          += $getJumlahMhsAktif;
                }

                $getJumlahBolos    = 0;
                $getJumlahMhsBolos = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "DM")
                ->countAllResults();
                if (empty($getJumlahMhsBolos)) {
                  $getJumlahMhsBolos = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "DM")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                  $getJumlahBolos          += (empty($getJumlahMhsBolos) ? 0 : $getJumlahMhsBolos);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsBolos;
                  $getJumlahBolos          += $getJumlahMhsBolos;
                }

                $getJumlahCuti    = 0;
                $getJumlahMhsCuti = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                ->where("Status", "LA")
                ->countAllResults();
                if (empty($getJumlahMhsCuti)) {
                  $getJumlahMhsCuti = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"])
                  ->where("Status", "LA")
                  ->countAllResults();
                  $getJumlahMahasiswaProdi += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                  $getJumlahCuti           += (empty($getJumlahMhsCuti) ? 0 : $getJumlahMhsCuti);
                } else {
                  $getJumlahMahasiswaProdi += $getJumlahMhsCuti;
                  $getJumlahCuti           += $getJumlahMhsCuti;
                }
              }

              $dataCardMahasiswa[$i]['JmlMhs']       = (empty($getJumlahMahasiswaProdi) ? 0 : $getJumlahMahasiswaProdi);
              $dataCardMahasiswa[$i]['jumlah_aktif'] = (empty($getJumlahAktif) ? 0 : $getJumlahAktif);
              $dataCardMahasiswa[$i]['jumlah_bolos'] = (empty($getJumlahBolos) ? 0 : $getJumlahBolos);
              $dataCardMahasiswa[$i]['jumlah_cuti']  = (empty($getJumlahCuti) ? 0 : $getJumlahCuti);

              $dataCardMahasiswa[$i]['color']            = $this->mydata['color'][$i];
              // end cardData

              $totalJumlahMahasiswaProdi += $dataCardMahasiswa[$i]['JmlMhs'];

              // start chartData
              $chartColor[] = $dataCardMahasiswa[$i]['color'];
              $chartProdi[] = $dataCardMahasiswa[$i]['NamaProdi'];
              $chartJumlahMahasiswaProdi[] = $dataCardMahasiswa[$i]['JmlMhs'];
              // end chartData

              $i++;

            }

          }
        }

        $data['totalJumlahMahasiswa']      = $totalJumlahMahasiswa;
        $data['totalJumlahMahasiswaProdi'] = $totalJumlahMahasiswaProdi;

        $data['dataCardMahasiswa']         = $dataCardMahasiswa;

        $data['namaFakultas']         = json_encode($chartFakultas);
        $data['jumlahMahasiswa']      = json_encode($chartJumlahMahasiswa);
        $data['namaProdi']            = json_encode($chartProdi);
        $data['jumlahMahasiswaProdi'] = json_encode($chartJumlahMahasiswaProdi);
        $data['color']                = json_encode($chartColor);

        if ($kodeFakultas == "") {

          if ($data['status'] != "") {
            return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.total_mahasiswa_status", $data);
          } else {
            return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.total_mahasiswa", $data);
          }
        } else {

          if ($data['status'] != "") {
            return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.total_mahasiswa_prodi_status", $data);
          } else {
            return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.total_mahasiswa_prodi", $data);
          }
        }
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function TotalMahasiswaBaru()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas  = $this->request->getPost('kode_fakultas');
      $tahunAngkatan = $this->request->getPost('tahunangkatan');

      $fakultas               = new Fakultas();
      $mahasiswabaru          = new MahasiswaBaru();
      $viewMahasiswaBaruProdi = new ViewMahasiswaBaruProdiTa();

      // buat session fakultas dan status di section total mahasiswa
      unset($_SESSION["compareTotalMahasiswaBaru"]);
      $_SESSION["totalMahasiswaBaru"] = [
        "totalMahasiswaBaruFakultas" => $kodeFakultas,
        "totalMahasiswaBaruAngkatan" => $tahunAngkatan,
      ];

      // GET TAHUN TERBARU
      $getTahunTerbaru = $mahasiswabaru->select('LEFT(TahunSemMasuk, 2) as TahunAngkatan')->groupBy('LEFT(TahunSemMasuk, 2)')->orderBy('TahunAngkatan', 'DESC')->first();
      $tahunTerbaru = "20" . $getTahunTerbaru['TahunAngkatan'];

      if ($tahunAngkatan == "") {
        $tahunAngkatan = $tahunTerbaru;
      }
      // GET TAHUN TERBARU

      // ambil data fakultas selain KodeFakultas 00, 50, 99
      $data['fakultas'] = $fakultas->whereNotIn('KodeFakultas', ['00', '50', '99'])->findAll();

      // buat array untuk mengumpulkan jumlah mahasiswa dan fakultas sebagai data card
      $dataCardMahasiswaBaru = [];

      // buat array untuk mengumpulkan data chart
      $chartColor               = [];
      $chartFakultas            = [];
      $chartJumlahMahasiswaBaru = [];

      $totalJumlahMahasiswaBaru            = 0;
      $totalJumlahMahasiswaBaruProdi       = 0;
      $totalJumlahTargetMahasiswaBaru      = 0;
      $totalJumlahTargetMahasiswaBaruProdi = 0;
      $i                                   = 0;

      if ($kodeFakultas == "") {
        // foreach for cardData and chartData
        foreach ($data['fakultas'] as $item) {

          // start cardData
          $dataCardMahasiswaBaru[$i]['nama_fakultas'] = $item['NamaFakultas'];

          $dataCardMahasiswaBaru[$i]['jumlah_mahasiswa_sum'] = $mahasiswabaru
          ->select('sum(JmlKonfirmasi) as total')
          ->join('StagingData.dbo.Prodi as prodi', 'prodi.KodeFakultas = LEFT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2) AND prodi.KodeProdi = RIGHT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2)')
          ->where("prodi.KodeFakultasInduk", $item['KodeFakultas'])
          ->where("LEFT(TahunSemMasuk, 2)", substr($tahunAngkatan, -2))
          ->first();

          $dataCardMahasiswaBaru[$i]['jumlah_mahasiswa']    = $dataCardMahasiswaBaru[$i]['jumlah_mahasiswa_sum']['total'];

          $dataCardMahasiswaBaru[$i]['jumlah_target_sum'] = $mahasiswabaru
          ->select('sum(TargetKonfirmasi) as total')
          ->join('StagingData.dbo.Prodi as prodi', 'prodi.KodeFakultas = LEFT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2) AND prodi.KodeProdi = RIGHT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2)')
          ->where("prodi.KodeFakultasInduk", $item['KodeFakultas'])
          ->where("LEFT(TahunSemMasuk, 2)", substr($tahunAngkatan, -2))
          ->first();
  

          $dataCardMahasiswaBaru[$i]['jumlah_target_mahasiswa_baru'] = $dataCardMahasiswaBaru[$i]['jumlah_target_sum']['total'];

          $dataCardMahasiswaBaru[$i]['color'] = $this->mydata['color'][$i];
          // end cardData

          $totalJumlahMahasiswaBaru       += $dataCardMahasiswaBaru[$i]['jumlah_mahasiswa'];
          $totalJumlahTargetMahasiswaBaru += $dataCardMahasiswaBaru[$i]['jumlah_target_mahasiswa_baru'];

          // start chartData
          $chartColor[] = $dataCardMahasiswaBaru[$i]['color'];
          $chartFakultas[] = $dataCardMahasiswaBaru[$i]['nama_fakultas'];
          $chartJumlahMahasiswaBaru[] = $dataCardMahasiswaBaru[$i]['jumlah_mahasiswa'];
          // end chartData

          $i++;
        }
      } else {
        $getMahasiswaBaruProdi = $viewMahasiswaBaruProdi
        ->where('KodeFakultas', $kodeFakultas)
        ->where('TahunAngkatan', substr($tahunAngkatan, -2))
        ->findAll();

        foreach ($getMahasiswaBaruProdi as $item) {
          // isi array untuk dipakai di data card
          $dataCardMahasiswaBaru[$i]['color']         = $this->mydata['color'][$i];
          $dataCardMahasiswaBaru[$i]['NamaProdi']     = $item['NamaProdi'];
          $dataCardMahasiswaBaru[$i]['JmlKonfirmasi'] = $item['JmlKonfirmasi'];

          $totalJumlahMahasiswaBaruProdi       += $item['JmlKonfirmasi'];
          $totalJumlahTargetMahasiswaBaruProdi += $item['TargetKonfirmasi'];

          // isi array untuk dipakai di data chart
          $chartColor[] = $this->mydata['color'][$i];
          $chartJumlahMahasiswaBaruProdi[] = $item['JmlKonfirmasi'];
          $chartProdi[] = $item['NamaProdi'];

          $i++;
        }

        $data['namaProdi']                = json_encode($chartProdi);
        $data['jumlahMahasiswaBaruProdi'] = json_encode($chartJumlahMahasiswaBaruProdi);
      }

      $data['lblFakultas']                         = $fakultas->select("NamaFakultas")->where('KodeFakultas', $kodeFakultas)->first();
      $data['totalJumlahMahasiswaBaru']            = $totalJumlahMahasiswaBaru;
      $data['totalJumlahMahasiswaBaruProdi']       = $totalJumlahMahasiswaBaruProdi;

      $data['totalJumlahTargetMahasiswaBaru']      = $totalJumlahTargetMahasiswaBaru;
      $data['totalJumlahTargetMahasiswaBaruProdi'] = $totalJumlahTargetMahasiswaBaruProdi;

      $data['dataCardMahasiswaBaru'] = $dataCardMahasiswaBaru;

      $data['color']               = json_encode($chartColor);
      $data['namaFakultas']        = json_encode($chartFakultas);
      $data['jumlahMahasiswaBaru'] = json_encode($chartJumlahMahasiswaBaru);

      if ($kodeFakultas == "") {
        return $this->blade->render("pages.mahasiswa_dosen.mahasiswa_baru.total_mahasiswa_baru", $data);
      } else {
        return $this->blade->render("pages.mahasiswa_dosen.mahasiswa_baru.total_mahasiswa_baru_prodi", $data);
      }
    }
    
  }

  public function TotalDosen() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas    = $this->request->getPost('fakultas');
      $jabatanAkademik = $this->request->getPost('jabatanAkademik');

      // buat session fakultas dan jabatan di section total dosen
      unset($_SESSION["compareTotalDosen"]);
      $_SESSION["totalDosen"] = [
        "totalDosenFakultas"        => $kodeFakultas,
        "totalDosenJabatanAkademik" => $jabatanAkademik,
      ];

      $fakultas          = new Fakultas();
      $prodi             = new ViewProdi();
      $viewDosenFakultas = new ViewDosenFakultas();
      $viewDosenProdi    = new ViewDosenProdi();

      // buat array untuk mengumpulkan jumlah dosen sebagai data card
      $dataCardDosen = [];

      // buat array untuk mengumpulkan data chart
      $chartColor           = [];
      $chartJabatanAkademik = [];
      $chartJumlahDosen     = [];
      $chartNamaFakultas    = [];

      $totalJumlahDosen      = 0;
      $i                     = 0;

      if ($jabatanAkademik == "") {

        if ($kodeFakultas == "") {          

          // ambil data fakultas selain KodeFakultas 00, 50, 99
          $data['fakultas'] = $fakultas->whereNotIn('KodeFakultas', ['00', '50', '99'])->findAll();

          foreach ($data['fakultas'] as $item) {

            // start cardData
            $dataCardDosen[$i]['color'] = $this->mydata['color'][$i];

            $getDosenFakultas = $viewDosenFakultas->where(['KodeFakultas' => $item['KodeFakultas']])->first();

            if (empty($getDosenFakultas)) {
              $dataCardDosen[$i]['jumlah_dosen'] = 0;
            } else {
              $dataCardDosen[$i]['jumlah_dosen'] = $getDosenFakultas["JumlahDosen"];
            }

            $dataCardDosen[$i]['nama_fakultas'] = $item['NamaFakultas'];
            // end cardData

            $totalJumlahDosen += $dataCardDosen[$i]['jumlah_dosen'];

            // start chartData
            $chartColor[] = $dataCardDosen[$i]['color'];
            $chartJumlahDosen[] = $dataCardDosen[$i]['jumlah_dosen'];
            $chartNamaFakultas[] = $dataCardDosen[$i]['nama_fakultas'];
            // end chartData

            $i++;
          }

          $data['namaFakultas'] = json_encode($chartNamaFakultas);

        } else {

          // AMBIL DATA PRODI
          $getNamaProdi = $prodi->select("NamaProdi")->where("KodeFakultasInduk", $kodeFakultas)->groupBy("NamaProdi")->orderBy("NamaProdi", "ASC")->findAll();

          foreach ($getNamaProdi as $item) {

            // START CARD DOSEN
            $dataCardDosen[$i]["color"] = $this->mydata["color"][$i];

            $getDosenProdi = $viewDosenProdi->where(["NamaProdi" => $item["NamaProdi"]])->first();

            if (empty($getDosenProdi)) {
              $dataCardDosen[$i]['jumlah_dosen'] = 0;
            } else {
              $dataCardDosen[$i]['jumlah_dosen'] = $getDosenProdi["JumlahDosen"];
            }

            $dataCardDosen[$i]['nama_prodi'] = $item['NamaProdi'];
            // end cardData

            $totalJumlahDosen += $dataCardDosen[$i]['jumlah_dosen'];

            // start chartData
            $chartColor[] = $dataCardDosen[$i]['color'];
            $chartJumlahDosen[] = $dataCardDosen[$i]['jumlah_dosen'];
            $chartNamaProdi[] = $dataCardDosen[$i]['nama_prodi'];
            // end chartData

            $i++;
          }

          $data['namaProdi'] = json_encode($chartNamaProdi);

        }

      } else {

        $DosenJabAkademik = new ViewDosenJabatanAkademik();

        $getNamaJabAkademik = $DosenJabAkademik->orderBy("JabatanAkademik")->findAll();

        foreach ($getNamaJabAkademik as $value) {

          // Start cardData
          $dataCardDosen[$i]['color'] = $this->mydata['color'][$i];

          $dataCardDosen[$i]['nama_jabatan'] = $value["JabatanAkademik"];
          
          $dataCardDosen[$i]['jumlah_dosen'] = $value["JumlahDosen"];
          // End cardData

          $totalJumlahDosen += $dataCardDosen[$i]['jumlah_dosen'];

          // Start chartData
          $chartColor[] = $dataCardDosen[$i]['color'];
          $chartJumlahDosen[] = $dataCardDosen[$i]['jumlah_dosen'];
          $chartJabatanAkademik[] = $dataCardDosen[$i]['nama_jabatan'];
          // End chartData

          $i++;
        }

        $data['namaJabatan'] = json_encode($chartJabatanAkademik);
      }

      $data['totalJumlahDosen'] = $totalJumlahDosen;

      $data['dataCardDosen'] = $dataCardDosen;

      $data['color']       = json_encode($chartColor);
      $data['jumlahDosen'] = json_encode($chartJumlahDosen);

      if ($jabatanAkademik == "") {
        if ($kodeFakultas == "") {
          return $this->blade->render('pages.mahasiswa_dosen.dosen.total_dosen_fakultas', $data);
        } else {
          return $this->blade->render('pages.mahasiswa_dosen.dosen.total_dosen_prodi', $data);
        }
      } else {
        return $this->blade->render('pages.mahasiswa_dosen.dosen.total_dosen_jabatan_akademik', $data);
      } 
    }
  }

  public function RasioDosenMahasiswa() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas  = $this->request->getPost('kode_fakultas');
      $tahunAngkatan = $this->request->getPost('tahunAngkatan');
      $Tahun         = $this->request->getPost('tahun');
      $Bulan         = $this->request->getPost('bulan');

      // buat session fakultas di section rasio dosen mahasiswa
      unset($_SESSION["compareRasioDosenMahasiswa"]);
      $_SESSION["rasioDosenMahasiswa"] = [
        "rasioDosenMahasiswaFakultas"      => $kodeFakultas,
        "rasioDosenMahasiswaTahunAngkatan" => $tahunAngkatan,
        "rasioDosenMahasiswaTahun"         => $Tahun,
        "rasioDosenMahasiswaBulan"         => $Bulan,
      ];

      $viewMahasiswaFakultas   = new ViewMahasiswaFakultas();
      $viewMahasiswaFakultasTa = new ViewMahasiswaFakultasTa();
      $viewMahasiswaProdi      = new ViewMahasiswaProdi();
      $viewMahasiswaProdiTa    = new ViewMahasiswaProdiTa();
      $viewDosenFakultas       = new ViewDosenFakultas();
      $viewDosenProdi          = new ViewDosenProdi();

      $viewMahasiswa = new ViewMahasiswa();

      // DEFINE TAHUN TERAKHIR DATABASE
      $latestYear = $viewMahasiswa->select("MAX(Tahun) as Tahun")->first();
      // DEFINE TAHUN TERAKHIR DATABASE

      // DEFINE BULAN TERAKHIR DATABASE
      for ($i = 12; $i >= 1; $i--) {
        $latestMonth = $viewMahasiswa->select("Bulan")
          ->where("Tahun", $latestYear["Tahun"])
          ->where("Bulan", $i)
          ->first();
        if (!empty($latestMonth["Bulan"])) {
          $i = 0;
        }
      }
      // DEFINE BULAN TERAKHIR DATABASE

      // DEFINE TAHUN, BULAN
      if ($Tahun == "") {
        $Tahun = $latestYear["Tahun"]; // Output : 2022
      }
      if ($Bulan == "") {
        $Bulan = $latestMonth["Bulan"];
      }
      // DEFINE TAHUN, BULAN

      // buat array untuk mengumpulkan jumlah dosen dan jumlah mahasiswa sebagai data
      $dataRasioDosenMahasiswa = [];

      $i = 0;

      if ($kodeFakultas != "") {

        $getNamaProdi = $viewMahasiswaProdi
        ->select("KodeFakultas, NamaProdi, Kapasitas")
        ->where("KodeFakultas", $kodeFakultas)
        ->groupBy("KodeFakultas, NamaProdi, Kapasitas")
        ->orderBy("KodeFakultas", "ASC")
        ->findAll();

        // foreach for data rasio dosen terhadap mahasiswa/prodi
        foreach ($getNamaProdi as $item) {
          $dataColor[] = $this->mydata['color'][$i];
          // start Jumlah Dosen
          $getJumlahDosen = $viewDosenProdi->select("JumlahDosen")->where(["KodeFakultasInduk" => $item["KodeFakultas"], "NamaProdi" => $item["NamaProdi"]])->first();
          if (empty($getJumlahDosen)) {
            $jumlahDosen = 0;
          } else {
            $jumlahDosen = $getJumlahDosen["JumlahDosen"];
          }
          $dataRasioDosenMahasiswa[$i]['jumlah_dosen'] = $jumlahDosen;
          // end Jumlah Dosen

          // start Jumlah Mahasiswa
          $getJumlahMahasiswa = $viewMahasiswa
          ->select("StudentID")
          ->distinct("StudentID")
          ->where("Tahun", $Tahun)
          ->where("Bulan", $Bulan)
          ->where("KodeFakultasInduk", $item["KodeFakultas"])
          ->where("NamaProdi", $item["NamaProdi"]);
          if ($tahunAngkatan != "") {
            $getJumlahMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2));
          }
          $total = $getJumlahMahasiswa->countAllResults();
          if (empty($total)) {
            $getJumlahMahasiswa = $viewMahasiswa
            ->select("StudentID")
            ->distinct("StudentID")
            ->where("Tahun", $Tahun)
            ->where("Bulan", $Bulan)
            ->where("KodeFakultasInduk", $item["KodeFakultas"])
            ->where("NamaProdi", $item["NamaProdi"]);
            if ($tahunAngkatan != "") {
              $getJumlahMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2));
            }
            $total = $getJumlahMahasiswa->countAllResults();
            if (empty($total)) {
              $jumlahMahasiswa = 0;
            } else {
              $jumlahMahasiswa = $total;
            }
          } else {
            $jumlahMahasiswa = $total;
          }
          $dataRasioDosenMahasiswa[$i]['jumlah_mahasiswa'] = $jumlahMahasiswa;
          // end Jumlah Mahasiswa

          $dataRasioDosenMahasiswa[$i]['nama_prodi'] = $item['NamaProdi'];

          if (!empty($jumlahDosen)) {
            $dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] = $dataRasioDosenMahasiswa[$i]['jumlah_mahasiswa'] / $dataRasioDosenMahasiswa[$i]['jumlah_dosen'];

            $rasio = ($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] / ($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] + 1)) * 100;

            $dataRasioDosenMahasiswa[$i]['persenMahasiswa'] = ceil($rasio);

            $dataRasioDosenMahasiswa[$i]['persenDosen'] = 100 - $dataRasioDosenMahasiswa[$i]['persenMahasiswa'];
          } else {
            $dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] = 0;
            $dataRasioDosenMahasiswa[$i]['persenDosen']    = 0;
          }

          $data['rasio'][] = round($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'], 1);
          $data['prodi'][] = $dataRasioDosenMahasiswa[$i]['nama_prodi'];

          $dataTable[$i]['kapasitas'] = $item['Kapasitas'];
          $dataTable[$i]['prodi'] = $item['NamaProdi'];
          $dataTable[$i]['rasio'] = round($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'], 1);

          $i++;
          // end dataRasioDosenMahasiswa
        }

        $data['dataProdi']               = json_encode($data['prodi']);

      } else {

        $getNamaFakultas = $viewMahasiswaFakultas
        ->select("KodeFakultas, NamaFakultas, Kapasitas")
        ->groupBy("KodeFakultas, NamaFakultas, Kapasitas")
        ->orderBy("KodeFakultas", "ASC")
        ->findAll();

        // foreach for data rasio dosen terhadap mahasiswa/fakultas
        foreach ($getNamaFakultas as $item) {
          
          $dataColor[] = $this->mydata['color'][$i];
          // start Jumlah Dosen
          $getJumlahDosen = $viewDosenFakultas->select("JumlahDosen")->where("KodeFakultas", $item["KodeFakultas"])->first();
          if (empty($getJumlahDosen)) {
            $jumlahDosen = 0;
          } else {
            $jumlahDosen = $getJumlahDosen["JumlahDosen"];
          }
          $dataRasioDosenMahasiswa[$i]['jumlah_dosen'] = $jumlahDosen;
          // end Jumlah Dosen

          // start Jumlah Mahasiswa
          $getJumlahMahasiswa = $viewMahasiswa
          ->select("StudentID")
          ->distinct("StudentID")
          ->where("Tahun", $Tahun)
          ->where("Bulan", $Bulan)
          ->where("KodeFakultasInduk", $item["KodeFakultas"]);
          if ($tahunAngkatan != "") {
            $getJumlahMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2));
          }
          $total = $getJumlahMahasiswa->countAllResults();
          if (empty($total)) {
            $getJumlahMahasiswa = $viewMahasiswa
            ->select("StudentID")
            ->distinct("StudentID")
            ->where("Tahun", $Tahun)
            ->where("Bulan", $Bulan)
            ->where("KodeFakultasInduk", $item["KodeFakultas"]);
            if ($tahunAngkatan != "") {
              $getJumlahMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($tahunAngkatan, -2));
            }
            $total = $getJumlahMahasiswa->countAllResults();
            if (empty($total)) {
              $jumlahMahasiswa = 0;
            } else {
              $jumlahMahasiswa = $total;
            }
          } else {
            $jumlahMahasiswa = $total;
          }
          $dataRasioDosenMahasiswa[$i]['jumlah_mahasiswa'] = $jumlahMahasiswa;
          // end Jumlah Mahasiswa

          $dataRasioDosenMahasiswa[$i]['nama_fakultas'] = $item['NamaFakultas'];

          if (!empty($jumlahDosen)) {
            $dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] = $dataRasioDosenMahasiswa[$i]['jumlah_mahasiswa'] / $dataRasioDosenMahasiswa[$i]['jumlah_dosen'];

            $rasio = ($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] / ($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] + 1)) * 100;

            $dataRasioDosenMahasiswa[$i]['persenMahasiswa'] = ceil($rasio);

            $dataRasioDosenMahasiswa[$i]['persenDosen'] = 100 - $dataRasioDosenMahasiswa[$i]['persenMahasiswa'];
          } else {
            $dataRasioDosenMahasiswa[$i]['rasioMahasiswa'] = 0;
            $dataRasioDosenMahasiswa[$i]['persenDosen']    = 0;
          }
          $data['rasio'][] = round($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'], 1);
          $data['fakultas'][] = $dataRasioDosenMahasiswa[$i]['nama_fakultas'];
          
          $dataTable[$i]['kapasitas'] = $item['Kapasitas'];
          $dataTable[$i]['fakultas'] = $item['NamaFakultas'];
          $dataTable[$i]['rasio'] = round($dataRasioDosenMahasiswa[$i]['rasioMahasiswa'], 1);
          
          $i++;
        }
        $data['dataFakultas']            = json_encode($data['fakultas']);
      }


      $data['dataRasioTable']          = $dataTable;
      $data['dataRasio']               = json_encode($data['rasio']);
      $data["dataColor"]               = json_encode($dataColor);
      $data['dataRasioDosenMahasiswa'] = $dataRasioDosenMahasiswa;

      if ($kodeFakultas != "") {
        return $this->blade->render("pages.mahasiswa_dosen.rasio_dosen_mahasiswa.prodi", $data);
      } else {
        return $this->blade->render("pages.mahasiswa_dosen.rasio_dosen_mahasiswa.fakultas", $data);
      }
    }
  }

  public function CompareTotalMahasiswa() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      // GET KODE FAKULTAS SELECTED AND TAHUN TERAKHIR SELECTED
      $kodeFakultas    = $this->request->getPost("fakultascompare");
      $prodiSelected   = $this->request->getPost("prodicompare");
      $angkatanCompare = $this->request->getPost("angkatancompare");
      $Tahun           = $this->request->getPost("tahuncompare");
      $Bulan           = $this->request->getPost("bulancompare");

      // SET SESSION
      unset($_SESSION["totalMahasiswa"]);
      $_SESSION["compareTotalMahasiswa"] = [
        "compareTotalMahasiswaFakultas"      => $kodeFakultas,
        "compareTotalMahasiswaProdi"         => $prodiSelected,
        "compareTotalMahasiswaTahunAngkatan" => $angkatanCompare,
        "compareTotalMahasiswaTahun"         => $Tahun,
        "compareTotalMahasiswaBulan"         => $Bulan
      ];

      // PANGGIL MODEL
      $viewMahasiswaFakultas   = new ViewMahasiswaFakultas();
      $viewMahasiswaFakultasTa = new ViewMahasiswaFakultasTa();
      $viewMahasiswaProdi      = new ViewMahasiswaProdi();
      $viewMahasiswaProdiTa    = new ViewMahasiswaProdiTa();

      $fakultas = new Fakultas();
      $prodi    = new Prodi();

      $viewMahasiswa = new ViewMahasiswa();

      // DEFINE TAHUN TERAKHIR DATABASE
      $latestYear = $viewMahasiswa->select("MAX(Tahun) as Tahun")->first();
      // DEFINE TAHUN TERAKHIR DATABASE

      // DEFINE BULAN TERAKHIR DATABASE
      for ($i = 12; $i >= 1; $i--) {
        $latestMonth = $viewMahasiswa->select("Bulan")
        ->where("Tahun", $latestYear["Tahun"])
        ->where("Bulan", $i)
        ->first();
        if (!empty($latestMonth["Bulan"])) {
          $i = 0;
        }
      }
      // DEFINE BULAN TERAKHIR DATABASE

      // DEFINE TAHUN, BULAN
      if ($Tahun == "") {
        $Tahun = $latestYear["Tahun"]; // Output : 2022
      }
      if ($Bulan == "") {
        $Bulan = $latestMonth["Bulan"];
      }
      // DEFINE TAHUN, BULAN

      // ambil data fakultas selain KodeFakultas 00, 50, 99
      $Fakultas = $fakultas->select("KodeFakultas, NamaFakultas")
      ->whereNotIn('KodeFakultas', ['00', '50', '99'])
      ->groupBy("KodeFakultas, NamaFakultas")
      ->orderBy("KodeFakultas ASC")
      ->findAll();

      // VARIABEL UNTUK CHART
      $chartColor                  = array();
      $chartCompareJumlahMahasiswa = array();

      // VARIABEL UNTUK TABLE
      $dataTableCompareJumlahMahasiswa = array();

      // VARIABEL UNTUK TABLE TOTAL MAHASISWA
      $totalCompareJumlahMahasiswa              = 0;
      $totalCompareJumlahMahasiswaProdi         = 0;
      $totalCompareJumlahMahasiswaPerTahun      = [
        0 => 0,
        1 => 0,
        2 => 0,
        3 => 0,
        4 => 0
      ];

      // VARIABEL UNTUK INDEXING
      $i = 0;

      // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
      $listTahun = [];
      for ($thn=($Tahun - 4); $thn <= $Tahun; $thn++) { 
        $listTahun[] = $thn;
      }

      // KALAU KODE FAKULTAS KOSONG JALANKAN
      if ($kodeFakultas == "") {

        foreach ($Fakultas as $item) {
          $chartColor[] = $this->mydata["color"][$i];
          $chartCompareJumlahMahasiswa[$i]["name"] = $item["NamaFakultas"];

          $data_array = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {
            $getMahasiswaFakultas = $viewMahasiswa
            ->select("StudentID")
            ->distinct("StudentID")
            ->where("Tahun", $listTahun[$perthn])
            ->where("Bulan", $Bulan)
            ->where("KodeFakultasInduk", $item["KodeFakultas"]);
            if ($angkatanCompare != "") {
              $getMahasiswaFakultas->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
            }
            $total = $getMahasiswaFakultas->countAllResults();
            if (empty($total)) {
              if ($listTahun[$perthn] == $latestYear["Tahun"]) {
                $getMahasiswaFakultas = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $Tahun)
                ->where("Bulan", $Bulan)
                ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                if ($angkatanCompare != "") {
                  $getMahasiswaFakultas->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                }
                $total = $getMahasiswaFakultas->countAllResults();
                if (empty($total)) {
                  array_push($data_array, 0);
                } else {
                  array_push($data_array, $total);
                }
              } else {
                array_push($data_array, 0);
              }
            } else {
              array_push($data_array, $total);
            }
          }

          $chartCompareJumlahMahasiswa[$i]["data"] = $data_array;

          $dataTableCompareJumlahMahasiswa[$i]["nama_fakultas"]    = $item["NamaFakultas"];
          $dataTableCompareJumlahMahasiswa[$i]["jumlah_mahasiswa"] = $data_array;

          // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
          for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
            $totalCompareJumlahMahasiswa += $data_array[$totalCJM];

            $totalCompareJumlahMahasiswaPerTahun[$totalCJM] += $data_array[$totalCJM];
          }

          $i++;
        }

      } else {

        if ($prodiSelected == "") {
          $getNamaProdi = $prodi->select("NamaProdi, KodeFakultasInduk")
          ->where("KodeFakultasInduk", $kodeFakultas)
          ->groupBy("NamaProdi, KodeFakultasInduk")
          ->orderBy("NamaProdi")
          ->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata['color'][$i];

            $chartCompareJumlahMahasiswa[$i]['name'] = $item['NamaProdi'];

            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getMahasiswaProdi = $viewMahasiswa
              ->select("StudentID")
              ->distinct("StudentID")
              ->where("Tahun", $listTahun[$perthn])
              ->where("Bulan", $Bulan)
              ->where("NamaProdi", $item["NamaProdi"])
              ->where("KodeFakultasInduk", $item["KodeFakultasInduk"]);
              if ($angkatanCompare != "") {
                $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
              }
              $total = $getMahasiswaProdi->countAllResults();
              if (empty($total)) {
                if ($listTahun[$perthn] == $latestYear["Tahun"]) {
                  $getMahasiswaProdi = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"]);
                  if ($angkatanCompare != "") {
                    $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                  }
                  $total = $getMahasiswaProdi->countAllResults();
                  if (empty($total)) {
                    array_push($data_array, 0);
                  } else {
                    array_push($data_array, $total);
                  }
                } else {
                  array_push($data_array, 0);
                }
              } else {
                array_push($data_array, $total);
              }
            }

            $chartCompareJumlahMahasiswa[$i]['data'] = $data_array;

            $dataTableCompareJumlahMahasiswa[$i]["nama_prodi"]       = $item["NamaProdi"];
            $dataTableCompareJumlahMahasiswa[$i]["jumlah_mahasiswa"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalCompareJumlahMahasiswaProdi += $data_array[$totalCJMP];

              $totalCompareJumlahMahasiswaPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }
        } else {
          $getNamaProdi = $prodi->select("NamaProdi, KodeFakultasInduk")
          ->where("KodeFakultasInduk", $kodeFakultas)
          ->where("NamaProdi", $prodiSelected)
          ->groupBy("NamaProdi, KodeFakultasInduk")
          ->orderBy("NamaProdi")
          ->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata['color'][$i];
            $chartCompareJumlahMahasiswa[$i]['name'] = $item['NamaProdi'];
            
            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getMahasiswaProdi = $viewMahasiswa
              ->select("StudentID")
              ->distinct("StudentID")
              ->where("Tahun", $listTahun[$perthn])
              ->where("Bulan", $Bulan)
              ->where("NamaProdi", $item["NamaProdi"])
              ->where("KodeFakultasInduk", $item["KodeFakultasInduk"]);
              if ($angkatanCompare != "") {
                $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
              }
              $total = $getMahasiswaProdi->countAllResults();
              if (empty($total)) {
                if ($listTahun[$perthn] == $latestYear["Tahun"]) {
                  $getMahasiswaProdi = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("NamaProdi", $item["NamaProdi"])
                  ->where("KodeFakultasInduk", $item["KodeFakultasInduk"]);
                  if ($angkatanCompare != "") {
                    $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                  }
                  $total = $getMahasiswaProdi->countAllResults();
                  if (empty($total)) {
                    array_push($data_array, 0);
                  } else {
                    array_push($data_array, $total);
                  }
                } else {
                  array_push($data_array, 0);
                }
              } else {
                array_push($data_array, $total);
              }
            }

            $chartCompareJumlahMahasiswa[$i]['data'] = $data_array;

            $dataTableCompareJumlahMahasiswa[$i]["nama_prodi"]       = $item["NamaProdi"];
            $dataTableCompareJumlahMahasiswa[$i]["jumlah_mahasiswa"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalCompareJumlahMahasiswaProdi += $data_array[$totalCJMP];

              $totalCompareJumlahMahasiswaPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }

        }
      }

      $LastFiveYearsProdiSelected = [];
      foreach ($listTahun as $value) {
        array_push($LastFiveYearsProdiSelected, "$value");
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["ChartCompareJumlahMahasiswa"] = json_encode($chartCompareJumlahMahasiswa);
      $data["ChartColor"]                  = json_encode($chartColor);
      $data["LastFiveYears"]               = json_encode($listTahun);
      $data["LastFiveYearsProdiSelected"]  = json_encode($LastFiveYearsProdiSelected);

      // DATA YANG AKAN DIKIRIM KE TABLE
      $data["TableCompareJumlahMahasiswa"] = $dataTableCompareJumlahMahasiswa;
      $data["TableListTahun"]              = $listTahun;
      $data["TableTotalKeseluruhan"]       = $totalCompareJumlahMahasiswa;
      $data["TableTotalKeseluruhanProdi"]  = $totalCompareJumlahMahasiswaProdi;
      $data["TableTotalPerTahun"]          = $totalCompareJumlahMahasiswaPerTahun;

      if ($kodeFakultas == "") {
        return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.compare", $data);
      } else {
        if ($prodiSelected == "") {
          return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.compare_prodi", $data);
        } else {
          return $this->blade->render("pages.mahasiswa_dosen.mahasiswa.compare_prodi_selected", $data);
        }
      }
    }
  }

  public function CompareTotalMahasiswaBaru()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      // GET KODE FAKULTAS SELECTED AND TAHUN TERAKHIR SELECTED
      $kodeFakultas = $this->request->getPost("fakultascompare");
      $prodi        = $this->request->getPost("prodicompare");
      $tahunCompare = $this->request->getPost("tahuncompare");

      // SET SESSION
      unset($_SESSION["totalMahasiswaBaru"]);
      $_SESSION["compareTotalMahasiswaBaru"] = [
        "compareTotalMahasiswaBaruFakultas"      => $kodeFakultas,
        "compareTotalMahasiswaBaruProdi"         => $prodi,
        "compareTotalMahasiswaBaruTahunAngkatan" => $tahunCompare
      ];

      // PANGGIL MODEL
      $fakultas                 = new Fakultas();
      $mahasiswabaru            = new MahasiswaBaru();
      $viewMahasiswaBaruProdi   = new ViewMahasiswaBaruProdi();
      $viewMahasiswaBaruProdiTa = new ViewMahasiswaBaruProdiTa();

      // QUERY FAKULTAS
      $getNamaFakultas = $fakultas->select("KodeFakultas, NamaFakultas")->whereNotIn("KodeFakultas", ["00", "50", "99"])->groupBy("KodeFakultas, NamaFakultas")->orderBy("KodeFakultas", "ASC")->findAll();

      // VARIABEL UNTUK CHART
      $chartColor                      = array();
      $chartCompareJumlahMahasiswaBaru = array();

      // VARIABEL UNTUK TABLE
      $dataTableCompareJumlahMahasiswaBaru = array();

      // VARIABEL UNTUK TABLE TOTAL MAHASISWA BARU
      $totalCompareJumlahMahasiswaBaru         = 0;
      $totalCompareJumlahMahasiswaBaruProdi    = 0;
      $totalCompareJumlahMahasiswaBaruPerTahun = [
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
      if ($tahunCompare == "") {
        $mahasiswa             = new Mahasiswa();
        $tahunTerbaruMahasiswa = $mahasiswa->select("LEFT(NIM, 4) as TahunAngkatan")->groupBy("LEFT(NIM, 4)")->orderBy("TahunAngkatan", "DESC")->first();
        $tahunCompare          = $tahunTerbaruMahasiswa["TahunAngkatan"];
      }
      for ($thn=($tahunCompare - 4); $thn <= $tahunCompare; $thn++) { 
        $listTahun[] = $thn;
      }
      

      // KALAU KODE FAKULTAS KOSONG JALANKAN
      if ($kodeFakultas == "") {

        foreach ($getNamaFakultas as $item) {
          $chartColor[] = $this->mydata["color"][$i];
          $chartCompareJumlahMahasiswaBaru[$i]["name"] = $item["NamaFakultas"];

          $data_array = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {
            $getMahasiswaBaruFakultas_sum = $mahasiswabaru->select('sum(JmlKonfirmasi) as total')
            ->join('StagingData.dbo.Prodi as prodi', 'prodi.KodeFakultas = LEFT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2) AND prodi.KodeProdi = RIGHT(dashboard_atmajaya.dbo.ms_mahasiswa_baru.KodeProdi, 2)')
            ->where("LEFT(KodeProdi, 2)", $item["KodeFakultas"])
            ->where("LEFT(TahunSemMasuk, 2)", substr($listTahun[$perthn], 2))
            ->first();
            $getMahasiswaBaruFakultas     = $getMahasiswaBaruFakultas_sum["total"];

            if (is_null($getMahasiswaBaruFakultas)) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, $getMahasiswaBaruFakultas);
            }
          }

          $chartCompareJumlahMahasiswaBaru[$i]["data"] = $data_array;

          $dataTableCompareJumlahMahasiswaBaru[$i]["nama_fakultas"]    = $item["NamaFakultas"];
          $dataTableCompareJumlahMahasiswaBaru[$i]["jumlah_mahasiswa"] = $data_array;

          // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
          for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
            $totalCompareJumlahMahasiswaBaru += $data_array[$totalCJM];

            $totalCompareJumlahMahasiswaBaruPerTahun[$totalCJM] += $data_array[$totalCJM];
          }

          $i++;
        }
      } else {

        $viewMahasiswaProdi = new ViewMahasiswaProdi();
        if ($prodi == "") {
          $getNamaProdi = $viewMahasiswaProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata['color'][$i];

            $chartCompareJumlahMahasiswaBaru[$i]['name'] = $item['NamaProdi'];

            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getMahasiswaBaruProdi = $viewMahasiswaBaruProdiTa->select("JmlKonfirmasi, TahunAngkatan")->where("NamaProdi", $item["NamaProdi"])->where("TahunAngkatan", substr($listTahun[$perthn], 2))->first();
              if (is_null($getMahasiswaBaruProdi)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getMahasiswaBaruProdi["JmlKonfirmasi"]);
              }
            }

            $chartCompareJumlahMahasiswaBaru[$i]['data'] = $data_array;

            $dataTableCompareJumlahMahasiswaBaru[$i]["nama_prodi"]       = $item["NamaProdi"];
            $dataTableCompareJumlahMahasiswaBaru[$i]["jumlah_mahasiswa"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalCompareJumlahMahasiswaBaruProdi += $data_array[$totalCJMP];

              $totalCompareJumlahMahasiswaBaruPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }
        } else {
          $getNamaProdi = $viewMahasiswaProdi
          ->select("KodeFakultas, NamaProdi")
          ->where("KodeFakultas", $kodeFakultas)
          ->where("NamaProdi", $prodi)
          ->groupBy("KodeFakultas, NamaProdi")
          ->orderBy("KodeFakultas", "ASC")
          ->findAll();

          foreach ($getNamaProdi as $item) {
            $chartColor[] = $this->mydata['color'][$i];

            $data_array  = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getMahasiswaBaruProdi = $viewMahasiswaBaruProdiTa->select("JmlKonfirmasi, TahunAngkatan")->where("NamaProdi", $item["NamaProdi"])->where("TahunAngkatan", substr($listTahun[$perthn], 2))->first();
              if (is_null($getMahasiswaBaruProdi)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, $getMahasiswaBaruProdi["JmlKonfirmasi"]);
              }
            }

            $chartCompareJumlahMahasiswaBaru[$i]['data'] = $data_array;

            $dataTableCompareJumlahMahasiswaBaru[$i]["nama_prodi"]       = $item["NamaProdi"];
            $dataTableCompareJumlahMahasiswaBaru[$i]["jumlah_mahasiswa"] = $data_array;

            // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
            for ($totalCJMP = 0; $totalCJMP <= 4; $totalCJMP++) {
              $totalCompareJumlahMahasiswaBaruProdi += $data_array[$totalCJMP];

              $totalCompareJumlahMahasiswaBaruPerTahun[$totalCJMP] += $data_array[$totalCJMP];
            }

            $i++;
          }
        }
      }

      $LastFiveYearsProdiSelected = [];
      foreach ($listTahun as $value) {
        array_push($LastFiveYearsProdiSelected, "$value");
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["ChartCompareJumlahMahasiswaBaru"] = json_encode($chartCompareJumlahMahasiswaBaru);
      $data["ChartColor"]                      = json_encode($chartColor);
      $data["LastFiveYears"]                   = json_encode($listTahun);
      $data["LastFiveYearsProdiSelected"]      = json_encode($LastFiveYearsProdiSelected);

      // DATA YANG AKAN DIKIRIM KE TABLE
      $data["TableCompareJumlahMahasiswaBaru"] = $dataTableCompareJumlahMahasiswaBaru;
      $data["TableListTahun"]                  = $listTahun;
      $data["TableTotalKeseluruhan"]           = $totalCompareJumlahMahasiswaBaru;
      $data["TableTotalKeseluruhanProdi"]      = $totalCompareJumlahMahasiswaBaruProdi;
      $data["TableTotalPerTahun"]              = $totalCompareJumlahMahasiswaBaruPerTahun;

      if ($kodeFakultas == "") {
        return $this->blade->render("pages.mahasiswa_dosen.mahasiswa_baru.compare", $data);
      } else {
        if ($prodi == "") {
          return $this->blade->render("pages.mahasiswa_dosen.mahasiswa_baru.compare_prodi", $data);
        } else {
          return $this->blade->render("pages.mahasiswa_dosen.mahasiswa_baru.compare_prodi_selected", $data);
        }
      }
    }
  }

  public function CompareTotalDosen()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      // GET KODE FAKULTAS SELECTED AND PRODI SELECTED
      $kodeFakultas = $this->request->getPost("fakultascompare");

      // SET SESSION
      unset($_SESSION["totalDosen"]);
      $_SESSION["compareTotalDosen"] = [
        "compareTotalDosenFakultas"      => $kodeFakultas
      ];

      // PANGGIL MODEL
      $fakultas          = new Fakultas();
      $viewDosenFakultas = new ViewDosenFakultas();
      $viewDosenProdi    = new ViewDosenProdi();

      // QUERY FAKULTAS
      $getNamaFakultas = $fakultas->select("KodeFakultas, NamaFakultas")->whereNotIn("KodeFakultas", ["00", "50", "99"])->groupBy("KodeFakultas, NamaFakultas")->orderBy("KodeFakultas", "ASC")->findAll();

      // VARIABEL UNTUK CHART
      $chartColor                  = array();
      $chartCompareJumlahDosen = array();

      // VARIABEL UNTUK TABLE
      $dataTableCompareJumlahDosen = array();

      // VARIABEL UNTUK TABLE TOTAL DOSEN
      $totalCompareJumlahDosen              = 0;
      $totalCompareJumlahDosenProdi         = 0;

      // VARIABEL UNTUK INDEXING
      $i = 0;

      // KALAU KODE FAKULTAS KOSONG JALANKAN
      if ($kodeFakultas == "") {
        $data_fakultas = array();
        $jumlah_dosen = array();
        foreach ($getNamaFakultas as $item) {
          $chartColor[] = $this->mydata["color"][$i];
          $chartCompareJumlahDosen[$i]["name"] = $item["NamaFakultas"];

          $data_array = array();
          $getDosenFakultas = $viewDosenFakultas->select("JumlahDosen")->where("KodeFakultas", $item["KodeFakultas"])->first();
          $data_fakultas[] = $item["NamaFakultas"];
          if (is_null($getDosenFakultas)) {
            array_push($data_array, 0);
            $jumlah_dosen[] = 0;
          } else {
            array_push($data_array, $getDosenFakultas["JumlahDosen"]);
            $jumlah_dosen[] = $getDosenFakultas["JumlahDosen"];
          }

          $chartCompareJumlahDosen[$i]["data"] = $data_array;

          $dataTableCompareJumlahDosen[$i]["nama_fakultas"] = $item["NamaFakultas"];
          $dataTableCompareJumlahDosen[$i]["jumlah_dosen"]  = $data_array;

          // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
          $totalCompareJumlahDosen += $data_array[0];
          $i++;
          $data["dataFakultas"]            = json_encode($data_fakultas);
        }
      } else {
        $data_prodi      = array();
        $jumlah_dosen       = array();
        $viewMahasiswaProdi = new ViewMahasiswaProdi();

        $getNamaProdi = $viewMahasiswaProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();
        
        foreach ($getNamaProdi as $item) {
          $data_prodi[] = $item["NamaProdi"];
          $chartColor[] = $this->mydata['color'][$i];

          $chartCompareJumlahDosen[$i]['name'] = $item['NamaProdi'];

          $data_array  = array();
          $getDosenProdi = $viewDosenProdi->select("JumlahDosen")->where("NamaProdi", $item["NamaProdi"])->first();
          if (is_null($getDosenProdi)) {
            array_push($data_array, 0);
            $jumlah_dosen[] = 0;
          } else {
            array_push($data_array, $getDosenProdi["JumlahDosen"]);
            $jumlah_dosen[] = $getDosenProdi["JumlahDosen"];
          }

          $chartCompareJumlahDosen[$i]['data'] = $data_array;

          $dataTableCompareJumlahDosen[$i]["nama_prodi"]   = $item["NamaProdi"];
          $dataTableCompareJumlahDosen[$i]["jumlah_dosen"] = $data_array;

          // INI UNTUK MENGHITUNG TOTAL KESELURUHAN
          $totalCompareJumlahDosenProdi += $data_array[0];

          $i++;
          $data["dataProdi"]               = json_encode($data_prodi);
        }
      }

      // DATA YANG AKAN DIKIRIM KE CHART
      $data["chartCompareJumlahDosen"] = json_encode($chartCompareJumlahDosen);
      $data["jumlahDosen"]             = json_encode($jumlah_dosen);
      $data["ChartColor"]              = json_encode($chartColor);

      // DATA YANG AKAN DIKIRIM KE TABLE
      $data["TableCompareJumlahDosen"]    = $dataTableCompareJumlahDosen;
      $data["TableTotalKeseluruhan"]      = $totalCompareJumlahDosen;
      $data["TableTotalKeseluruhanProdi"] = $totalCompareJumlahDosenProdi;

      if ($kodeFakultas == "") {
        return $this->blade->render("pages.mahasiswa_dosen.dosen.compare", $data);
      } else {
        return $this->blade->render("pages.mahasiswa_dosen.dosen.compare_prodi", $data);
      }
    }
  }

  public function CompareRasioDosenMahasiswa()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // GET KODE FAKULTAS SELECTED AND TAHUN TERAKHIR SELECTED
        $kodeFakultas    = $this->request->getPost("fakultascompare");
        $prodi           = $this->request->getPost("prodicompare");
        $angkatanCompare = $this->request->getPost("angkatancompare");
        $Tahun           = $this->request->getPost("tahuncompare");
        $Bulan           = $this->request->getPost("bulancompare");

        // SET SESSION
        unset($_SESSION["rasioDosenMahasiswa"]);
        $_SESSION["compareRasioDosenMahasiswa"] = [
          "compareRasioDosenMahasiswaFakultas"      => $kodeFakultas,
          "compareRasioDosenMahasiswaProdi"         => $prodi,
          "compareRasioDosenMahasiswaTahunAngkatan" => $angkatanCompare,
          "compareRasioDosenMahasiswaTahun"         => $Tahun,
          "compareRasioDosenMahasiswaBulan"    => $Bulan,
        ];

        // PANGGIL MODEL
        $viewMahasiswaFakultas   = new ViewMahasiswaFakultas();
        $viewMahasiswaFakultasTa = new ViewMahasiswaFakultasTa();
        $viewMahasiswaProdi      = new ViewMahasiswaProdi();
        $viewMahasiswaProdiTa    = new ViewMahasiswaProdiTa();
        $viewDosenFakultas       = new ViewDosenFakultas();
        $viewDosenProdi          = new ViewDosenProdi();

        $fakultas = new Fakultas();

        $viewMahasiswa = new ViewMahasiswa();

        // DEFINE TAHUN TERAKHIR DATABASE
        $latestYear = $viewMahasiswa->select("MAX(Tahun) as Tahun")->first();
        // DEFINE TAHUN TERAKHIR DATABASE

        // DEFINE BULAN TERAKHIR DATABASE
        for ($i = 12; $i >= 1; $i--) {
          $latestMonth = $viewMahasiswa->select("Bulan")
          ->where("Tahun", $latestYear["Tahun"])
          ->where("Bulan", $i)
          ->first();
          if (!empty($latestMonth["Bulan"])) {
            $i = 0;
          }
        }
        // DEFINE BULAN TERAKHIR DATABASE

        // DEFINE TAHUN, BULAN
        if ($Tahun == "") {
          $Tahun = $latestYear["Total"]; // Output : 2022
        }
        if ($Bulan == "") {
          $Bulan = $latestMonth["Bulan"];
        }
        // DEFINE TAHUN, BULAN        

        // ambil data fakultas selain KodeFakultas 00, 50, 99
        $Fakultas = $fakultas->select("KodeFakultas, NamaFakultas")
        ->whereNotIn('KodeFakultas', ['00', '50', '99'])
        ->groupBy("KodeFakultas, NamaFakultas")
        ->orderBy("KodeFakultas ASC")
        ->findAll();

        // VARIABEL UNTUK RASIO DOSEN MAHASISWA
        $dataRasio = array();

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $listTahun = [];
        for ($thn = ($Tahun - 4); $thn <= $Tahun; $thn++) {
          $listTahun[] = $thn;
        }

        // KALAU KODE FAKULTAS KOSONG JALANKAN
        if ($kodeFakultas == "") {

          foreach ($Fakultas as $item) {
            $dataRasio[$i]["name"] = $item["NamaFakultas"];
            $dataColor[] = $this->mydata['color'][$i];

            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getMahasiswaFakultas = $viewMahasiswa
              ->select("StudentID")
              ->distinct("StudentID")
              ->where("Tahun", $listTahun[$perthn])
              ->where("Bulan", $Bulan)
              ->where("KodeFakultasInduk", $item["KodeFakultas"]);
              if ($angkatanCompare != "") {
                $getMahasiswaFakultas->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
              }
              $total = $getMahasiswaFakultas->countAllResults();
              if (empty($total)) {
                if ($listTahun[$perthn] == $latestYear["Tahun"]) {
                  $getMahasiswaFakultas = $viewMahasiswa
                  ->select("StudentID")
                  ->distinct("StudentID")
                  ->where("Tahun", $Tahun)
                  ->where("Bulan", $Bulan)
                  ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                  if ($angkatanCompare != "") {
                    $getMahasiswaFakultas->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                  }
                  $total = $getMahasiswaFakultas->countAllResults();
                  if (empty($total)) {
                    $PerhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = 0;
                  } else {
                    $PerhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = $total;
                  }
                } else {
                  $PerhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = 0;
                }
              } else {
                $PerhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = $total;
              }
            }

            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getDosenFakultas = $viewDosenFakultas->select("JumlahDosen")->where("KodeFakultas", $item["KodeFakultas"])->first();
              if (empty($getDosenFakultas)) {
                $PerhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]] = 0;
              } else {
                $PerhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]] = $getDosenFakultas["JumlahDosen"];
              }
            }

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              if (!empty($PerhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]])) {
                $PerhitunganRasio[$i]['rasioMahasiswa'][$listTahun[$perthn]] = $PerhitunganJumlahMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perthn]] / $PerhitunganJumlahDosen[$i]['jumlah_dosen'][$listTahun[$perthn]];

                array_push($data_array, round($PerhitunganRasio[$i]['rasioMahasiswa'][$listTahun[$perthn]], 1));
              } else {
                array_push($data_array, 0);
              }
            }

            $dataRasio[$i]["data"] = $data_array;

            $i++;
          }
        } else {

          if($prodi == "") {
            $getNamaProdi = $viewMahasiswaProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();
  
            foreach ($getNamaProdi as $item) {
              $dataRasio[$i]["name"] = $item["NamaProdi"];
              $dataColor[] = $this->mydata['color'][$i];
  
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                $getMahasiswaProdi = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $listTahun[$perthn])
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                if ($angkatanCompare != "") {
                  $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                }
                $total = $getMahasiswaProdi->countAllResults();
                if (empty($total)) {
                  if ($listTahun[$perthn] == $latestYear["Tahun"]) {
                    $getMahasiswaProdi = $viewMahasiswa
                    ->select("StudentID")
                    ->distinct("StudentID")
                    ->where("Tahun", $Tahun)
                    ->where("Bulan", $Bulan)
                    ->where("NamaProdi", $item["NamaProdi"])
                    ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                    if ($angkatanCompare != "") {
                      $getMahasiswaProdi->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                    }
                    $total = $getMahasiswaProdi->countAllResults();
                    if (empty($total)) {
                      $perhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = 0;
                    } else {
                      $perhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = $total;
                    }
                  } else {
                    $perhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = 0;
                  }
                } else {
                  $perhitunganJumlahMahasiswa[$i]["jumlah_mahasiswa"][$listTahun[$perthn]] = $total;
                }
              }
  
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                $getDosenProdi = $viewDosenProdi->select("JumlahDosen")->where("NamaProdi", $item["NamaProdi"])->first();
                if (empty($getDosenProdi)) {
                  $perhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]] = 0;
                } else {
                  $perhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]] = $getDosenProdi["JumlahDosen"];
                }
              }
  
              $data_array = array();
              for ($perthn = 0; $perthn <= 4; $perthn++) {
                if (!empty($perhitunganJumlahDosen[$i]["jumlah_dosen"][$listTahun[$perthn]])) {
                  $perhitunganRasio[$i]['rasioMahasiswa'][$listTahun[$perthn]] = $perhitunganJumlahMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perthn]] / $perhitunganJumlahDosen[$i]['jumlah_dosen'][$listTahun[$perthn]];
                  
                  array_push($data_array, round($perhitunganRasio[$i]['rasioMahasiswa'][$listTahun[$perthn]], 1));
                } else {
                  array_push($data_array, 0);
                }
              }
  
              $dataRasio[$i]["data"] = $data_array;
  
              $i++;
            }
          } else {
            $getNamaProdi = $viewMahasiswaProdi
            ->select("KodeFakultas, NamaProdi")
            ->where("KodeFakultas", $kodeFakultas)
            ->where("NamaProdi", $prodi)
            ->findAll();

            foreach($getNamaProdi as $item){
              // passing series name: {}, dan color: {} ke chart
              $dataRasio[$i]["name"] = $item['NamaProdi'];
              $dataColor[] = $this->mydata['color'][$i];
              $data_array = array();
              for($perTahun = 0; $perTahun <= 4; $perTahun++){
                // get data mahasiswa, untuk perhitungan jumlah mahasiswa
                $getMahasiswa = $viewMahasiswa
                ->select("StudentID")
                ->distinct("StudentID")
                ->where("Tahun", $listTahun[$perTahun])
                ->where("Bulan", $Bulan)
                ->where("NamaProdi", $item["NamaProdi"])
                ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                if ($angkatanCompare != "") {
                  $getMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                }
                $total = $getMahasiswa->countAllResults();
                if(empty($total)){
                  if ($listTahun[$perTahun] == $latestYear["Tahun"]) {
                    $getMahasiswa = $viewMahasiswa
                      ->select("StudentID")
                      ->distinct("StudentID")
                      ->where("Tahun", $Tahun)
                      ->where("Bulan", $Bulan)
                      ->where("NamaProdi", $item["NamaProdi"])
                      ->where("KodeFakultasInduk", $item["KodeFakultas"]);
                    if ($angkatanCompare != "") {
                      $getMahasiswa->where("LEFT(TahunSemesterMasuk, 2)", substr($angkatanCompare, -2));
                    }
                    $total = $getMahasiswa->countAllResults();
                    if (empty($total)) {
                      $countMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perTahun]] = 0;
                    } else {
                      $countMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perTahun]] = $total;
                    }
                  } else {
                    $countMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perTahun]] = 0;
                  }
                } else {
                  $countMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perTahun]] = $total;
                }
                // get data dosen, untuk perhitungan jumlah dosen
                $getDosen = $viewDosenProdi->select("JumlahDosen")->where("NamaProdi", $item["NamaProdi"])->first();
                $countDosen[$i]['jumlah_dosen'][$listTahun[$perTahun]] = 0;
                if (empty($getDosen)) {
                  $countDosen[$i]['jumlah_dosen'][$listTahun[$perTahun]] = 0;
                } else{
                  $countDosen[$i]['jumlah_dosen'][$listTahun[$perTahun]] = $getDosen["JumlahDosen"];
                }
                // calc data rasio, mahasiswa : dosen
                $countRasio['rasioMahasiswa'][$listTahun[$perTahun]] = 0;
                if (!empty($countDosen[$i]['jumlah_dosen'][$listTahun[$perTahun]])) {
                  $countRasio['rasioMahasiswa'][$listTahun[$perTahun]] = $countMahasiswa[$i]['jumlah_mahasiswa'][$listTahun[$perTahun]] / $countDosen[$i]['jumlah_dosen'][$listTahun[$perTahun]];
                  array_push($data_array, round($countRasio['rasioMahasiswa'][$listTahun[$perTahun]], 1));
                } else {
                  array_push($data_array, 0);
                }
                // passing series data: {} ke chart
                $dataRasio[$i]["data"] = $data_array;
              }
              $i++;
            }
          }
        }
        
        // DATA YANG AKAN DIKIRIM KE VIEW
        $data["dataRasio"]     = json_encode($dataRasio);
        $data["LastFiveYears"] = json_encode($listTahun);
        $data["ChartColor"]    = json_encode($dataColor);

        if ($kodeFakultas == "") {
          return $this->blade->render("pages.mahasiswa_dosen.rasio_dosen_mahasiswa.compare", $data);
        } else {
          return $this->blade->render("pages.mahasiswa_dosen.rasio_dosen_mahasiswa.compare_prodi", $data);
        }
      } else {
        return redirect()->to('/universitas');
      }
      
    }
  }

  public function SetPreference() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $compare = $this->request->getPost("7e1f2f97");
      $filter  = $this->request->getPost("7dde607");

      $_SESSION["globalCompare"] = $compare;
      $_SESSION["globalFilter"]  = $filter;
    }
  }

  public function GetProdi()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $kodeFakultas = $this->request->getPost("kodefakultas");

      $viewMahasiswaProdi = new ViewMahasiswaProdi();

      $listProdi = "";

      $getNamaProdi = $viewMahasiswaProdi->select("KodeFakultas, NamaProdi")->where("KodeFakultas", $kodeFakultas)->groupBy("KodeFakultas, NamaProdi")->orderBy("KodeFakultas", "ASC")->findAll();

      $listProdi .= "<option value=\"\">Seluruh Prodi</option>";
      foreach ($getNamaProdi as $item) {
        $listProdi .= "<option value=\"" . $item["NamaProdi"] . "\" " . (!empty($_SESSION["compareTotalMahasiswa"]["compareTotalMahasiswaProdi"]) == $item["NamaProdi"] ? "selected" : "") . ">" . $item["NamaProdi"] . "</option>";
      }

      return $listProdi;
    }

  }

}
