<?php
namespace App\Libraries;
/*
CODEIGNITER 4 DATATABLES SERVERSIDE LIBRARY
Founded By :
1. Riski Nurohman     => Original Founder
2. Muhamad Ridwansyah => Contributor
*/
class MyDatatables {
  protected $query;
  protected $filtered;
  protected $allRecord;
  protected $where = []; //Array Assosiatif
  protected $orWhere = []; //Array Assosiatif
  /* whereIn and orWhereIn array form */
  /*
    It should be like this
    [
      "field_name" => [array_of_value]
    ]
  */
  protected $orWhereIn = []; //Array Assosiatif
  protected $whereIn = []; //Array Multidimensi & Asosiatif;

  protected $groupBy = [];

  //Inisialisasi pada saat akan menggunakan datatable. Function ini berisi aksi-aksi yang terdapat dalam datatable seperti ordering, filtering , paging, dll.
  public function init_datatables($query, $columnOrder, $columnSearch) {
    $this->query = $query;
    $request = \Config\Services::request();
    $i = 0;

    //Jika melakukan pencarian
    if($request->getPost('search')['value']) {
      foreach($columnSearch as $item) {
        if($i === 0) { //Jika iterasi pertama, lakukan pencarian by kolom pertama
          $this->query->groupStart();
          $this->query->like($item, $request->getPost('search')['value']);
        } else { //Jika tidak, lakukan pencarian by kolom berikutnya
          $this->query->orLike($item, $request->getPost('search')['value']);
        }
        //Jika sudah sampai pada kolom terakhir, akhiri pencarian.
        if(count($columnSearch) - 1 == $i) {
          $this->query->groupEnd();
        }
        $i++;
      }
    }

    //Jika kolom diurutkan
    if($request->getPost('order')) {
      $this->query->orderBy(
        $columnOrder[$request->getPost('order')['0']['column']], 
        $request->getPost('order')['0']['dir']
      );
    }
    
    if(!empty($this->groupBy)) {
      $this->query->groupBy($this->return_group_by());
    }

    if(!empty($this->where)) {
      $this->query->where($this->return_where());
    }

    if(!empty($this->orWhere)) {
      $this->query->orWhere($this->return_or_where());
    }

    if(!empty($this->whereIn)) {
      $whereIn = $this->return_where_in();
      if(array_key_exists("group_datatables", $whereIn)) {
        if($whereIn['group_datatables'] == 'start') {
          $grouping = 'start';
          unset($whereIn['group_datatables']);
        } elseif($whereIn['group_datatables'] == 'end') {
          $grouping = 'end';
          unset($whereIn['group_datatables']);
        }
        if($grouping == 'start') {
          $this->query->groupStart();
        }
        foreach($whereIn as $key => $val) {
          $this->query->whereIn($key, $val);
        }
        if($grouping == 'end') {
          $this->query->groupEnd();
        }
      } else {
        foreach($whereIn as $key => $val) {
          $this->query->whereIn($key, $val);
        }
      }
    }

    if(!empty($this->orWhereIn)) {
      $orWhereIn = $this->return_or_where_in();
      if(array_key_exists("group_datatables", $orWhereIn)) {
        if($orWhereIn['group_datatables'] == 'start') {
          $grouping = 'start';
          unset($orWhereIn['group_datatables']);
        } elseif($orWhereIn['group_datatables'] == 'end') {
          $grouping = 'end';
          unset($orWhereIn['group_datatables']);
        }
        if($grouping == 'start') {
          $this->query->groupStart();
        }
        foreach($orWhereIn as $key => $val) {
          $this->query->orWhereIn($key, $val);
        }
        if($grouping == 'end') {
          $this->query->groupEnd();
        }
      } else {
        foreach($orWhereIn as $key => $val) {
          $this->query->orWhereIn($key, $val);
        }
      }
    }

    //Jika data tidak ditampilkan semua, lakukan limitasi data
    if($request->getPost('length') != -1) {
      $this->query->limit($request->getPost('length'), $request->getPost('start'));
    }
    $this->filtered = $this->query->countAllResults(false);
    $this->allRecord = $this->query->countAllResults(false);
    $result = $this->query->get()->getResult();
    return $result;
  }
  
  /* 
  Fungsi untuk melakukan seleksi data
  Parameter :
  ->$key berisi nama kolom yang akan dikenai seleksi
  ->$value berisi nilai yang akan diberikan kepada kolom yang akan dikenai seleksi 
  */
  public function where($key, $value) {
    $this->where = array_push_assoc($this->where, $key, $value); //Isi nilai $where berupa array asosiatif dengan pasangan key dan valuenya
    return $this; //Mengembalikan object dari atribut $where itu sendiri
  }

  //Fungsi untuk mengembalikan nilai akhir dari atribut where.(Mutator)
  public function return_where() {
    return $this->where;
  }
  /* ===== End Function Where =====*/


  /* 
  orWhere Function
  Parameter :
  ->$key berisi nama kolom yang akan dikenai seleksi
  ->$value berisi nilai yang akan diberikan kepada kolom yang akan dikenai seleksi 
  */
  public function orWhere($key, $value) {
    $this->orWhere = array_push_assoc($this->orWhere, $key, $value); //Isi nilai $where berupa array asosiatif dengan pasangan key dan valuenya
    return $this; //Mengembalikan object dari atribut $where itu sendiri
  }

  //Fungsi untuk mengembalikan nilai akhir dari atribut where.(Mutator)
  public function return_or_where() {
    return $this->orWhere;
  }
  /* ===== End Function orWhere =====*/



  /* ===== Fungsi Wherein ===== */
  /* 
  * Parameter: 
  * $field berisi nama field yang akan dikenai fungsi whereIn
  * $value(array) nilai yang menjadi acuan untuk field yang akan dikenai whereIn 
  */

  public function whereIn($field, $value = [], $group=null) {
    $this->whereIn = array_push_assoc($this->whereIn, $field, $value);
    if(!empty($group)) {
      $this->whereIn = array_push_assoc($this->whereIn, 'group_datatables', $group);
    }
    return $this; //Mengembalikan object dari atribut $where itu sendiri
  }

  //Fungsi untuk mengembalikan nilai akhir dari atribut whereIn.(Mutator)
  public function return_where_in() {
    return $this->whereIn; 
  }
  /* ===== End WhereIn Function =====*/


  /* ===== Fungsi orWhereInn ===== */
  /* 
  * Parameter: 
  * $field berisi nama field yang akan dikenai fungsi whereIn
  * $value(array) nilai yang menjadi acuan untuk field yang akan dikenai orWhereIn 
  */

  public function orWhereIn($field, $value = [], $group=null) {
    $this->orWhereIn = array_push_assoc($this->orWhereIn, $field, $value);
    if(!empty($group)) {
      $this->orWhereIn = array_push_assoc($this->orWhereIn, 'group_datatables', $group);
    }
    return $this; //Mengembalikan object dari atribut $orWhereIn itu sendiri
  }

  //Fungsi untuk mengembalikan nilai akhir dari atribut whereIn.(Mutator)
  public function return_or_where_in() {
    return $this->orWhereIn; 
  }
  /* ===== End orWhereIn Function =====*/
  

  /* ===== Fungsi GroupBy ===== */
  /* 
  Parameter: 
  ->$array nilai yang menjadi acuan untuk field yang akan dikenai groupBy 
  */

  public function groupBy($array) {
    $this->groupBy = $array;
    return $this;
  }

  //Fungsi untuk mengembalikan nilai akhir dari atribut groupBy.(Mutator)
  public function return_group_by() {
    return $this->groupBy;
  }
  /* ===== End GroupBy Function =====*/

  /* ===== Fungsi groupStart ===== */
  /* 
  Parameter: null
  */
  public function groupStart($bool) {
    $this->groupStart = $bool;
    return $this;
  }
  /* ===== End groupStart Function =====*/

  /* ===== Fungsi groupEnd ===== */
  /* 
  Parameter: null
  */
  public function groupEnd($bool) {
    $this->groupEnd = $bool;
    return $this;
  }
  /* ===== End groupEnd Function =====*/


  /* ===== Kirim Respon Ke Datatable Clientside ===== */
  public function response($data) {
    $request = \Config\Services::request();
    $response = json_encode([
      "draw"            => (int)$request->getPost('draw'),
      "recordsTotal"    => (int)$this->allRecord,
      "recordsFiltered" => (int)$this->filtered,
      "data" => $data
    ]);
    return $response;
  }
}