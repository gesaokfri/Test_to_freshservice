<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Karyawan;
use App\Models\User;

class LoginController extends BaseController
{
	public function Index()
	{
		if($this->session->has('session_id')) {
			return redirect()->to('/');
		} else {
			return $this->blade->render('pages.auth.auth-login');
		}
	}

	public function DoLogin() 
	{
		$user = new User();

		$username = $this->request->getPost('username');
		$password = $this->request->getPost('password');

		
		$validation = \Config\Services::validation();


		$input = $this->validate([
			'username' => 'required',
			'password' => 'required'
		]);
		$error = $validation->getErrors();

		if (!$input) {
			return json_encode([
				"status"     => "Invalid",
				"validation" => $error,
			]);
		} else {
			$getUser = $user->select('ms_user.*, karyawan.KodeFakultas, karyawan.Unit')
			->join('ms_karyawan as karyawan', 'karyawan.nip = ms_user.username', 'left')
			->where('username', $username)
			->where('password', (string)crc32($password))
			->first();
	
			if(!empty($getUser)) {
				$data = [
					"session_id" => $getUser['id_user'],
					"username"   => $getUser['username'],
					"email"      => $getUser['email'],
					"name"       => $getUser['name'],
					"photo"      => $getUser['photo'],
					"level_id"   => $getUser['account_type'],
					"nip"        => "",
					"fakultas"   => $getUser['KodeFakultas'],
					"unit"       => $getUser['Unit'],
				];

				$karyawan    = new Karyawan();
				$getKaryawan = $karyawan
				->where('AlasanPHK', null)
				->where('id_user', $getUser['id_user'])
				->first();
	
				if(!empty($getKaryawan)) {
					$data['nip'] = $getKaryawan['NIP'];
				}
	
				$this->session->set($data);
				echo json_encode([
					"code"   => 200,
					"status" => "OK"
				]);
			} else {
				echo json_encode([
					"code"       => 401,
					"status"     => "UNAUTHORIZED",
					"message"    => "Nama Pengguna atau Kata Sandi Salah",
					"validation" => $error,
				]);
			}
		}

	}

}
