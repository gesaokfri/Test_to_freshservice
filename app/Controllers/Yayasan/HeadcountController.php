<?php

namespace App\Controllers\Yayasan;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;
use App\Models\Karyawan;
use App\Models\Yayasan\Headcount\ViewHeadcount;

class HeadcountController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '19';
  }

  public function Index(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/'); //Back to Login
    } else {
      $data['id_menu']  = "19";
      if(acc_read(session('level_id'),$data['id_menu'])=="1"){

        // pass data for filtering
        for ($i = date("Y"); $i >= 1900 ; $i--) {
          $data['tahun'][] = $i;
        }

        return $this->blade->render("yayasan.pages.headcount.index", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Read(){
    $headcount = new ViewHeadcount();

    $datatables   = new MyDatatables();
    $columnOrder  = [
      "headcount.Tahun", "JumlahPria", "JumlahWanita", "JumlahPHK"
    ];
    $columnSearch = [
      "headcount.Tahun"
    ];

    $query = $headcount
    ->select("
    (SELECT max(JumlahDosen) FROM view_headcount WHERE JenisKelamin = 'P' AND Tahun = headcount.Tahun) AS JumlahPria, 
    (SELECT max(JumlahDosen) FROM view_headcount WHERE JenisKelamin = 'W' AND Tahun = headcount.Tahun) AS JumlahWanita,
    headcount.JumlahPHK,
    headcount.Tahun")
    ->from('view_headcount as headcount')
    ->groupBy('headcount.Tahun,headcount.JumlahPHK');
    // ->where('tr_competitor.status','1');
    $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
    $data    = [];

    foreach ($getList as $list) {

      $row             = [];

      $row  ["jumlahPria"]   = $list->JumlahPria;
      $row  ["jumlahWanita"] = $list->JumlahWanita;
      $row  ["jumlahPhk"]    = $list->JumlahPHK;
      $row  ["tahun"]        = $list->Tahun;
      $data[]                = $row;
    }
    echo $datatables->response($data);
  }

  public function Res(){
    if (!$this->session->has('session_id')) {
      return redirect()->to('/'); //Back to Login
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];
      if (acc_read(session("level_id"), $data["id_menu"]) == "1") {

        // get passed function
        return $this->blade->render("yayasan.pages.headcount.res", $data);
      } else {
        return redirect()->to('/yayasan');
      }
    }
  }

  public function Chart(){
    $headcount = new ViewHeadcount;

    $getTahun = $this->request->getPost("tahun");

    // get tahun yang dipilih (still proccess)
    
    if($getTahun != ""){
      for ($tahun = ($getTahun - 4); $tahun <= $getTahun; $tahun++) { 
        $listTahun[] = $tahun;
      }
    } else{
      for ($tahun = (date('Y') - 4); $tahun <= date('Y'); $tahun++) { 
        $listTahun[] = $tahun;
      }
    }
    
    $result = $headcount
    ->select("(SELECT max(JumlahDosen) FROM view_headcount WHERE JenisKelamin = 'P' AND Tahun = headcount.Tahun) AS JumlahPria, 
    (SELECT max(JumlahDosen) FROM view_headcount WHERE JenisKelamin = 'W' AND Tahun = headcount.Tahun) AS JumlahWanita,
    headcount.Tahun")
    ->whereIn('headcount.Tahun', $listTahun)
    ->from('view_headcount as headcount')
    ->groupBy('headcount.Tahun')
    ->findAll();

    $i = 0;
    foreach ($result as $item){
      
      // pass chart data
      $dataTahun  [$i] = $item['Tahun'];
      $dataJumlahP[$i] = $item['JumlahPria'];
      $dataJumlahW[$i] = $item['JumlahWanita'];

      $i++;
    }

    $data['tahun']        = json_encode($dataTahun);
    $data['jumlahPria']   = json_encode($dataJumlahP);
    $data['jumlahWanita'] = json_encode($dataJumlahW);

    return $this->blade->render("yayasan.pages.headcount.chart", $data);

  }
  
}