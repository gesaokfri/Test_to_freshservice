<?php

namespace App\Controllers;

use App\Controllers\BaseController;

use App\Models\User;

class ProfilController extends BaseController
{

  public function IndexYayasan() 
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to(base_url('/login'));
    } else {
      return $this->blade->render("yayasan.pages.profil.index");
    }
  }

  public function IndexUniversitas()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to(base_url('/login'));
    } else {
      return $this->blade->render("pages.profil.index");
    }
  }

  public function Update()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to(base_url('/login'));
    } else {
      $id = session('session_id');

      $User = new User();

      $getData = $User->where("id_user", $id)->first();

      if (!empty($getData)) {
        $upload = $this->request->getFile('photo');

        if ($upload->getName()) {

          if ($upload->getSizeByUnit('mb') > 5) {
            echo json_encode([
              "status"  => "NOK",
              "title"   => "Melebihi kapasitas!",
              "message" => ["Ukuran gambar melebihi 5MB"],
            ]);
            die();
          } elseif (!in_array($upload->getMimeType(), IMAGE_PROFILE)) {
            echo json_encode([
              "status"  => "NOK",
              "title"   => "Format salah!",
              "message" => ["Hanya mendukung jenis dokumen IMAGE"]
            ]);
            die();
          }

          $generateImageName = uniqid() . "_" . $upload->getName();
          $upload->move(WRITEPATH . '../assets/images/design/user', $generateImageName);
        }

        if ($getData["photo"] != "default.png") {
          $path = "./assets/images/design/user/" . $getData['photo'];

          if (file_exists($path)) {
            unlink($path);
          }
        }

        $data = [
          "photo"      => $generateImageName,
          "updated_by" => session('session_id')
        ];

        $User->update($id, $data);

        $this->session->set(array("photo" => $generateImageName));

        echo json_encode([
          "code"   => 200,
          "status" => "OK",
          "message" => "Photo profil berhasil dirubah!"
        ]);
      } else {
        
        echo json_encode([
          "code"   => 200,
          "status" => "NOK",
          "message" => "Terjadi kesalahan!"
        ]);

      }

    }
  }

  public function UpdatePassword()
  {
    if (!$this->session->has('session_id')) {
      return redirect()->to(base_url('/login'));
    } else {
      $id = session('session_id');

      $User = new User();

      $getData = $User->where("id_user", $id)->first();

      if (!empty($getData)) {
        $old_password              = $this->request->getPost('old_password');
        $new_password              = $this->request->getPost('new_password');
        $new_password_confirmation = $this->request->getPost('new_password_confirmation');

        if ((string)crc32($old_password) != $getData["password"]) {
          echo json_encode([
            "code"    => 200,
            "status"  => "NOK",
            "message" => "Kata sandi Salah!"
          ]);
          die();
        }

        if (strlen($new_password) < 8 || strlen($new_password_confirmation) < 8) {
          echo json_encode([
            "code"    => 200,
            "status"  => "NOK",
            "message" => "Minimal 8 karakter!"
          ]);
          die();
        }

        if ($new_password !== $new_password_confirmation) {
          echo json_encode([
            "code"    => 200,
            "status"  => "NOK",
            "message" => "Kata sandi tidak sama!"
          ]);
          die();
        }

        $data = [
          "password"   => (string)crc32($new_password),
          "updated_by" => session('session_id')
        ];

        $User->update($id, $data);

        echo json_encode([
          "code"   => 200,
          "status" => "OK",
          "message" => "Password berhasil dirubah!"
        ]);
      } else {
        echo json_encode([
          "code"    => 200,
          "status"  => "NOK",
          "message" => "Terjadi kesalahan!"
        ]);
      }

    }
  }

}
