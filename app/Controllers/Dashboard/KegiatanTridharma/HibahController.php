<?php

namespace App\Controllers\Dashboard\KegiatanTridharma;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;
use App\Models\Semester;
use App\Models\Hibah;

use App\Models\Dashboard\KegiatanTridharmaPT\Hibah\ViewTridharmaHibahDataTable;
use App\Models\Dashboard\KegiatanTridharmaPT\Hibah\ViewTridharmaHibahJumlah;
use App\Models\Dashboard\KegiatanTridharmaPT\Hibah\ViewTridharmaHibahAnggaran;

require ('App/ThirdParty/spreadsheet-reader/php-excel-reader/excel_reader2.php');  
require ('App/ThirdParty/spreadsheet-reader/SpreadsheetReader.php');

class HibahController extends BaseController
{

  public $mydata;
  public function __construct(){
    $semester = new Semester();
    $this->mydata['tahun_semester'] = $semester->where('is_active', '1')->orderBy('kode_semester', 'desc')->findAll();
    $this->mydata['id_menu'] = '8';
  }

  public function Index() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['tahun_semester'] = $this->mydata['tahun_semester'];
      $data['id_menu']     = $this->mydata['id_menu'];
      if(acc_read(session('level_id'),$data['id_menu'])=="1"){
        return $this->blade->render("pages.kegiatan_tridharma.hibah.index", $data);
      }
      else{
        return redirect()->to('/universitas');
      }
    }
  }

  public function Res(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("pages.kegiatan_tridharma.hibah.res", $data);
      } else {
        return redirect()->to('/universitas');
      }

    }
  }

  public function Read(){
    $id_menu  = $this->mydata['id_menu'];
    $tableHibah = new ViewTridharmaHibahDataTable();
    $datatables   = new MyDatatables();
    $columnOrder  = [
      "InstitusiPendana", "Periode", "Dana", "Nama", "Tahun"
    ];
    $columnSearch = [
      "InstitusiPendana", "Nama", "Tahun"
    ];

    $query = $tableHibah;
    $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
    $data    = [];

    foreach ($getList as $list) {

      // $list_btn = '<div class="dropdown table-btn-group d-flex">
      //               <button class="btn btn-outline-primary border-0 dropdown-toggle rounded-circle m-auto" type="button" id="btnTableGrup" data-bs-toggle="dropdown" aria-expanded="false" style="height: 40px; width: 40px">
      //                 <i class="mdi mdi-menu"></i>
      //               </button>';

    //  $list_btn .= "<ul class='dropdown-menu' aria-labelledby='btnTableGroup'>";
     
    // if(acc_update(session('level_id'),$id_menu)=="1"){ 
    //    //Edit
    //    $list_btn .= "<li>
    //                   <button class='btn btn-sm text-nowrap m-1' onclick='Go(\"hibah\",\"Hibah\",\"edit\",\"Ubah\",\"".$list->hibah_id."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
    //                 </li>"; 
    // }
      
    // if(acc_delete(session('level_id'),$id_menu)=="1"){  
    //    //Delete
    //    $list_btn .= "<li>
    //                   <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->hibah_id."\", \"Hapus Hibah\", \"Apakah anda yakin ingin hapus data hibah ?\", \"warning\", \"" . base_url() . "/universitas/hibah/delete\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
    //                 </li>"; 
    // }

      // $list_btn .="</ul>
      //           </div>";


      $row                          = [];
      // $row  ["hibahID"]             = $list->hibah_id;
      // $row  ["hibahInstitution"]    = $list->hibah_institution;
      // $row  ["hibahName"]           = $list->hibah_name;
      // $row  ["hibahPeriode"]        = get_semester($list->hibah_periode);
      // $row  ["hibahPriceReceived"]  = "Rp".int_to_rp($list->hibah_price_received);
      // $row  ["hibahPriceCompanion"] = "Rp".int_to_rp($list->hibah_price_companion);
      // $row  ["hibahPIC"]            = $list->hibah_pic;
      // $row  ["hibahAction"]         = $list_btn;
      $row["hibahInstitusi"] = (empty($list->InstitusiPendana) ? "-" : $list->InstitusiPendana);
      $row["hibahPeriode"]   = (empty($list->Periode) ? "-" : get_semester($list->Periode));
      $row["hibahDana"]      = number_format($list->Dana/1000000);
      $row["hibahPIC"]       = $list->Nama;
      $row["hibahTahun"]     = $list->Tahun;

      $data[]                       = $row;
    }
    echo $datatables->response($data);
  }

  public function Detail(){
    $hbh = new Hibah;
    $id  = $this->request->getPost("parameter");
    $get = $hbh->where('hibah_id',$id)->first();
        if(!empty($get)){
            echo json_encode([
                "id"                         => $get['hibah_id'],
                "name"                       => $get['hibah_name'],
                "periode"                    => $get['hibah_periode'],
                "institution"                => $get['hibah_institution'],
                "total_pengabdian"           => $get['hibah_total_pengabdian'],
                "total_penelitian"           => $get['hibah_total_penelitian'],
                "total_penelitian_pegabdian" => $get['hibah_total_penelitian_pengabdian'],
                "pic"                        => $get['hibah_pic'],
                "member"                     => $get['hibah_member'],
                "price_companion"            => int_to_rp($get['hibah_price_companion']),
                "price_received"             => int_to_rp($get['hibah_price_received']),
                "status"                     => "OK"
            ]);
        }
        else {
            echo json_encode([
                "status"    => "NOK",
                "message"   => "Data hibah tidak tersedia"
            ]);
        }
  }

  public function Create() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['tahun_semester'] = $this->mydata['tahun_semester'];
      return $this->blade->render("pages.kegiatan_tridharma.hibah.create", $data);
    }
  }

  public function Save(){
    $generate = "hbh_" . date("ymdhis") . "_" . uniqid();
    $hbh = new Hibah;

    $data = [
      "hibah_id"                          => $generate,
      "hibah_name"                        => $this->request->getPost("hibah_name"),
      "hibah_periode"                     => $this->request->getPost("tahun_semester"),
      "hibah_institution"                 => $this->request->getPost("lembaga"),
      "hibah_total_pengabdian"            => $this->request->getPost("jumlah_hibah_pengabdian"),
      "hibah_total_penelitian"            => $this->request->getPost("jumlah_hibah_penelitian"),
      "hibah_total_penelitian_pengabdian" => $this->request->getPost("jumlah_hibah_penelitian_pengabdian"),
      "hibah_pic"                         => $this->request->getPost("pic_pengabdian_penelitan"),
      "hibah_price_companion"             => rp_to_int($this->request->getPost("hibah_pendamping")),
      "hibah_price_received"              => rp_to_int($this->request->getPost("hibah_terima")),
      "created_by"                        => session('session_id'),
      "updated_by"                        => session('session_id')
    ];

    //Proses Save
      try{

          $dataMember = $this->request->getPost("member_pengabdian_penelitan");
          if(empty($dataMember)){
            echo json_encode([
                "status"            => "NOK",
                "message"           => "Member Pengabdian/Penelitian Kosong, Silahkan Pilih"
            ]);
            die();  
          }
          $i=0;
          $store_member = "";
          foreach($dataMember as $member){
            $store_member .= $member.";";
            $i++;
          }
          $data['hibah_member'] = $store_member;

          $hbh->save($data);
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
      }
      catch(\Exception $e){
         // do task when error
         print_r($hbh->errors());die();
         $err = "";
         if ($hbh->errors()) {
           echo "anu";die();
            foreach ($hbh->errors() as $error) {
              $err .= $error . "<br>";
            }
          } else {
            echo"else";die();
          }

          if(empty($err)){
            $err = $e->getMessage();
          }

          echo json_encode([
            "status"            => "NOK",
            "message"           => $err
           ]);
          die();
      }
  } 

  public function Edit() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['tahun_semester'] = $this->mydata['tahun_semester'];
            $id               = $this->request->getPost('parameter');
            $tableHibah       = new Hibah;
      
      $data["data"]                 = $tableHibah->find($id);
      
      return $this->blade->render("pages.kegiatan_tridharma.hibah.edit", $data);
    }
  }

  public function Update(){
    $id  = $this->request->getPost('parameter');
    $hbh = new Hibah;

    $check = $hbh->where('status',1)->where('hibah_id', $id)->first();
    if(!empty($check)){
        $data = [
          "hibah_name"                        => $this->request->getPost("hibah_name"),
          "hibah_periode"                     => $this->request->getPost("tahun_semester"),
          "hibah_institution"                 => $this->request->getPost("lembaga"),
          "hibah_total_pengabdian"            => $this->request->getPost("jumlah_hibah_pengabdian"),
          "hibah_total_penelitian"            => $this->request->getPost("jumlah_hibah_penelitian"),
          "hibah_pic"                         => $this->request->getPost("pic_pengabdian_penelitan"),
          "hibah_total_penelitian_pengabdian" => $this->request->getPost("jumlah_hibah_penelitian_pengabdian"),
          "hibah_price_received"              => rp_to_int($this->request->getPost("hibah_terima")),
          "hibah_price_companion"             => rp_to_int($this->request->getPost("hibah_pendamping")),
          "updated_by"                        => session('session_id')
       ];
       
       //Proses Update
       try{
            $dataMember = $this->request->getPost("member_pengabdian_penelitan");
            if(empty($dataMember)){
              echo json_encode([
                  "status"            => "NOK",
                  "message"           => "Member Pengabdian/Penelitian Kosong, Silahkan Pilih"
              ]);
              die();  
            }
            $i=0;
            $store_member = "";
            foreach($dataMember as $member){
              $store_member .= $member.";";
              $i++;
            }
            $data['hibah_member'] = $store_member;
            $hbh->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($hbh->errors())) {
              foreach ($hbh->errors() as $error) {
                $err .= $error . "<br>";
              }
            }

            if(empty($err)){
              $err = $e->getMessage();
            }

            echo json_encode([
              "status"            => "NOK",
              "message"           => $err
             ]);
            die();
        }
    }
    else {
      echo json_encode([
            "status"            => "NOK",
            "message"           => "Data hibah tidak tersedia"
        ]);
        die();
    }
  } 

  public function Delete(){
    $id    = $this->request->getPost("id");
    $hbh   = new Hibah;
    $check = $hbh->where('hibah_id',$id)->first();
    if(empty($check)){
      echo json_encode([
              "status"           => "NOK",
              "message"          => "Data hibah tidak tersedia"
          ]);
      die();
    }

     $data = [
          "status"                => "0",
          "updated_by"            => session('session_id')
       ];

      try{
          $hbh->update($id, $data);
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
      }
      catch(\Exception $e){
         // do task when error
         echo json_encode([
              "status"            => "NOK",
              "message"           => $e->getMessage()
          ]);
      }
  }

  public function Periode() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['tahun_semester'] = $this->mydata['tahun_semester'];
      $html="<table style='width:100%' class='table_black' cellspacing='0'>
                  <tr class='tr_black'>
                    <td align='center' class='td_black'><b>Kode Periode</b></td>
                    <td align='center' class='td_black'><b>Periode</b></td>
                  </tr>";
      foreach($data['tahun_semester'] as $dt){
        $html .="<tr class='tr_black'>";
          $html .="<td align='center' class='td_black'>".$dt['kode_semester']."</td>";
          $html .="<td align='center' class='td_black'>".get_semester($dt['kode_semester'])."</td>";
        $html .="</tr>";
      }
      echo $html;
    }
  }

  public function Import() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      return $this->blade->render("pages.kegiatan_tridharma.hibah.import");
    }
  }

  public function ImportInsertData_Preview() {
    $file_ext = array('xls','csv');
    if(isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_hibah_".date("ymdhis")."_".rand(10000,99999).".".$ext;
      $upload_dir       = $path.$fileName;

      move_uploaded_file($_FILES['excel_file']['tmp_name'],$upload_dir);
      $Reader = new \SpreadsheetReader($upload_dir);
      
      $arr = [];
      $total=0;
      $duplikasi=0;
      $num=0;

      foreach ($Reader as $Key => $Row){
        if ($Key < 2) continue; 
        if(!empty($Row[0])){
          $nama                                              = $Row['0'];
          $periode                                           = get_semester($Row['1']);
          $jmlh_penelitian_direncanakan_dilakukan            = $Row['2'];
          $jmlh_pengabdian_direncanakan_dilakukan            = $Row['3'];
          $jmlh_penelitian_pengabdian_direncanakan_dilakukan = $Row['4'];
          $dana_terima                                       = $Row['5'];
          $dana_pendamping                                   = $Row['6'];
          $lembaga                                           = $Row['7'];
          $pic                                               = $Row['8'];
          $member                                            = rtrim($Row['9'],';');

          $duplicate = "";
          $count = 0; // Check database if data must unique
          if($count>0){
            $duplicate = "exist_data"; // Css Class if data exist in database
            $duplikasi++;
          }
          else{
            $total++;
          }
          $num++;

          $new = array('nama'=>$nama,
                 'periode'=>$periode,
                 'jmlh_penelitian_direncanakan_dilakukan' =>$jmlh_penelitian_direncanakan_dilakukan,
                 'jmlh_pengabdian_direncanakan_dilakukan'=>$jmlh_pengabdian_direncanakan_dilakukan,
                 'jmlh_penelitian_pengabdian_direncanakan_dilakukan'=>$jmlh_penelitian_pengabdian_direncanakan_dilakukan,
                 'dana_terima'=>$dana_terima,
                 'dana_pendamping'=>$dana_pendamping,
                 'lembaga'=>$lembaga,
                 'pic'=>$pic,
                 'member'=>$member,
                 'no'=>$num,
                 'duplicate'=>$duplicate
                );
          $arr[] = $new;

         }
        }

        $data['total_duplikasi']  = $duplikasi;
        $data['total_import']     = $total;
        $data['total']            = $total+$duplikasi;
        $data['import']           = $arr;
        @unlink($upload_dir); //remove file excel
        return $this->blade->render("pages/kegiatan_tridharma/hibah/import_insert_hibah_preview", $data);
    }
  }

  public function ImportInsertData_Proses() {
      $file_ext = array('xls','csv');
      if(isset($_FILES['excel_file']['name'])) {

        //upload file excel
        $path             = "./import/upload/xls/";
        $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
        $fileName         = "import_hibah_".date("ymdhis")."_".rand(10000,99999).".".$ext;
        $upload_dir       = $path.$fileName;

        move_uploaded_file($_FILES['excel_file']['tmp_name'],$upload_dir);
        $Reader = new \SpreadsheetReader($upload_dir);
        
        $arr = [];
        $total=0;
        $duplikasi=0;
        $num=0;
        
        foreach ($Reader as $Key => $Row){
          if ($Key < 2) continue; 
            if(!empty($Row[0])){
                $nama                                              = $Row['0'];
                $periode                                           = $Row['1'];
                $jmlh_penelitian_direncanakan_dilakukan            = $Row['2'];
                $jmlh_pengabdian_direncanakan_dilakukan            = $Row['3'];
                $jmlh_penelitian_pengabdian_direncanakan_dilakukan = $Row['4'];
                $dana_terima                                       = $Row['5'];
                $dana_pendamping                                   = $Row['6'];
                $lembaga                                           = $Row['7'];
                $pic                                               = $Row['8'];
                $member                                            = $Row['9'];

                $count = 0; // Check database if data must unique
                if($count==0){
                    
                    $generate = "hbh_" . date("ymdhis") . "_" . uniqid();
                    $hbh = new Hibah;

                    $data = [
                      "hibah_id"                          => $generate,
                      "hibah_name"                        => $nama,
                      "hibah_periode"                     => $periode,
                      "hibah_institution"                 => $lembaga,
                      "hibah_total_pengabdian"            => $jmlh_pengabdian_direncanakan_dilakukan,
                      "hibah_total_penelitian"            => $jmlh_penelitian_direncanakan_dilakukan,
                      "hibah_total_penelitian_pengabdian" => $jmlh_penelitian_pengabdian_direncanakan_dilakukan,
                      "hibah_pic"                         => $pic,
                      "hibah_member"                      => $member,
                      "hibah_price_companion"             => rp_to_int($dana_pendamping),
                      "hibah_price_received"              => rp_to_int($dana_terima),
                      "created_by"                        => session('session_id'),
                      "updated_by"                        => session('session_id')
                    ];

                    //Proses Save
                    $hbh->save($data);

                }
             }
          }
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
          @unlink($upload_dir); //remove file excel
          
      }
  }

  public function ChartHibah()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // PANGGIL MODEL
        $viewHibahJumlah   = new ViewTridharmaHibahJumlah();
        $viewHibahAnggaran = new ViewTridharmaHibahAnggaran();

        // VARIABEL UNTUK CHART
        $chartHibah             = array();
        $dataTableAnggaranHibah = array();

        // VARIABEL UNTUK TOTAL PER TAHUN
        $totalHibahPerTahun      = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];
        $totalAnggaranPerTahun      = [
          0 => 0,
          1 => 0,
          2 => 0,
          3 => 0,
          4 => 0
        ];
        $totalAnggaranKeseluruhan = 0;

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn        = 0;
        $listTahun  = [];
        $getLatestYear = $viewHibahJumlah->select("Tahun")->groupBy("Tahun")->orderBy("Tahun", "DESC")->first();
        $latestYear = $getLatestYear["Tahun"];
        for ($thn = ($latestYear - 4); $thn <= $latestYear; $thn++) {
          $listTahun[] = $thn;
        }

        // MEMBUAT VARIABEL NAMA SKEMA HIBAH
        $getNamaSkema = $viewHibahJumlah->select("Skema")->groupBy("Skema")->orderBy("Skema", "ASC")->findAll();

        foreach ($getNamaSkema as $item) {
          $chartHibah[$i]["name"] = $item["Skema"];

          $data_array = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {
            $getJumlahHibah = $viewHibahJumlah->select("Skema, JumlahHibah")->where("Skema", $item["Skema"])->where("Tahun", $listTahun[$perthn])->first();
            if (is_null($getJumlahHibah)) {
              array_push($data_array, 0);
            } else {
              array_push($data_array, $getJumlahHibah["JumlahHibah"]);
            }
          }

          $chartHibah[$i]["data"] = $data_array;

          $data_anggaran = array();
          for ($perthn = 0; $perthn <= 4; $perthn++) {
            $getHibahAnggaran = $viewHibahAnggaran->select("Skema, AnggaranDisetujui")->where("Skema", $item["Skema"])->where("Tahun", $listTahun[$perthn])->first();
            if (is_null($getHibahAnggaran)) {
              array_push($data_anggaran, 0);
            } else {
              array_push($data_anggaran, $getHibahAnggaran["AnggaranDisetujui"]);
            }
          }

          $dataTableAnggaranHibah[$i]["Skema"]           = $item["Skema"];
          $dataTableAnggaranHibah[$i]["jumlah_anggaran"] = $data_anggaran;

          // INI UNTUK MENGHITUNG TOTAL JUDUL HIBAH
          for ($totalCJM = 0; $totalCJM <= 4; $totalCJM++) {
            $totalAnggaranKeseluruhan += $data_anggaran[$totalCJM];

            $totalAnggaranPerTahun[$totalCJM] += $data_anggaran[$totalCJM];

            $totalHibahPerTahun[$totalCJM] += $data_array[$totalCJM];
          }

          $i++;
        }

        $LastFiveYears = [];
        for ($i = 0; $i <= 4; $i++) {
          $LastFiveYears[] = [
            "$listTahun[$i]", "($totalHibahPerTahun[$i] " . " Total)"
          ];
        }

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartHibah"]    = json_encode($chartHibah);
        $data["LastFiveYears"] = json_encode($LastFiveYears);

        // DATA YANG AKAN DIKIRIM KE TABLE
        $data["TableAnggaranHibah"]    = $dataTableAnggaranHibah;
        $data["TableListTahun"]        = $listTahun;
        $data["TableAnggaranPerTahun"] = $totalAnggaranPerTahun;
        $data["TableTotalKeseluruhan"] = $totalAnggaranKeseluruhan;

        return $this->blade->render("pages.kegiatan_tridharma.hibah.chart", $data);
      } else {
        return redirect()->to('/universitas');
      }
      
    }
  }

}
