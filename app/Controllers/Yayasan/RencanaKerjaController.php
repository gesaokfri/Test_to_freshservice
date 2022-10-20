<?php

namespace App\Controllers\Yayasan;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;

use App\Models\RencanaKerja;
use App\Models\RencanaKerjaDetail;
use App\Models\ViewUnit;

require('App/ThirdParty/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('App/ThirdParty/spreadsheet-reader/SpreadsheetReader.php');

class RencanaKerjaController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '17';
  }

  public function Index(){
     $tableRencanaKerja = new RencanaKerja;
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        return $this->blade->render("yayasan.pages.rencana_kerja.index", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function ViewUpload(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = "30";

      if(acc_read(session('level_id'),$data['id_menu'])=="1") {
        return $this->blade->render("yayasan.pages.rencana_kerja.upload", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Res(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        return $this->blade->render("yayasan.pages.rencana_kerja.container", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Read(){
    $id_menu  = $this->mydata['id_menu'];
    $tableRencanaKerja = new RencanaKerja();

    $datatables   = new MyDatatables();
    $columnOrder  = [
      "", "rencana_kerja_no", "rencana_kerja_group", "rencana_kerja_name", "rencana_kerja_pic",  "rencana_kerja_kegiatan"
    ];
    $columnSearch = [
      "rencana_kerja_no", "rencana_kerja_group", "rencana_kerja_name", "rencana_kerja_pic",  "rencana_kerja_kegiatan"
    ];

    $query = $tableRencanaKerja->select('tr_rencana_kerja.*')->where('rencana_kerja_type','1');
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
                        <button class='btn btn-sm text-nowrap m-1' onclick='Go(\"rencana_kerja\",\"Rencana Kerja\",\"edit\",\"Ubah\",\"".$list->rencana_kerja_id."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
                      </li>"; 
      }
      
      // if(acc_delete(session('level_id'),$id_menu)=="1"){
      //   //Delete
      //   $list_btn .= "<li>
      //                   <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->rencana_kerja_id."\", \"Hapus Rencana Kerja\", \"Apakah anda yakin ingin hapus data <br/> Rencana Kerja ?\", \"warning\", \"" . base_url() . "/yayasan/rencana_kerja/delete\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
      //                 </li>"; 
      // }

      $list_btn .="</ul>
                </div>";

            $getUnit = "<center>-</center>";  
            if(!empty($list->rencana_kerja_unit)){
              $unit    = new ViewUnit;
              $unitRes = $unit->where('Unit',$list->rencana_kerja_unit)->first();
              if(!empty($unitRes)){
                 $getUnit = $unitRes['DeskripsiUnit'];
              }
            } 

            $getGroup = "<center>-</center>";  
            if(!empty($list->rencana_kerja_group)){
              $getGroup = rencana_kerja_group($list->rencana_kerja_group);
            }  

            $row                      = [];
            $row["rkId"]              = $list->rencana_kerja_id;
            $row["rkNo"]              = $list->rencana_kerja_no;
            $row["rkGroup"]           = $getGroup;
            $row["rkKegiatan"]        = $list->rencana_kerja_kegiatan;
            $row["rkPic"]             = $list->rencana_kerja_pic;
            $row["rkAction"]          = $list_btn;
            $row["rkName"]            = "<a title='Detail Rencana Kerja' style='color:blue;cursor:pointer' onclick='DetailRencanaKerja(\"".$list->rencana_kerja_id."\")'>".$list->rencana_kerja_name."</a>";
            $data[]                     = $row;
    }
    echo $datatables->response($data);
  }

  public function Create() {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $unit = new ViewUnit;
      $data['listUnit'] = $unit->findAll();
      return $this->blade->render("yayasan.pages.rencana_kerja.create", $data);
    }
  }

  public function Save(){
    $tableRencanaKerja = new RencanaKerja;
    $generate = "rk_" . date("ymdhis") . "_" . uniqid();
    
    $check    = $tableRencanaKerja->select('rencana_kerja_no')
                           ->where('month(created_at)',date('m'))
                           ->where('year(created_at)',date('Y'))
                           ->OrderBy('rencana_kerja_no','desc')
                           ->first();
    if(!empty($check)){
       $arr         = explode("-",$check['rencana_kerja_no']);
       $generate_no = $arr[0]."-".str_pad($arr[1] + 1, 1, 0, STR_PAD_LEFT);
    }
    else {
       $generate_no = get_quarter().date('y')."-1";
    }

    $data = [
      "rencana_kerja_id"        => $generate,
      "rencana_kerja_no"        => $generate_no,
      "rencana_kerja_tahun"     => date('Y'),
      "rencana_kerja_group"     => $this->request->getPost("rencana_kerja_group"),
      "rencana_kerja_name"      => $this->request->getPost("rencana_kerja_name"),
      "rencana_kerja_kegiatan"  => $this->request->getPost("rencana_kerja_kegiatan"),
      "rencana_kerja_pic"       => $this->request->getPost("rencana_kerja_pic"),
      "rencana_kerja_type"      => "1",
      "created_by"              => session('session_id'),
      "updated_by"              => session('session_id')
    ];


    //Proses Save
    try{
          $tableRencanaKerja->save($data);
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
        }
        catch(\Exception $e){
          // do task when error
          print_r($tableRencanaKerja->errors());die();
         $err = "";
         if (!empty($tableRencanaKerja->errors())) {
            foreach ($tableRencanaKerja->errors() as $error) {
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
      $tableRencanaKerja        = new RencanaKerja;
      $unit                     = new ViewUnit;
      $data['listUnit']         = $unit->findAll();
      $data["data"]             = $tableRencanaKerja->find($id);
      
      return $this->blade->render("yayasan.pages.rencana_kerja.edit", $data);
    }
  }

  public function Update(){
    $id  = $this->request->getPost('parameter');
    $tableRencanaKerja = new RencanaKerja;

    $check = $tableRencanaKerja->where('rencana_kerja_id', $id)->first();
    if(!empty($check)){
        $data = [
          "rencana_kerja_group"     => $this->request->getPost("rencana_kerja_group"),
          "rencana_kerja_name"      => $this->request->getPost("rencana_kerja_name"),
          "rencana_kerja_kegiatan"  => $this->request->getPost("rencana_kerja_kegiatan"),
          "rencana_kerja_pic"       => $this->request->getPost("rencana_kerja_pic"),
          "updated_by"              => session('session_id')
        ];

       //Proses Update
       try{
            $tableRencanaKerja->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($tableRencanaKerja->errors())) {
              foreach ($tableRencanaKerja->errors() as $error) {
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
            "message"           => "Data rencana kerja tidak tersedia"
        ]);
        die();
    }
  } 

  public function Delete(){
    $id                       = $this->request->getPost("id");
    $tableRencanaKerja        = new RencanaKerja();
    $tableRencanaKerjaDetail  = new RencanaKerjaDetail();
    $check                    = $tableRencanaKerja->where('rencana_kerja_type','1')
                                                  ->where('rencana_kerja_id',$id)->first();
    if(empty($check)){
      echo json_encode([
              "status"           => "NOK",
              "message"          => "Data rencana kerja tidak tersedia"
          ]);
      die();
    }

    try{
        $tableRencanaKerja->delete(['rencana_kerja_id' => $id]);
        $tableRencanaKerjaDetail->where('rencana_kerja_id',$id)->delete();
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
    $tableRencanaKerja = new RencanaKerja();
    $tableRencanaKerjaDetail  = new RencanaKerjaDetail();
    foreach($params as $key => $value){
      $check = $tableRencanaKerja->where('rencana_kerja_type','1')
                                 ->where('rencana_kerja_id',$value)->first();
      if(empty($check)){
        echo json_encode([
                "status"           => "NOK",
                "message"          => "Data rencana kerja tidak tersedia"
            ]);
        die();
      } else{
        $tableRencanaKerja->delete(['rencana_kerja_id' => $value]);
        $tableRencanaKerjaDetail->where('rencana_kerja_id',$value)->delete();
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
      return $this->blade->render("yayasan.pages.rencana_kerja.import");
    }
  }

  public function ImportInsertData_Preview()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_rencana_kerja_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
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
          $rj_type        = $Row['0'];
          $rj_tahun       = $Row['1'];
          $rj_no          = $Row['2'];
          $rj_group       = $Row['3'];
          $rj_rencana     = $Row['4'];
          $rj_pic         = $Row['5'];
          $rj_kegiatan    = $Row['6'];

          $duplicate = "";
          $RencanaKerja = new RencanaKerja();
          $count = $RencanaKerja->select('count(*) as total')
                                ->where("rencana_kerja_type",$rj_type)
                                ->where("rencana_kerja_group",$rj_group)
                                ->where("rencana_kerja_tahun",$rj_tahun)
                                ->where("rencana_kerja_no",$rj_no)
                                ->first();
          if ($count['total'] > 0) {
            $duplicate = "exist_data"; // Css Class if data exist in database
            $duplikasi++;
          } else {
            $total++;
          }
          $num++;

          $new = array(
            'rj_type'             => rencana_kerja_type($rj_type),
            'rj_tahun'            => $rj_tahun,
            'rj_no'               => $rj_no,
            'rj_group'            => rencana_kerja_group($rj_group),
            'rj_rencana'          => $rj_rencana,
            'rj_pic'              => $rj_pic,
            'rj_kegiatan'         => $rj_kegiatan,
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

      return $this->blade->render("yayasan.pages.rencana_kerja.import_insert_rencana_kerja_preview", $data);
    }
  }

  public function ImportInsertData_Proses()
  {
    $file_ext = array('xls', 'csv');
    if (isset($_FILES['excel_file']['name'])) {

      //upload file excel
      $path             = "./import/upload/xls/";
      $ext              = strtolower(pathinfo($_FILES["excel_file"]["name"])['extension']);
      $fileName         = "import_rencana_kerja_" . date("ymdhis") . "_" . rand(10000, 99999) . "." . $ext;
      $upload_dir       = $path . $fileName;

      move_uploaded_file($_FILES['excel_file']['tmp_name'], $upload_dir);
      $Reader       = new \SpreadsheetReader($upload_dir);
      $RencanaKerja = new RencanaKerja();

      $arr = [];
      $total = 0;
      $duplikasi = 0;
      $num = 0;

      foreach ($Reader as $Key => $Row) {
        if ($Key < 2) continue;
        if (!empty($Row[0])) {
          $rj_type        = $Row['0'];
          $rj_tahun       = $Row['1'];
          $rj_no          = $Row['2'];
          $rj_group       = $Row['3'];
          $rj_rencana     = $Row['4'];
          $rj_pic         = $Row['5'];
          $rj_kegiatan    = $Row['6'];

         
            $count = $RencanaKerja->select('count(*) as total')
                                ->where("rencana_kerja_type",$rj_type)
                                ->where("rencana_kerja_group",$rj_group)
                                ->where("rencana_kerja_tahun",$rj_tahun)
                                ->where("rencana_kerja_no",$rj_no)
                                ->first();

            if ($count['total'] == 0) {
              $generate = "rk_" . date("ymdhis") . "_" . uniqid();
              $rk = new RencanaKerja();

              $data = [
                "rencana_kerja_id"        => $generate,
                "rencana_kerja_type"      => $rj_type,
                "rencana_kerja_no"        => $rj_no,
                "rencana_kerja_tahun"     => $rj_tahun,
                "rencana_kerja_group"     => $rj_group,
                "rencana_kerja_name"      => $rj_rencana,
                "rencana_kerja_kegiatan"  => $rj_kegiatan,
                "rencana_kerja_pic"       => $rj_pic,
                "created_by"              => session("session_id"),
                "updated_by"              => session("session_id")
              ];

              //Proses Save
              $rk->save($data);
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

}
