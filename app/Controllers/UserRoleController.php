<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AccountType;
use App\Libraries\MyDatatables;
use App\Models\Menu;

class UserRoleController extends BaseController
{
	public $mydata;
	public function __construct() {
        $this->mydata['id_menu'] = '10';
    }

	public function Index(){
		if(!$this->session->has('session_id')) {
			return redirect()->to('login');
		} else {
			$data['id_menu']     = $this->mydata['id_menu'];
			if(acc_read(session('level_id'),$data['id_menu'])=="1"){
				return $this->blade->render("pages/user_role/index",$data);
			}
			else{
				return redirect()->to('/');
			}
		}
	}

	public function Res($parent='') {
		$id_menu  = $this->mydata['id_menu'];
		$accType = new AccountType();
		$datatables = new MyDatatables();

		$columnOrder = [
			'ms_account_type.account_type','ty.account_type','ms_account_type.id_parent_account_type'
		];
		$columnSearch = [
			'ms_account_type.account_type','ty.account_type','ms_account_type.id_parent_account_type'
		];

		$query   = $accType->select(
									'ms_account_type.id_account_type,
									 ms_account_type.account_type, 
									 ty.account_type as type,
									 ty.id_account_type as id_parent_account_type',
									)
		->join('ms_account_type as ty', 'ty.id_account_type = ms_account_type.id_parent_account_type', 'left');
		$datatables->groupBy(["ms_account_type.id_account_type","ms_account_type.account_type","ty.account_type","ty.id_account_type"]);

		if(!empty($parent)){
           $datatables->where('ms_account_type.id_parent_account_type', $parent);     
        }
		
		$getList = $datatables->init_datatables($query, $columnOrder, $columnSearch);
		$data = [];
		foreach($getList as $list) {
			     $row            = [];
			$row["account_type"] = $list->account_type;
			$row["parent"]       = $this->GetAccountType($list->id_parent_account_type);

			$show = 0;
			if(acc_update(session('level_id'),$id_menu)=="1"){	
				$show = 1;
			}
			if(acc_delete(session('level_id'),$id_menu)=="1"){	
				$show = 1;
			}

			$list_btn = "<div class='dropdown table-btn-group'>
							<button class='btn btn-outline-primary border-0 dropdown-toggle rounded-circle' type='button' id='btnTableGroup' data-bs-toggle='dropdown' aria-expanded='false' style='height: 40px; width: 40px'>
								<i class='mdi mdi-menu'></i>
							</button>
							<ul class='dropdown-menu table_btn_2' aria-labelledby='btnTableGroup'>";

			if(acc_update(session('level_id'),$id_menu)=="1"){	
				//Edit
				$list_btn .= "<li>
								<button class='btn btn-sm text-nowrap m-1' onclick='get_account_type(\"".$list->id_account_type."\")'><i class='mdi mdi-square-edit-outline font-size-16'></i> Ubah</button>
							</li>"; 
			}

			if(acc_delete(session('level_id'),$id_menu)=="1"){	
				//Delete
				$list_btn .= "<li>
								<button class='btn btn-delete btn-sm text-nowrap m-1' onclick='action_delete(\"".$list->id_account_type."\", \"Hapus User Role\", \"Apakah anda yakin ingin hapus User Role?\", \"question\", \"" . base_url() . "/user_role/delete\", refresh)'><i class='mdi mdi-delete-outline font-size-16'></i> Hapus</button>
							</li>"; 
			}

			$list_btn .="</ul></div>";

			if($show==0){
				$list_btn="";
			}			

			$row["act"]     = $list_btn;
			$data[] = $row;
		}
		$response = $datatables->response($data);
		echo $response;
	}

	protected function GetAccountType($id) {
		$accType = new AccountType();
		$get = $accType->where("id_account_type", $id)->first();
		if($get) {
			return $get['account_type'];
		} else {
			return null;
		}
	}

	public function Add(){
		if(!$this->session->has('session_id')) {
			return redirect()->to('login');
		} else {
			$data['id_menu']     = $this->mydata['id_menu'];
			$listaccType = new AccountType;
			$data['parent_account'] = $listaccType->select([
				'ms_account_type.id_account_type',
				'ms_account_type.account_type'
			])
			->where('ms_account_type.id_parent_account_type',NULL)
			->groupBy(["ms_account_type.id_account_type","ms_account_type.account_type"])
			->get()->getResult();


			if(acc_read(session('level_id'),$data['id_menu'])=="1"){
				return $this->blade->render('pages/user_role/add', $data);
			}
			else{
				return redirect()->to('/');
			}
		}
	}

	public function Save() {
		$ac_type  = new AccountType;
		$req      = json_decode(json_encode($_POST));
		$fua_name = $req->account_type;

      	if(isset($req->parameter)){   //Edit
           	if(!empty($req->parent)){
             	$count = 0;
			} 
			else {
				$count = $ac_type->where('account_type',$fua_name)
						      	 ->where('id_account_type','!=',$req->parameter)
						      	 ->countAllResults();
            }

          	if($count>0){
            	echo json_encode([
	                "status"            => "NOK",
	                "message"           => "User Role Not Available"
	            ]);
            	die();
			}
			else {
				$ac_type->where('id_account_type',$req->parameter)->delete();
				foreach($req as $key=>$val) {
                	if(preg_match("/read|edit|add|delete|upload|download/",$key)){
                  		$$key = $val;

						foreach($$key as $key1 => $val1) {
							$data_exist =  $ac_type->where('id_menu',$val1)
												   ->where('id_account_type',$req->parameter)->first();

							if(!empty($data_exist['id_menu'])){
								$data2 = [
									"updated_by"  => session('session_id'),
									"acc_".$key   => 1
								];  

								if(!empty($req->parent)){
									$data2['id_parent_account_type'] = $req->parent;
								}

								$ac_type2   = new AccountType;
                          		$ac_type2->update($data_exist['id'],$data2);		 
							} 
							else {
								$milliseconds = round(microtime(true) * 1000);
								$generate_id  = date('ymdhis').$milliseconds."_".uniqid();
								$data = [
									"id"              => $generate_id,
									"id_account_type" => $req->parameter,
									"account_type"    => $fua_name,
									"id_menu"         => $val1,
									"created_by"      => session('session_id'),
									"updated_by"      => session('session_id'),

									"acc_".$key              => 1
								];

								if(!empty($req->parent)){
									$data['id_parent_account_type'] = $req->parent;
								}
								
								$res = new AccountType;
								$res->save($data);

							}
						}
                	}
            	} 
          	}
          	echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
		}
		else {
		  	$generate = "fa_".date("ymdhis")."_".rand(10000, 99999);
			foreach($req as $key=>$val){
              	if(preg_match("/read|edit|add|delete|upload|download/",$key)){
                	$$key = $val;
                	foreach($$key as $key1 => $val1){
                  		$data_exist = $ac_type->where('id_menu',$val1)
		                  					  ->where('id_account_type',$generate)->first();
                  		if(!empty($data_exist['id_menu'])){
							$data2 = [
								"updated_by" => session('session_id'),
								"acc_".$key  => 1
							];  

                          	if(!empty($req->parent)){
                            	$data2['id_parent_account_type'] = $req->parent;
                          	}

                          	$ac_type2   = new AccountType;
                          	$ac_type2->update($data_exist['id'],$data2);
						} 
						else {
							$milliseconds = round(microtime(true) * 1000);
							$generate_id  = date('ymdhis').$milliseconds."_".rand()."_".uniqid();
							$data = [
								"id"    			 => $generate_id,
								"id_account_type"    => $generate,
								"account_type" 		 => $fua_name,
								"id_menu" 			 => $val1,
								"created_by" 		 => session('session_id'),
								"updated_by" 		 => session('session_id'),
								"acc_".$key   		 => 1
							];

							if(!empty($req->parent_account)){
								$data['id_parent_account_type'] = $req->parent_account;
							}

							$res = new AccountType;
							$res->save($data);
						}
                	}	
              	}
          	}
			echo json_encode([
                "status"            => "OK",
                "message"           => "Success"
            ]);
      	}
	}

	public function Delete() {
		$id = $this->request->getPost('id');
		$accountType = new AccountType;
        try{
          $accountType->where('id_account_type',$id)->delete();
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

	public function GetData() {
		if(!$this->session->has('session_id')) {
			return redirect()->to('login');
		} else {
			$data['id_menu']     = $this->mydata['id_menu'];
			$id = $this->request->getPost('id');
			$accType = new AccountType();
			$getData = $accType->where('id_account_type',$id)->first();

			$listaccType = new AccountType;
			$data['parent_account'] = $listaccType->select([
				'ms_account_type.id_account_type',
				'ms_account_type.account_type'
			])
			->where('ms_account_type.id_parent_account_type',NULL)
			->groupBy(["ms_account_type.id_account_type","ms_account_type.account_type"])
			->get()->getResult();
			$data['account']        = $getData['account_type'];
			$data['id']             = $getData['id_account_type'];
			$data['parent_id']      = $getData['id_parent_account_type'];

			if(acc_read(session('level_id'),$data['id_menu'])=="1"){
				return $this->blade->render('pages/user_role/edit', $data);
			}
			else{
				return redirect()->to('/');
			}
		}
	}
	
	public function GetDataAdd() {
		$id     = @$_REQUEST['id'];
		$menu   = new Menu;

		if($id!="") {
			$sql = $menu->where('reference',$id)
			->orderBy("
          CASE
            WHEN type_menu = '2' THEN 1
            WHEN type_menu = '1' THEN 2
          	ELSE 3 
					END ASC
								")
			->orderBy('urutan', 'asc')
			->get()
			->getResult();
		} else {
			$sql = $menu->where('level_menu','1')
			->orderBy("
          CASE
            WHEN type_menu = '2' THEN 1
            WHEN type_menu = '1' THEN 2
          	ELSE 3 
					END ASC
								")
			->orderBy('urutan', 'asc')
      ->get()
			->getResult();
		}
  		
  		$result = [];
        
			foreach ($sql as $nw){ 
				$men_judul = $nw->menu;
				$men_id    = $nw->id_menu;
					
				$state  = "";

				$hitung = $menu->where('reference',$men_id)->countAllResults();
					
				if($hitung>0){
					$state = "closed";
				}
				else {
					$state = "open";
				}

				if ($nw->type_menu == 1) {
					$label = " Universitas";
				} elseif ($nw->type_menu == 2) {
					$label = " Yayasan";
				} else {
					$label = "";
				}
					
				$read_check     ="";
				$add_check      ="";
				$edit_check     ="";
				$delete_check   ="";
				$upload_check   ="";
				$download_check ="";
					
					
				$data["id"]          = $men_id;
				$data["menu"]        = $men_judul . $label;
				$data["state"]       = $state;
				$data["read"]        = '<center><input type="checkbox" value="'.$men_id.'" id="read_'.$men_id.'" name="read[]" '.$read_check.'></center>';
				$data["add"]         = '<center><input type="checkbox" value="'.$men_id.'" id="add_'.$men_id.'" name="add[]" '.$add_check.'></center>';
				$data["edit"]        = '<center><input type="checkbox" value="'.$men_id.'" id="edit_'.$men_id.'" name="edit[]" '.$edit_check.'></center>';
				$data["delete"]      = '<center><input type="checkbox" value="'.$men_id.'" id="delete_'.$men_id.'" name="delete[]" '.$delete_check.'></center>';
				$data["upload"]      = '<center><input type="checkbox" value="'.$men_id.'" id="upload_'.$men_id.'" name="upload[]" '.$upload_check.'></center>';
				$data["download"]    = '<center><input type="checkbox" value="'.$men_id.'" id="download_'.$men_id.'" name="download[]" '.$download_check.'></center>';
				$data["check_all"]   = '<center onClick="check_row(\''.$men_id.'\')"><button type="button" class="btn btn-success btn-sm waves-effect waves-light"><i class="bx bx-check-circle" ></i></button></center>';
				$data["uncheck_all"] = '<center onClick="uncheck_row(\''.$men_id.'\')"><button type="button" class="btn btn-danger btn-sm waves-effect waves-light">&times;</button></center>';

				$result[] = $data;
			}
		echo json_encode($result);
	}

	public function GetDataUpdate() {
		$id     		= @$_REQUEST['id'];
		$id_parameter   = @$_REQUEST['id_parameter'];
		$menu   = new Menu;
   		//@ = isset
		if($id!="") {
			$sql = $menu->where('reference',$id)->orderBy('urutan', 'asc')
				   ->get()->getResult();
		}
    	else {
      		$sql = $menu->where('level_menu','1')->orderBy('urutan', 'asc')
      			   ->get()->getResult();
    	}
  		
  		$result = [];
        //if(!$result) print $db->ErrorMsg();
        foreach ($sql as $nw){ 
					$men_judul = $nw->menu;
					$men_id    = $nw->id_menu;
            
					$state  = "";

					$hitung = $menu->where('reference',$men_id)->countAllResults();
					if($hitung>0){
							$state = "closed";
					}
					else {
							$state = "open";
					}

					if ($nw->type_menu == 1) {
						$label = " Universitas";
					} elseif ($nw->type_menu == 2) {
						$label = " Yayasan";
					} else {
						$label = "";
					}
            
					$read_check     ="";
					$add_check      ="";
					$edit_check     ="";
					$delete_check   ="";
					$upload_check   ="";
					$download_check ="";

             if($id_parameter) {
             	$accType 		  = new AccountType();
				$row_check        = $accType->where('id_account_type',$id_parameter)
										    ->where('id_menu',$men_id)->first();
				$read_check       = @($row_check['acc_read']=="1" ? 'checked' : '');
				$add_check        = @($row_check['acc_add']=="1" ? 'checked' : '');
				$edit_check       = @($row_check['acc_edit']=="1" ? 'checked' : '');
				$delete_check     = @($row_check['acc_delete']=="1" ? 'checked' : '');
				$upload_check 	  = @($row_check['acc_upload']=="1" ? 'checked' : '');
				$download_check   = @($row_check['acc_download']=="1" ? 'checked' : '');
            }
            
            
					$data["id"]         = $men_id;
					$data["menu"]       = $men_judul . $label;
					$data["state"]      = $state;
					$data["read"]       = '<center><input type="checkbox" value="'.$men_id.'" id="read_'.$men_id.'" name="read[]" '.$read_check.'></center>';
					$data["add"]        = '<center><input type="checkbox" value="'.$men_id.'" id="add_'.$men_id.'" name="add[]" '.$add_check.'></center>';
					$data["edit"]       = '<center><input type="checkbox" value="'.$men_id.'" id="edit_'.$men_id.'" name="edit[]" '.$edit_check.'></center>';
					$data["delete"]     = '<center><input type="checkbox" value="'.$men_id.'" id="delete_'.$men_id.'" name="delete[]" '.$delete_check.'></center>';
					$data["upload"]     = '<center><input type="checkbox" value="'.$men_id.'" id="upload_'.$men_id.'" name="upload[]" '.$upload_check.'></center>';
					$data["download"]   = '<center><input type="checkbox" value="'.$men_id.'" id="download_'.$men_id.'" name="download[]" '.$download_check.'></center>';
					$data["check_all"]   = '<center onClick="check_row(\''.$men_id.'\')"><button type="button" class="btn btn-success btn-sm waves-effect waves-light"><i class="bx bx-check-circle" ></i></button></center>';
					$data["uncheck_all"] = '<center onClick="uncheck_row(\''.$men_id.'\')"><button type="button" class="btn btn-danger btn-sm waves-effect waves-light">&times;</button></center>';

					$result[] = $data;
        } 
		echo json_encode($result);
	}

	
}