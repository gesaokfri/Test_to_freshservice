<?php

namespace App\Controllers\Dashboard;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;

use App\Models\Budget;

require('App/ThirdParty/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('App/ThirdParty/spreadsheet-reader/SpreadsheetReader.php');

class AnggaranController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '22';
  }

  public function Index(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        return $this->blade->render("pages.anggaran.index", $data);
      } else {
        return redirect()->to('/universitas');
      }
    }
  }

  public function ViewUpload(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = "25";

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        return $this->blade->render("pages.anggaran.upload", $data);
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
        return $this->blade->render("pages/anggaran/res", $data);
      } else {
        return redirect()->to('/univeritas');
      }
    }
  }

  public function Read(){
    $id_menu  = $this->mydata['id_menu'];
    $tableAnggaran = new Budget();

    $datatables   = new MyDatatables();
    $columnOrder  = [
      "", "budget_type", "budget_period", "budget_value" 
    ];
    $columnSearch = [
      "budget_type", "budget_period", "budget_value"
    ];

    $query   = $tableAnggaran->select('ms_budget.*')->where('budget_group','1');
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
                        <button class='btn btn-sm text-nowrap m-1' onclick='Go(\"anggaran\",\"Anggaran\",\"edit\",\"Ubah\",\"".$list->budget_id."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
                      </li>"; 
      }
      
      if(acc_delete(session('level_id'),$id_menu)=="1"){
        //Delete
        $list_btn .= "<li>
                        <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->budget_id."\", \"Hapus Anggaran\", \"Apakah anda yakin ingin hapus data Anggaran?\", \"warning\", \"" . base_url() . "/marketing_kompetitor/delete\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
                      </li>"; 
      }

      $list_btn .="</ul>
                </div>";

           
            $row                         = [];
            $row  ["budget_id"]          = $list->budget_id;
            $row  ["budget_type"]        = anggaran_type($list->budget_type);
            $row  ["budget_period"]      = $list->budget_period;
            $row  ["budget_value"]       = int_to_rp($list->budget_value/1000000);
            $row  ["budgetAction"]       = $list_btn;
            $data[]                      = $row;
    }
    echo $datatables->response($data);
  }


  public function Edit() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $id                       = $this->request->getPost('parameter');
      $tableAnggaran            = new Budget;
      $data["data"]             = $tableAnggaran->find($id);
      
      return $this->blade->render("pages/anggaran/edit", $data);
    }
  }

  public function Update(){
    $id  = $this->request->getPost('parameter');
    $tableAnggaran = new Budget;

    $check = $tableAnggaran->where('budget_group',1)->where('budget_id', $id)->first();
    if(!empty($check)){
        $data = [
          "budget_period"       => $this->request->getPost("budget_period"),
          "budget_type"         => $this->request->getPost("tipe"),
          "budget_value"        => $this->request->getPost("budget_value"),
          "created_by"          => session('session_id'),
          "updated_by"          => session('session_id')
       ];
       
       //Proses Update
       try{
            $tableAnggaran->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($tableAnggaran->errors())) {
              foreach ($tableAnggaran->errors() as $error) {
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
            "message"           => "Data anggaran tidak tersedia"
        ]);
        die();
    }
  } 

  public function Delete(){
    $id                       = $this->request->getPost("id");
    $tableAnggaran            = new Budget();
    $check                    = $tableAnggaran->where('budget_id',$id)->first();
    if(empty($check)){
      echo json_encode([
              "status"           => "NOK",
              "message"          => "Data anggaran tidak tersedia"
          ]);
      die();
    }

     $data = [
          "status"                => "0",
          "updated_by"            => session('session_id')
       ];

      try{
          $tableAnggaran->delete($id);
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
    $tableAnggaran = new Budget();
    foreach($params as $key => $value){
      $check = $tableAnggaran->where('budget_id',$value)->first();
      if(empty($check)){
        echo json_encode([
                "status"           => "NOK",
                "message"          => "Data anggaran tidak tersedia"
            ]);
        die();
      } else{
        $tableAnggaran->delete(['budget_id' => $value]);
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
      return $this->blade->render("pages/anggaran/import");
    }
  }

  public function ImportInsertData_Preview()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_anggaran_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
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
          $anggaran_periode     = $Row['0'];
          $anggaran_pendapatan  = $Row['1'];
          $anggaran_beban       = $Row['2'];
          $anggaran_gedung      = $Row['3'];
          $anggaran_peralatan   = $Row['4'];
          $anggaran_tanah       = $Row['5'];

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
            'anggaran_periode'     => $anggaran_periode,
            'anggaran_pendapatan'  => $anggaran_pendapatan,
            'anggaran_beban'       => $anggaran_beban,
            'anggaran_gedung'      => $anggaran_gedung,
            'anggaran_peralatan'   => $anggaran_peralatan,
            'anggaran_tanah'       => $anggaran_tanah,
            'no'                   => $num,
            'duplicate'            => $duplicate
          );
          $arr[] = $new;
        }
      }

      $data['total_duplikasi']  = $duplikasi;
      $data['total_import']     = $total;
      $data['total']            = $total + $duplikasi;
      $data['import']           = $arr;
      @unlink($upload_dir); //remove file excel

      return $this->blade->render("pages/anggaran/import_insert_anggaran_preview", $data);
    }
  }

  public function ImportInsertData_Proses()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_anggaran_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
      $upload_dir       = $path . $fileName;

      move_uploaded_file($_FILES['excel_file']['tmp_name'], $upload_dir);
      $Reader     = new \SpreadsheetReader($upload_dir);
      $anggaran   = new Budget();

      $arr = [];
      $total = 0;
      $duplikasi = 0;
      $num = 0;

      foreach ($Reader as $Key => $Row) {
        if ($Key < 2) continue;
        if (!empty($Row[0])) {
          $anggaran_periode     = $Row['0'];
          $anggaran_pendapatan  = $Row['1'];
          $anggaran_beban       = $Row['2'];
          $anggaran_gedung      = $Row['3'];
          $anggaran_peralatan   = $Row['4'];
          $anggaran_tanah       = $Row['5'];

          if(!empty($anggaran_pendapatan)){
              $check = $anggaran->where([
                "budget_period"       => $anggaran_periode,
                "budget_type"         => 1,
                "budget_group"        => 1    //Unika
              ])->first();
                if ($check) {
                  $anggaran->update($check["budget_id"], [
                    "budget_value"      => $anggaran_pendapatan,
                    "updated_by"        => session("session_id")
                  ]);
                } else {
                  $count = 0; // Check database if data must unique
                  if ($count == 0) {
                    $generate = "bdg_".date("ymdhis")."_".uniqid();
                    $arr = array();
                    $arr['id']      = $generate;
                    $arr['periode'] = $anggaran_periode;
                    $arr['value']   = $anggaran_pendapatan;
                    $arr['type']    = 1;
                    $arr['group']   = 1;
                    $this->ImportInsertData($arr);
                  }
                }
          }

          if(!empty($anggaran_beban)){
              $check = $anggaran->where([
                "budget_period"       => $anggaran_periode,
                "budget_type"         => 2,
                "budget_group"        => 1   //Unika
              ])->first();
                if ($check) {
                  $anggaran->update($check["budget_id"], [
                    "budget_value"      => $anggaran_beban,
                    "updated_by"        => session("session_id")
                  ]);
                } else {
                  $count = 0; // Check database if data must unique
                  if ($count == 0) {
                    $generate = "bdg_".date("ymdhis")."_".uniqid();
                    $arr = array();
                    $arr['id']      = $generate;
                    $arr['periode'] = $anggaran_periode;
                    $arr['value']   = $anggaran_beban;
                    $arr['type']    = 2;
                    $arr['group']   = 1;
                    $this->ImportInsertData($arr);
                  }
                }
          }

          if(!empty($anggaran_gedung)){
              $check = $anggaran->where([
                "budget_period"       => $anggaran_periode,
                "budget_type"         => 3,
                "budget_group"        => 1   //Unika
              ])->first();
                if ($check) {
                  $anggaran->update($check["budget_id"], [
                    "budget_value"      => $anggaran_gedung,
                    "updated_by"        => session("session_id")
                  ]);
                } else {
                  $count = 0; // Check database if data must unique
                  if ($count == 0) {
                    $generate = "bdg_".date("ymdhis")."_".uniqid();
                    $arr = array();
                    $arr['id']      = $generate;
                    $arr['periode'] = $anggaran_periode;
                    $arr['value']   = $anggaran_gedung;
                    $arr['type']    = 3;
                    $arr['group']   = 1;
                    $this->ImportInsertData($arr);
                  }
                }
          }

          if(!empty($anggaran_peralatan)){
              $check = $anggaran->where([
                "budget_period"       => $anggaran_periode,
                "budget_type"         => 4,
                "budget_group"        => 1   //Unika
              ])->first();
                if ($check) {
                  $anggaran->update($check["budget_id"], [
                    "budget_value"      => $anggaran_peralatan,
                    "updated_by"        => session("session_id")
                  ]);
                } else {
                  $count = 0; // Check database if data must unique
                  if ($count == 0) {
                    $generate = "bdg_".date("ymdhis")."_".uniqid();
                    $arr = array();
                    $arr['id']      = $generate;
                    $arr['periode'] = $anggaran_periode;
                    $arr['value']   = $anggaran_peralatan;
                    $arr['type']    = 4;
                    $arr['group']   = 1;
                    $this->ImportInsertData($arr);
                  }
                }
          }

          if(!empty($anggaran_tanah)){
              $check = $anggaran->where([
                "budget_period"       => $anggaran_periode,
                "budget_type"         => 5,
                "budget_group"        => 1   //Unika
              ])->first();
                if ($check) {
                  $anggaran->update($check["budget_id"], [
                    "budget_value"      => $anggaran_tanah,
                    "updated_by"        => session("session_id")
                  ]);
                } else {
                  $count = 0; // Check database if data must unique
                  if ($count == 0) {
                    $generate = "bdg_".date("ymdhis")."_".uniqid();
                    $arr = array();
                    $arr['id']      = $generate;
                    $arr['periode'] = $anggaran_periode;
                    $arr['value']   = $anggaran_peralatan;
                    $arr['type']    = 5;
                    $arr['group']   = 1;
                    $this->ImportInsertData($arr);
                  }
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

  public function ImportInsertData($get)
  {
      $anggaran = new Budget();

      $data = [
        "budget_id"           => $get['id'],
        "budget_period"       => $get['periode'],
        "budget_value"        => $get['value'],
        "budget_type"         => $get['type'],
        "budget_group"        => $get['group'],
        "created_by"          => session("session_id"),
        "updated_by"          => session("session_id")
      ];
      //Proses Save
      $anggaran->save($data);
  }


}
