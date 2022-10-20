<?php

namespace App\Controllers\Yayasan;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;

use App\Models\RencanaKerja;
use App\Models\RencanaKerjaDetail;
use App\Models\ViewUnit;

require('App/ThirdParty/spreadsheet-reader/php-excel-reader/excel_reader2.php');
require('App/ThirdParty/spreadsheet-reader/SpreadsheetReader.php');

class RencanaKerjaDetailController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '17';
  }


  public function Res(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {
        $data['parameter'] = $this->request->getPost("parameter");
        return $this->blade->render("yayasan.pages.rencana_kerja_detail.container", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Read(){
    $id_menu   = $this->mydata['id_menu'];
    $parameter = $this->request->getPost("parameter");
    $tableRencanaKerjaDetail = new RencanaKerjaDetail();

    $datatables   = new MyDatatables();
    $columnOrder  = [
      "", "rencana_kerja_detail_quarter", "rencana_kerja_detail_pencapaian", "rencana_kerja_detail_status", "rencana_kerja_verifikasi"
    ];
    $columnSearch = [
      "rencana_kerja_detail_quarter", "rencana_kerja_detail_pencapaian", "rencana_kerja_detail_status",
      "rencana_kerja_verifikasi"
    ];

    $query = $tableRencanaKerjaDetail->select('tr_rencana_kerja_detail.*')
                                     ->where('rencana_kerja_id',$parameter);
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
                        <button class='btn btn-sm text-nowrap m-1' onclick='edit(\"".$list->rencana_kerja_detail_id."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
                      </li>"; 
      }
      
      if(acc_delete(session('level_id'),$id_menu)=="1"){
        //Delete
        if($list->rencana_kerja_verifikasi=="0") { // Pending
          $list_btn .= "<li>
                        <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->rencana_kerja_detail_id."\", \"Hapus Detail Rencana Kerja \", \"Apakah anda yakin ingin hapus data <br/> Detail Rencana Kerja ?\", \"warning\", \"" . base_url() . "/yayasan/rencana_kerja_detail/delete\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
                      </li>"; 
         }
      }

      $list_btn .="</ul>
                </div>";

      if($list->rencana_kerja_verifikasi=="1") {
        $list_btn="";
      }         
            $row                      = [];
            $row["rkId"]              = $list->rencana_kerja_detail_id;
            $row["rkQuarter"]         = $list->rencana_kerja_detail_quarter;
            $row["rkStatus"]          = $list->rencana_kerja_detail_status;
            $row["rkVerifikasi"]      = "<a title='Detail Rencana Kerja' style='color:blue;cursor:pointer' onclick='detail(\"".$list->rencana_kerja_detail_id."\",\"verifikasi\")'>".rencana_kerja_verify($list->rencana_kerja_verifikasi)."</a>";
            $row["rkAction"]          = $list_btn;
            $row["rkPencapaian"]      = "<a title='Detail Rencana Kerja' style='color:blue;cursor:pointer' onclick='detail(\"".$list->rencana_kerja_detail_id."\")'>".$list->rencana_kerja_detail_pencapaian."</a>";
            $data[]                     = $row;
    }
    echo $datatables->response($data);
  }

  public function Save(){
    $tableRencanaKerjaDetail = new RencanaKerjaDetail;
    $parameter =  $this->request->getPost("rencana_kerja_id");
    $generate  = "rkdt_" . date("ymdhis") . "_" . uniqid();
    
    $check     = $tableRencanaKerjaDetail->where('month(created_at)',date('m'))
                           ->where('year(created_at)',date('Y'))
                           ->where('rencana_kerja_id',$parameter)
                           ->first();
    if(!empty($check)){
       echo json_encode([
          "status"            => "NOK",
          "message"           => "Gagal menyimpan pada tahun dan quarter yang sama"
         ]);
        die();
    }

    $data = [
      "rencana_kerja_detail_id"         => $generate,
      "rencana_kerja_id"                => $parameter,
      "rencana_kerja_detail_quarter"    => get_quarter(),
      "rencana_kerja_detail_pencapaian" => $this->request->getPost("rencana_kerja_detail_pencapaian"),
      "rencana_kerja_detail_status"     => $this->request->getPost("rencana_kerja_detail_status"),
      "rencana_kerja_detail_catatan"    => $this->request->getPost("rencana_kerja_detail_catatan"),
      "created_by"                      => session('session_id'),
      "updated_by"                      => session('session_id')
    ];


    //Proses Save
    try{
          $tableRencanaKerjaDetail->save($data);
          echo json_encode([
              "status"            => "OK",
              "message"           => "Success"
          ]);
        }
        catch(\Exception $e){
          // do task when error
          print_r($tableRencanaKerjaDetail->errors());die();
         $err = "";
         if (!empty($tableRencanaKerjaDetail->errors())) {
            foreach ($tableRencanaKerjaDetail->errors() as $error) {
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
      $tableRencanaKerjaDetail  = new RencanaKerjaDetail;
      $check                    = $tableRencanaKerjaDetail->where('rencana_kerja_detail_id',$id)
                                  ->first();

      if(!empty($check)){
        echo json_encode([
          "status"        => "OK",
          "id"            => $check['rencana_kerja_detail_id'],
          "pencapaian"    => $check['rencana_kerja_detail_pencapaian'],
          "status_rk"     => $check['rencana_kerja_detail_status'],
          "catatan"       => $check['rencana_kerja_detail_catatan'],
          "verifikasi"    => rencana_kerja_verify($check['rencana_kerja_verifikasi']),
          "quarter"       => $check['rencana_kerja_detail_quarter']
         ]);
      }
      else {
         echo json_encode([
          "status"        => "NOK"
         ]);
      }                            
      
    }
  }

  public function Verify(){
    $id  = $this->request->getPost('parameter');
    $tableRencanaKerjaDetail = new RencanaKerjaDetail;

    $check = $tableRencanaKerjaDetail->where('rencana_kerja_detail_id', $id)->first();
    if(!empty($check)){
        $data = [
          "rencana_kerja_verifikasi"         => $this->request->getPost("verifikasi"),
          "updated_by"                       => session('session_id')
        ];

       //Proses Update
       try{
            $tableRencanaKerjaDetail->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($tableRencanaKerjaDetail->errors())) {
              foreach ($tableRencanaKerjaDetail->errors() as $error) {
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
            "message"           => "Data detail rencana kerja tidak tersedia"
        ]);
        die();
    }
  } 

  public function Update(){
    $id  = $this->request->getPost('parameter');
    $tableRencanaKerjaDetail = new RencanaKerjaDetail;

    $check = $tableRencanaKerjaDetail->where('rencana_kerja_detail_id', $id)->first();
    if(!empty($check)){
        $data = [
          "rencana_kerja_detail_pencapaian"  => $this->request->getPost("rk_dt_pencapaian"),
          "rencana_kerja_detail_status"      => $this->request->getPost("rk_dt_status"),
          "rencana_kerja_detail_catatan"     => $this->request->getPost("rk_dt_catatan"),
          "updated_by"                       => session('session_id')
        ];

       //Proses Update
       try{
            $tableRencanaKerjaDetail->update($id,$data);
            echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
        }
        catch(\Exception $e){
           // do task when error
           $err = "";
           if (!empty($tableRencanaKerjaDetail->errors())) {
              foreach ($tableRencanaKerjaDetail->errors() as $error) {
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
            "message"           => "Data detail rencana kerja tidak tersedia"
        ]);
        die();
    }
  } 

  public function Delete(){
    $id                       = $this->request->getPost("id");
    $tableRencanaKerjaDetail  = new RencanaKerjaDetail();
    $check                    = $tableRencanaKerjaDetail->where('rencana_kerja_detail_id',$id)
                                                        ->first();
    if(empty($check)){
      if($check['rencana_kerja_verifikasi']=="1" or $check['rencana_kerja_verifikasi']=="2"){
        echo json_encode([
              "status"           => "NOK",
              "message"          => "Data rencana kerja detail tidak bisa di hapus"
          ]);
        die();
      }
    }

    try{
        $tableRencanaKerjaDetail->where('rencana_kerja_detail_id',$id)->delete();
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

  

}
