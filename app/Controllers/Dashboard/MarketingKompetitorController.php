<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;
use App\Models\Prodi;

use App\Models\Dashboard\MarketingCompetitor\MarketingKompetitor;
use App\Models\Dashboard\MarketingCompetitor\ViewMarketingCompetitorProdi;

require('App/ThirdParty/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('App/ThirdParty/spreadsheet-reader/SpreadsheetReader.php');

class MarketingKompetitorController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '5';
  }

  public function Index(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {

      $data['id_menu'] = $this->mydata['id_menu'];

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        $competitor = new MarketingKompetitor();
        $prodi      = new Prodi();
        for ($i = date("Y"); $i >= (date("Y") - 4); $i--) {
          $listTahunTerakhir[] = $i;
        }
        $data["competitorName"] = $competitor->select("competitor_name")->groupBy("competitor_name")->orderBy("competitor_name", "ASC")->findAll();
        $data["competitorProdi"] = $prodi->select("NamaProdi")->whereNotIn("KodeFakultas", ["50, 99"])->groupBy("NamaProdi")->orderBy("NamaProdi", "ASC")->findAll();
        $data["competitorTahunTerakhir"] = $listTahunTerakhir;

        return $this->blade->render("pages.marketing_kompetitor.index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function ViewUpload(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      
      $data['id_menu'] = "26";

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        return $this->blade->render("pages.marketing_kompetitor.upload", $data);
      } else {
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
        return $this->blade->render("pages.marketing_kompetitor.res", $data);
      } else {
        return redirect()->to('/univeritas');
      }
    }
  }

  public function Read(){
    $id_menu  = $this->mydata['id_menu'];
    $tableMarketingKompetitor = new MarketingKompetitor();

    $datatables   = new MyDatatables();
    $columnOrder  = [
      "", "competitor_name", "competitor_fakultas", "competitor_jurusan", 'competitor_value', 'tahun_akademik'
    ];
    $columnSearch = [
      "competitor_name", "competitor_fakultas", "competitor_jurusan", 'competitor_value', 'tahun_akademik'
    ];

    $query = $tableMarketingKompetitor->select('tr_competitor.*');
    // ->where('tr_competitor.status','1');
    $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
    $data    = [];

    foreach ($getList as $list) {

      $list_btn = '<div class="dropdown table-btn-group d-flex">
                    <button class="btn btn-outline-primary border-0 dropdown-toggle rounded-circle m-auto" type="button" id="btnTableGrup" data-bs-toggle="dropdown" aria-expanded="false" style="height: 40px; width: 40px">
                      <i class="mdi mdi-menu"></i>
                    </button>';

       $list_btn .= "<ul class='dropdown-menu' aria-labelledby='btnTableGroup'>";
      
      if(acc_update(session('level_id'),$id_menu)=="1"){ 
        //Edit
        $list_btn .= "<li>
                        <button class='btn btn-sm text-nowrap m-1' onclick='Go(\"marketing_kompetitor\",\"Marketing / Kompetitor\",\"edit\",\"Ubah\",\"".$list->competitor_id."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
                      </li>"; 
      }
      
      if(acc_delete(session('level_id'),$id_menu)=="1"){
        //Delete
        $list_btn .= "<li>
                        <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->competitor_id."\", \"Hapus Marketing/Kompetitor\", \"Apakah anda yakin ingin hapus data Marketing/Kompetitor?\", \"warning\", \"" . base_url() . "/marketing_kompetitor/delete\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
                      </li>"; 
      }

      $list_btn .="</ul>
                </div>";

                  $row                   = [];
            $row  ["competitor_id"]     = $list->competitor_id;
            $row  ["competitorName"]     = $list->competitor_name;
            $row  ["competitorFakultas"] = $list->competitor_fakultas;
            $row  ["competitorProdi"]    = $list->competitor_jurusan;
            $row  ["competitorValue"]    = int_to_rp($list->competitor_value/1000000);
            $row  ["tahunAkademik"]      = $list->tahun_akademik;
            $row  ["competitorAction"]   = $list_btn;
            $data[]                      = $row;
    }
    echo $datatables->response($data);
  }

  public function Create() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      return $this->blade->render("pages.marketing_kompetitor.create");
    }
  }

  public function Save(){
    $generate = "markom_" . date("ymdhis") . "_" . uniqid(); 
    $tableMarketingKompetitor = new MarketingKompetitor;

    $data = [
      "competitor_id"       => $generate,
      "competitor_name"     => $this->request->getPost("competitor_name"),
      "competitor_fakultas" => $this->request->getPost("competitor_fakultas"),
      "competitor_jurusan"  => $this->request->getPost("competitor_prodi"),
      "competitor_value"    => $this->request->getPost("competitor_value"),
      "tahun_akademik"      => $this->request->getPost("tahun_akademik"),
      "created_by"          => session('session_id'),
      "updated_by"          => session('session_id')
    ];

    //Proses Save
    try{
          $tableMarketingKompetitor->save($data);
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
        }
        catch(\Exception $e){
          // do task when error
          print_r($tableMarketingKompetitor->errors());die();
         $err = "";
         if (!empty($tableMarketingKompetitor->errors())) {
            foreach ($tableMarketingKompetitor->errors() as $error) {
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

  public function Edit() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $id                       = $this->request->getPost('parameter');
      $tableMarketingKompetitor = new MarketingKompetitor;
      
      $data["data"]                 = $tableMarketingKompetitor->find($id);
      
      return $this->blade->render("pages.marketing_kompetitor.edit", $data);
    }
  }

  public function Update(){
    $id  = $this->request->getPost('parameter');
    $tableMarketingKompetitor = new MarketingKompetitor;

    $check = $tableMarketingKompetitor->where('status',1)->where('competitor_id', $id)->first();
    if(!empty($check)){
        $data = [
          "competitor_name"     => $this->request->getPost("competitor_name"),
          "competitor_fakultas" => $this->request->getPost("competitor_fakultas"),
          "competitor_jurusan"  => $this->request->getPost("competitor_prodi"),
          "competitor_value"    => $this->request->getPost("competitor_value"),
          "tahun_akademik"      => $this->request->getPost("tahun_akademik"),
          "created_by"          => session('session_id'),
          "updated_by"          => session('session_id')
       ];
       
       //Proses Update
       try{
            $tableMarketingKompetitor->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($tableMarketingKompetitor->errors())) {
              foreach ($tableMarketingKompetitor->errors() as $error) {
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
            "message"           => "Data marketing kompetitor tidak tersedia"
        ]);
        die();
    }
  } 

  public function Delete(){
    $id                       = $this->request->getPost("id");
    $tableMarketingKompetitor = new MarketingKompetitor();
    $check                    = $tableMarketingKompetitor->where('competitor_id',$id)->first();
    if(empty($check)){
      echo json_encode([
              "status"           => "NOK",
              "message"          => "Data marketing/kompetitor tidak tersedia"
          ]);
      die();
    }

     $data = [
          "status"                => "0",
          "updated_by"            => session('session_id')
       ];

      try{
          $tableMarketingKompetitor->update($id, $data);
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

  public function DeleteSelected(){
    $params = $this->request->getPost("params");
    $tableMarketingKompetitor = new MarketingKompetitor();
    foreach($params as $key => $value){
      $check = $tableMarketingKompetitor->where('competitor_id',$value)->first();
      if(empty($check)){
        echo json_encode([
                "status"           => "NOK",
                "message"          => "Data marketing/kompetitor tidak tersedia"
            ]);
        die();
      } else{
        $tableMarketingKompetitor->delete(['competitor_id' => $value]);
      }
    }
    echo json_encode([
        "status"            => "OK",
        "message"           => "Success"
    ]);
  }

  public function Import()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      return $this->blade->render("pages.marketing_kompetitor.import");
    }
  }

  public function ImportInsertData_Preview()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_marketingkompetitor_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
      $upload_dir       = $path . $fileName;

      move_uploaded_file($_FILES['excel_file']['tmp_name'], $upload_dir);
      $Reader = new \SpreadsheetReader($upload_dir);

      $arr = [];
      $total = 0;
      $duplikasi = 0;
      $num = 0;

      foreach ($Reader as $Key => $Row) {
        if ($Key < 2) continue;
        if (!empty($Row[0])) {
          $competitor_name     = $Row['0'];
          $competitor_fakultas = $Row['1'];
          $competitor_jurusan  = $Row['2'];
          $competitor_value    = $Row['3'];
          $tahun_akademik      = $Row['4'];

          $duplicate = "";
          $count = 0; // Check database if data must unique
          if ($count > 0) {
            $duplicate = "exist_data"; // Css Class if data exist in database
            $duplikasi++;
          } else {
            $total++;
          }
          $num++;

          $new = array(
            'competitor_name'     => $competitor_name,
            'competitor_fakultas' => $competitor_fakultas,
            'competitor_jurusan'  => $competitor_jurusan,
            'competitor_value'    => $competitor_value,
            'tahun_akademik'      => $tahun_akademik,
            'no'                  => $num,
            'duplicate'           => $duplicate
          );
          $arr[] = $new;
        }
      }

      $data['total_duplikasi']  = $duplikasi;
      $data['total_import']     = $total;
      $data['total']            = $total + $duplikasi;
      $data['import']           = $arr;
      @unlink($upload_dir); //remove file excel

      return $this->blade->render("pages.marketing_kompetitor.import_insert_marketingkompetitor_preview", $data);
    }
  }

  public function ImportInsertData_Proses()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_marketingkompetitor_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
      $upload_dir       = $path . $fileName;

      move_uploaded_file($_FILES['excel_file']['tmp_name'], $upload_dir);
      $Reader     = new \SpreadsheetReader($upload_dir);
      $kompetitor = new MarketingKompetitor();

      $arr = [];
      $total = 0;
      $duplikasi = 0;
      $num = 0;

      foreach ($Reader as $Key => $Row) {
        if ($Key < 2) continue;
        if (!empty($Row[0])) {
          $competitor_name     = $Row['0'];
          $competitor_fakultas = $Row['1'];
          $competitor_jurusan  = $Row['2'];
          $competitor_value    = $Row['3'];
          $tahun_akademik      = $Row['4'];

          $check = $kompetitor->where([
            "competitor_name"     => $competitor_name,
            "competitor_fakultas" => $competitor_fakultas,
            "competitor_jurusan"  => $competitor_jurusan,
            "tahun_akademik"      => $tahun_akademik
          ])->first();

          if ($check) {
            $kompetitor->update($check["competitor_id"], [
              "competitor_value" => $competitor_value,
              "status"           => "1",
              "updated_by"       => session("session_id")
            ]);
          } else {
            $count = 0; // Check database if data must unique
            if ($count == 0) {

              $generate = "markom_" . date("ymdhis") . "_" . uniqid();
              $markom = new MarketingKompetitor();

              $data = [
                "competitor_id"       => $generate,
                "competitor_name"     => $competitor_name,
                "competitor_fakultas" => $competitor_fakultas,
                "competitor_jurusan"  => $competitor_jurusan,
                "competitor_value"    => (int)$competitor_value,
                "tahun_akademik"      => $tahun_akademik,
                "status"              => "1",
                "created_by"          => session("session_id"),
                "updated_by"          => session("session_id")
              ];

              //Proses Save
              $markom->save($data);
            }
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

  public function ChartMarketingKompetitor()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data["id_menu"] = $this->mydata["id_menu"];

      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        // GET NAMA COMPETITOR, PRODI SELECTED
        $namaCompetitor = $this->request->getPost('namacompetitor');
        $namaProdi      = $this->request->getPost('namaprodi');
        $tahunTerakhir  = $this->request->getPost('tahunterakhir');

        // PANGGIL MODEL
        $viewMarketingCompetitorProdi = new ViewMarketingCompetitorProdi();

        // CEK APAKAH MEMILIH COMPETITOR
        if ($namaCompetitor == "") {
          $getNamaCompetitor = $viewMarketingCompetitorProdi->select("NamaCompetitor")->where("NamaProdi", "S1 - Manajemen")->groupBy("NamaCompetitor")->orderBy("NamaCompetitor", "ASC")->findAll(5);

          if (empty($getNamaCompetitor)) {
            $getNamaCompetitor = $viewMarketingCompetitorProdi->select("NamaCompetitor")->groupBy("NamaCompetitor")->orderBy("NamaCompetitor", "ASC")->findAll(5);

            foreach ($getNamaCompetitor as $item) {
              $NamaCompetitor[] = $item["NamaCompetitor"];
            }
          } else {
            foreach ($getNamaCompetitor as $item) {
              $NamaCompetitor[] = $item["NamaCompetitor"];
            }
          }
          
        } else {
          foreach ($namaCompetitor as $item) {
            $NamaCompetitor[] = $item;
          }

          if (!in_array("Universitas Atmajaya",$NamaCompetitor)){
            array_push($NamaCompetitor,"Universitas Atmajaya");
          }
        }

        // CEK APAKAH MEMILIH PRODI
        if ($namaProdi == "") {
          $namaProdi = "S1 - Manajemen";
        }

        // VARIABEL UNTUK CHART
        $chartMarketingCompetitor = array();

        // VARIABEL UNTUK INDEXING
        $i = 0;

        // MEMBUAT VARIABEL 5 TAHUN TERAKHIR
        $thn = 0;
        $listTahun = [];
        if ($tahunTerakhir == "") {
          $tahunTerakhir = date("Y");
        }
        for ($thn = ($tahunTerakhir - 4); $thn <= $tahunTerakhir; $thn++) {
          $listTahun[] = $thn;
        }

        // START MAIN FUNCTION
        if (!empty($NamaCompetitor)) {
          foreach ($NamaCompetitor as $item) {
            $chartMarketingCompetitor[$i]["name"] = $item;

            $data_array = array();
            for ($perthn = 0; $perthn <= 4; $perthn++) {
              $getValue = $viewMarketingCompetitorProdi->select("TotalDanaKuliah")->where(["NamaCompetitor" => $item, "NamaProdi" => $namaProdi, "Tahun" => $listTahun[$perthn]])->first();
              if (empty($getValue)) {
                array_push($data_array, 0);
              } else {
                array_push($data_array, int_to_rp((int)$getValue["TotalDanaKuliah"] / 1000000));
              }
            }

            $chartMarketingCompetitor[$i]["data"] = $data_array;

            $i++;
          }
        }
        // END MAIN FUNCTION

        $LastFiveYears = [];
        for ($i = 0; $i <= 4; $i++) {
          $LastFiveYears[] = [
            "$listTahun[$i]"
          ];
        }

        // DATA YANG AKAN DIKIRIM KE CHART
        $data["chartNamaProdi"]           = $namaProdi;
        $data["chartMarketingCompetitor"] = json_encode($chartMarketingCompetitor);
        $data["LastFiveYears"]            = json_encode($LastFiveYears);
        $data["LFY"]                      = $listTahun;

        return $this->blade->render("pages.marketing_kompetitor.chart", $data);
      } else {
        return redirect()->to('/universitas');
      }

    }
  }

}
