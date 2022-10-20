<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\MyDatatables;

use App\Models\AccountType;
use App\Models\User;

class UserManagementController extends BaseController
{

	public $mydata;
  public function __construct()
  {
    $this->mydata['id_menu'] = '21';

    // get role list
    $tableRoles = new AccountType();
    $this->mydata['roleList'] = $tableRoles
      ->select("id_account_type, account_type")
      ->groupBy("id_account_type, account_type")
      ->findAll();
  }

  public function index() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];
      return $this->blade->render("pages.user_management.index", $data);
    }
  }

  public function res() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      $data['id_menu'] = $this->mydata['id_menu'];
      return $this->blade->render("pages.user_management.read", $data);
    }
  }

  public function read() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('login');
    } else {
      
      $idMenu  = $this->mydata['id_menu'];
      $tableUsers = new User();

      $datatables   = new MyDatatables();
      $columnOrder  = ["", "name", "account_type", 'email'];
      $columnSearch = ["", "name", "account_type", 'email'];

      $query = $tableUsers->select("
        ms_user.id_user, ms_user.photo, ms_user.name, ms_user.email,
        role.account_type,
      ")
      ->join("ms_account_type as role", "role.id_account_type = ms_user.account_type", "left")
      ->groupBy("
        ms_user.id_user,
        ms_user.photo,
        ms_user.name,
        role.account_type,
        ms_user.email
      ")
      ;

      $getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
      $data    = [];

      foreach ($getList as $item) {
        $row                   = [];

        // display photo as image
        $photo = "<center>
          <a href='" .base_url(). "/assets/images/design/user/".$item->photo."' target='_blank'>
            <img src='" .base_url(). "/assets/images/design/user/".$item->photo."' width='40' height='40' style='border-radius:50%; object-fit: cover; border: 2px solid #fcaf17'>
          </a>
        </center>";

        // start button tag
          $button = '<div class="dropdown table-btn-group d-flex">
                        <button class="btn btn-outline-primary border-0 dropdown-toggle rounded-circle m-auto" type="button" id="btnTableGrup" data-bs-toggle="dropdown" aria-expanded="false" style="height: 40px; width: 40px">
                          <i class="mdi mdi-menu"></i>
                        </button>';

          // dropdown list
          $button .= "<ul class='dropdown-menu' aria-labelledby='btnTableGroup'>";
        
          // check if sess have access to update
          if(acc_update(session('level_id'),$idMenu)=="1"){ 
            // edit button
            $button .= "<li>
                            <button class='btn btn-sm text-nowrap m-1' onclick='Go(\"user_management\",\"User Management\",\"edit\",\"Ubah\",\"".$item->id_user."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
                          </li>"; 
          }
        
          // check if sess have access to delete
          if(acc_delete(session('level_id'),$idMenu)=="1"){
            // delete button
            $button .= "<li>
                            <button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$item->id_user."\", \"Hapus Hapus User?\", \"Apakah anda yakin ingin hapus data User?\", \"warning\", \"" . base_url() . "/user_management/destroy\",refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
                          </li>"; 
          }

          $button .="</ul>
                  </div>";
        // end button tag

        $row ["id_user"]      = $item->id_user;
        $row ["photo"]        = $photo;
        $row ["name"]         = $item->name;
        $row ["account_type"] = $item->account_type;
        $row ["email"]        = $item->email;
        $row ["act"]          = $button;

        $data[]                = $row;
      }

      echo $datatables->response($data);

    }
  }

  public function create()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {
      $data['roleList'] = $this->mydata['roleList'];
      return $this->blade->render("pages.user_management.create", $data);
    }
  }

  public function store()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {

      $generateId = "user_" . date("ymdhis") . "_" . uniqid(); 

      // lakukan validasi
      $validation =  \Config\Services::validation();

      helper(['form', 'url']);
      $validation->setRules([
        'name'         => 'required|min_length[4]',
        'account_type' => 'required',
        'email'        => 'required|valid_email',
        'username'     => 'required|min_length[4]',
        'password'     => 'required|min_length[4]'
      ]);

      $isDataValid = $validation->withRequest($this->request)->run();

      // jika data valid, simpan ke database
      if($isDataValid) {
        $upload = $this->request->getFile('photo');

        if ($upload->getName()) {

          if ($upload->getSizeByUnit('mb') > 5) {
            echo json_encode([
              "status"            => "NOK",
              "message"           => ["Ukuran gambar melebihi 5MB"],
            ]);
            die();
          } elseif (!in_array($upload->getMimeType(), IMAGE_PROFILE)) {
            echo json_encode([
              "status"            => "NOK",
              "message"           => ["Hanya mendukung jenis dokumen IMAGE"]
            ]);
            die();
          }

          // create image name rules
          $generateImageName = uniqid() . "_" . $upload->getName(); 
          // store image to path dir
          $upload->move(WRITEPATH . '../assets/images/design/user', $generateImageName);
          
        } else {
          $generateImageName = "default.png";
        }

        $tableUsers = new User();

        $tableUsers->insert([
          "id_user"      => $generateId,
          "photo"        => $generateImageName,
          "name"         => $this->request->getPost("name"),
          "account_type" => $this->request->getPost("account_type"),
          "email"        => $this->request->getPost("email"),
          "username"     => $this->request->getPost("username"),
          "password"     => crc32($this->request->getPost("password")),
          "created_by"   => session('session_id'),
          "updated_by"   => session('session_id')
        ]);
        echo json_encode([
          "status"            => "OK",
          "message"           => "Success"
        ]);

      } else {
        // get semua error
        $errors = $validation->getErrors();
        echo json_encode([
          "status"            => "NOK",
          "message"           => $errors
         ]);
        die();
      }

    }
  }

  public function edit()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {

      // dapatkan id dri data yg di edit
      $id         = $this->request->getPost('parameter');
      $tableUsers = new User();

      $data["data"]     = $tableUsers->find($id);
      $data['roleList'] = $this->mydata['roleList'];
      
      return $this->blade->render("pages.user_management.edit", $data);

    }
  }

  public function update()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {

      // dapatkan id dri data yg di edit
      $id         = $this->request->getPost('parameter');

      // dapatkan data dari id
      $tableUsers = new User();
      $getData = $tableUsers->where('id_user', $id)->first();

      // jika data valid, simpan ke database
      if(!empty($getData)) {

        $data = [
          "name"         => $this->request->getPost("name"),
          "account_type" => $this->request->getPost("account_type"),
          "email"        => $this->request->getPost("email"),
          "username"     => $this->request->getPost("username"),
          "created_by"   => session('session_id'),
          "updated_by"   => session('session_id')
        ];

        // get updated password if exist
        $password = $this->request->getPost('password');
        if($password != ""){
          $data["password"] = crc32($password);
        }

        // get image file
        $upload = $this->request->getFile('photo');
        if($upload != ""){
          // create image name rules
          $generateImageName = uniqid() . "_" . $upload->getName(); 
          // store image to path dir
          $upload->move(WRITEPATH . '../assets/images/design/user', $generateImageName);
          $data['photo'] = $generateImageName;
        } else{
          $data['photo'] = $this->request->getPost('hidden_photo');
        }

        $tableUsers->update($id, $data);
        
        echo json_encode([
          "status"            => "OK",
          "message"           => "Berhasil mengubah data"
        ]);
      } else {
        echo json_encode([
          "status"            => "NOK",
          "message"           => ["anu" => "Data marketing kompetitor tidak tersedia"]
        ]);
        die();
      }

    }
  }

  public function destroy()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to('/');
    } else {

      // get deleted data id
      $id = $this->request->getPost("id");

      $tableUser = new User();
      $getData = $tableUser->where('id_user', $id)->first();

      if(!empty($getData)) {
        if ($getData["photo"] != "default.png") {
          $path = "./assets/images/design/user/" . $getData['photo'];
  
          if (file_exists($path)) {
            unlink($path);
          }
        }

        // do the delete
        $tableUser->delete($id, 'id_user');
        echo json_encode([
            "status"            => "OK",
            "message"           => "Berhasil menghapus data!"
        ]);
      }

    }
  }

}
