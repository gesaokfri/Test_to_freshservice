<?php

// RAIHAN

namespace App\Controllers\Integration\Universitas;

use App\Controllers\BaseController;

// TRIGGER BEASISWA
use App\Models\Trigger\LaporanBeasiswa\AJBeasiswaDonatur;
use App\Models\Trigger\LaporanBeasiswa\TRBeasiswaDonatur;

use App\Models\Trigger\LaporanBeasiswa\AJMasterBeasiswa;
use App\Models\Trigger\LaporanBeasiswa\TRMasterBeasiswa;

use App\Models\Trigger\LaporanBeasiswa\AJPembayaranBeasiswa;
use App\Models\Trigger\LaporanBeasiswa\TRPembayaranBeasiswa;

use App\Models\Trigger\LaporanBeasiswa\AJPenerimaanBeasiswa;
use App\Models\Trigger\LaporanBeasiswa\TRPenerimaanBeasiswa;

use App\Models\Trigger\LaporanBeasiswa\AJProjectAdministration;
use App\Models\Trigger\LaporanBeasiswa\TRProjectAdministration;
// TRIGGER BEASISWA

class IntegrationBeasiswaController extends BaseController
{

  public  $mydata;
  private $db;

  public function __construct()
  {
    $this->db = db_connect();
  }

  public function Index()
  {
    $this->TriggerBeasiswa();
    // echo "BEASISWA PER " . date("Y-m-d");
  }

  public function TriggerBeasiswa()
  {

    // BEASISWA DONATUR
    $AJBeasiswaDonatur = new AJBeasiswaDonatur();
    $TRBeasiswaDonatur = new TRBeasiswaDonatur();

    $getAJBeasiswaDonatur = $AJBeasiswaDonatur->findAll();

    foreach ($getAJBeasiswaDonatur as $value) {
      $firstCheck = $TRBeasiswaDonatur->where("id_code_donatur", $value["CODE_DONATUR"])->first();

      if (!empty($firstCheck)) {
        $data = [
          "id_code_donatur"        => $value["CODE_DONATUR"],
          "donatur_name"           => $value["NAMA_DONATUR"],
          "donatur_address1"       => $value["ADDRESS1"],
          "donatur_address2"       => $value["ADDRESS2"],
          "donatur_address3"       => $value["ADDRESS3"],
          "contact_name"           => $value["CONTACT_NAME"],
          "contact_phone"          => $value["CONTACT_PHONE"],
          "contact_fax"            => $value["CONTACT_FAX"],
          "contact_mobile"         => $value["CONTACT_MOBILE"],
          "contact_email"          => $value["CONTACT_EMAIL"],
          "created_by"             => $value["CREATED_BY"],
          "updated_by"             => $value["LAST_UPDATE_BY"]
        ];

        $TRBeasiswaDonatur->protect(false);
        $TRBeasiswaDonatur->update($firstCheck["id_aj_beasiswa_donatur"], $data);
        $TRBeasiswaDonatur->protect(true);
      } else {
        $generate_id = "AJ_BSW_DN_" . date("ymdhis") . "_" . substr(uniqid(rand()), -6);

        $data = [
          "id_aj_beasiswa_donatur" => $generate_id,
          "id_code_donatur"        => $value["CODE_DONATUR"],
          "donatur_name"           => $value["NAMA_DONATUR"],
          "donatur_address1"       => $value["ADDRESS1"],
          "donatur_address2"       => $value["ADDRESS2"],
          "donatur_address3"       => $value["ADDRESS3"],
          "contact_name"           => $value["CONTACT_NAME"],
          "contact_phone"          => $value["CONTACT_PHONE"],
          "contact_fax"            => $value["CONTACT_FAX"],
          "contact_mobile"         => $value["CONTACT_MOBILE"],
          "contact_email"          => $value["CONTACT_EMAIL"],
          "created_by"             => $value["CREATED_BY"],
          "updated_by"             => $value["LAST_UPDATE_BY"]
        ];

        $TRBeasiswaDonatur->protect(false);
        $TRBeasiswaDonatur->save($data);
        $TRBeasiswaDonatur->protect(true);
      }
    }
    // BEASISWA DONATUR

    // MASTER BEASISWA
    $AJMasterBeasiswa = new AJMasterBeasiswa();
    $TRMasterBeasiswa = new TRMasterBeasiswa();

    $getAJMasterBeasiswa = $AJMasterBeasiswa->findAll();

    foreach ($getAJMasterBeasiswa as $value) {
      $firstCheck = $TRMasterBeasiswa->where("header_id", $value["HEADER_ID"])->first();

      if (!empty($firstCheck)) {
        $data = [
          "header_id"       => $value["HEADER_ID"],
          "beasiswa_code"   => $value["CODE_BEASISWA"],
          "program_number"  => $value["NOMOR_PROGRAM"],
          "program_date"    => $value["TANGGAL_PROGRAM"],
          "program_name"    => $value["NAMA_PROGRAM"],
          "beasiswa_note"   => $value["KETERANGAN_BEASISWA"],
          "kemenriset_code" => $value["KEMENRISET_CODE"],
          "internal_code"   => $value["INTERNAL_CODE"],
          "dn_code"         => $value["DN_CODE"],
          "periode_start"   => $value["PERIOD_START"],
          "periode_end"     => $value["PERIOD_END"],
          "ledger_id"       => $value["LEDGER_ID"],
          "currency"        => $value["CURRENCY"],
          "status"          => $value["STATUS"],
          "created_by"      => $value["CREATED_BY"],
          "updated_by"      => $value["LAST_UPDATE_BY"]
        ];

        $TRMasterBeasiswa->protect(false);
        $TRMasterBeasiswa->update($firstCheck["id_aj_master_beasiswa"], $data);
        $TRMasterBeasiswa->protect(true);
      } else {
        $generate_id = "AJ_MS_BSW_" . date("ymdhis") . "_" . substr(uniqid(rand()), -6);

        $data = [
          "id_aj_master_beasiswa" => $generate_id,
          "header_id"             => $value["HEADER_ID"],
          "beasiswa_code"         => $value["CODE_BEASISWA"],
          "program_number"        => $value["NOMOR_PROGRAM"],
          "program_date"          => $value["TANGGAL_PROGRAM"],
          "program_name"          => $value["NAMA_PROGRAM"],
          "beasiswa_note"         => $value["KETERANGAN_BEASISWA"],
          "kemenriset_code"       => $value["KEMENRISET_CODE"],
          "internal_code"         => $value["INTERNAL_CODE"],
          "dn_code"               => $value["DN_CODE"],
          "periode_start"         => $value["PERIOD_START"],
          "periode_end"           => $value["PERIOD_END"],
          "ledger_id"             => $value["LEDGER_ID"],
          "currency"              => $value["CURRENCY"],
          "status"                => $value["STATUS"],
          "created_by"            => $value["CREATED_BY"],
          "updated_by"            => $value["LAST_UPDATE_BY"]
        ];

        $TRMasterBeasiswa->protect(false);
        $TRMasterBeasiswa->save($data);
        $TRMasterBeasiswa->protect(true);
      }
    }
    // MASTER BEASISWA

    // PEMBAYARAN BEASISWA
    $AJPembayaranBeasiswa = new AJPembayaranBeasiswa();
    $TRPembayaranBeasiswa = new TRPembayaranBeasiswa();

    $getAJPembayaranBeasiswa = $AJPembayaranBeasiswa->findAll();

    foreach ($getAJPembayaranBeasiswa as $value) {
      $firstCheck = $TRPembayaranBeasiswa->where("header_id", $value["HEADER_ID"])->first();

      if (!empty($firstCheck)) {
        $data = [
          "header_id"           => $value["HEADER_ID"],
          "terima_id"           => $value["ID_TERIMA"],
          "beasiswa_code"       => $value["CODE_BEASISWA"],
          "program_name"        => $value["NAMA_PROGRAM         "],
          "periode_start"       => $value["PERIOD_START         "],
          "periode_end"         => $value["PERIOD_END           "],
          "campus_id"           => $value["CAMPUS_ID            "],
          "student_name"        => $value["NAMA_MHS             "],
          "faculty_org"         => $value["FACULTY_ORG          "],
          "faculty_name"        => $value["FACULTY_NAME         "],
          "prodi_org"           => $value["PRODI_ORG            "],
          "prodi_name"          => $value["PRODI_NAME           "],
          "semester"            => $value["SEMESTER             "],
          "terms"               => $value["TERMS                "],
          "gpa"                 => $value["GPA"],
          "start_terima"        => $value["START_TERIMA"],
          "bank_account_id"     => $value["BANK_ACCOUNT_ID      "],
          "bank_account_name"   => $value["BANK_ACCOUNT_NAME"],
          "bank_account_number" => $value["BANK_ACCOUNT_NUM"],
          "bea_amount"          => $value["BEA_AMOUNT"],
          "facility_code_type"  => $value["FASILITAS_KODE_TYPE  "],
          "facility_desc"       => $value["FASILITAS_DESC       "],
          "keterangan"          => $value["KETERANGAN"],
          "gl_flag"             => $value["GL_FLAG"],
          "gl_created_at"       => $value["GL_CREATE_DATE"],
          "check_number"        => $value["CHECK_NUMBER         "],
          "check_date"          => $value["CHECK_DATE           "],
          "donatur_kode"        => $value["KODE_DONATUR"],
          "donatur_name"        => $value["NAMA_DONATUR"],
          "invoice_number"      => $value["INVOICE_NUM"],
          "invoice_date"        => $value["INVOICE_DATE"],
          "created_by"          => $value["CREATED_BY           "],
          "updated_by"          => $value["LAST_UPDATED_BY      "]
        ];

        $TRPembayaranBeasiswa->protect(false);
        $TRPembayaranBeasiswa->update($firstCheck["id_aj_pembayaran_beasiswa"], $data);
        $TRPembayaranBeasiswa->protect(true);
      } else {
        $generate_id = "AJ_PM_BSW_" . date("ymdhis") . "_" . substr(uniqid(rand()), -6);

        $data = [
          "id_aj_pembayaran_beasiswa" => $generate_id,
          "header_id"                 => $value["HEADER_ID"],
          "terima_id"                 => $value["ID_TERIMA"],
          "beasiswa_code"             => $value["CODE_BEASISWA"],
          "program_name"              => $value["NAMA_PROGRAM         "],
          "periode_start"             => $value["PERIOD_START         "],
          "periode_end"               => $value["PERIOD_END           "],
          "campus_id"                 => $value["CAMPUS_ID            "],
          "student_name"              => $value["NAMA_MHS             "],
          "faculty_org"               => $value["FACULTY_ORG          "],
          "faculty_name"              => $value["FACULTY_NAME         "],
          "prodi_org"                 => $value["PRODI_ORG            "],
          "prodi_name"                => $value["PRODI_NAME           "],
          "semester"                  => $value["SEMESTER             "],
          "terms"                     => $value["TERMS                "],
          "gpa"                       => $value["GPA"],
          "start_terima"              => $value["START_TERIMA"],
          "bank_account_id"           => $value["BANK_ACCOUNT_ID      "],
          "bank_account_name"         => $value["BANK_ACCOUNT_NAME"],
          "bank_account_number"       => $value["BANK_ACCOUNT_NUM"],
          "bea_amount"                => $value["BEA_AMOUNT"],
          "facility_code_type"        => $value["FASILITAS_KODE_TYPE  "],
          "facility_desc"             => $value["FASILITAS_DESC       "],
          "keterangan"                => $value["KETERANGAN"],
          "gl_flag"                   => $value["GL_FLAG"],
          "gl_created_at"             => $value["GL_CREATE_DATE"],
          "check_number"              => $value["CHECK_NUMBER         "],
          "check_date"                => $value["CHECK_DATE           "],
          "donatur_kode"              => $value["KODE_DONATUR"],
          "donatur_name"              => $value["NAMA_DONATUR"],
          "invoice_number"            => $value["INVOICE_NUM"],
          "invoice_date"              => $value["INVOICE_DATE"],
          "created_by"                => $value["CREATED_BY           "],
          "updated_by"                => $value["LAST_UPDATED_BY      "]
        ];

        $TRPembayaranBeasiswa->protect(false);
        $TRPembayaranBeasiswa->save($data);
        $TRPembayaranBeasiswa->protect(true);
      }
    }
    // PEMBAYARAN BEASISWA

    // PENERIMAAN BEASISWA
    $AJPenerimaanBeasiswa = new AJPenerimaanBeasiswa();
    $TRPenerimaanBeasiswa = new TRPenerimaanBeasiswa();

    $getAJPenerimaanBeasiswa = $AJPenerimaanBeasiswa->findAll();

    foreach ($getAJPenerimaanBeasiswa as $value) {
      $firstCheck = $TRPenerimaanBeasiswa->where("header_id", $value["HEADER_ID"])->first();

      if (!empty($firstCheck)) {
        $data = [
          "header_id"           => $value["HEADER_ID"],
          "received_no"         => $value["NO_PENERIMA"],
          "received_date"       => $value["TANGGAL_TERIMA"],
          "program_id"          => $value["ID_PROGRAM"],
          "program_code"        => $value["NOMOR_PROGRAM"],
          "program_name"        => $value["NAMA_PROGRAM"],
          "sponsor_name"        => $value["NAMA_SPONSOR"],
          "periode_start"       => $value["PERIOD_START"],
          "periode_end"         => $value["PERIOD_END"],
          "account_bank_id"     => $value["BANK_ACCOUNT_ID"],
          "account_bank_name"   => $value["BANK_ACCOUNT_NAME"],
          "account_bank_number" => $value["BANK_ACCOUNT_NUM"],
          "currency"            => $value["CURRENCY"],
          "bea_amount"          => $value["BEA_AMOUNT"],
          "facility_code_type"  => $value["FASILITAS_KODE_TYPE"],
          "facility_desc"       => $value["FASILITAS_DESC"],
          "note"                => $value["KETERANGAN"],
          "beasiswa_code"       => $value["CODE_BEASISWA"],
          "journal_flag"        => $value["JOURNAL_FLAG"],
          "donatur_kode"        => $value["KODE_DONATUR"],
          "donatur_name"        => $value["NAMA_DONATUR"],
          "receipt_status"      => $value["RECEIPT_NUMBER"],
          "receipt_number"      => $value["RECEIPT_STATUS"],
          "receipt_msg"         => $value["RECEIPT_MSG"],
          "created_by"          => $value["CREATED_BY"],
          "updated_by"          => $value["LAST_UPDATED_BY"]
        ];

        $TRPenerimaanBeasiswa->protect(false);
        $TRPenerimaanBeasiswa->update($firstCheck["id_aj_penerimaan_beasiswa"], $data);
        $TRPenerimaanBeasiswa->protect(true);
      } else {
        $generate_id = "AJ_PR_BSW_" . date("ymdhis") . "_" . substr(uniqid(rand()), -6);

        $data = [
          "id_aj_penerimaan_beasiswa" => $generate_id,
          "header_id"                 => $value["HEADER_ID"],
          "received_no"               => $value["NO_PENERIMA"],
          "received_date"             => $value["TANGGAL_TERIMA"],
          "program_id"                => $value["ID_PROGRAM"],
          "program_code"              => $value["NOMOR_PROGRAM"],
          "program_name"              => $value["NAMA_PROGRAM"],
          "sponsor_name"              => $value["NAMA_SPONSOR"],
          "periode_start"             => $value["PERIOD_START"],
          "periode_end"               => $value["PERIOD_END"],
          "account_bank_id"           => $value["BANK_ACCOUNT_ID"],
          "account_bank_name"         => $value["BANK_ACCOUNT_NAME"],
          "account_bank_number"       => $value["BANK_ACCOUNT_NUM"],
          "currency"                  => $value["CURRENCY"],
          "bea_amount"                => $value["BEA_AMOUNT"],
          "facility_code_type"        => $value["FASILITAS_KODE_TYPE"],
          "facility_desc"             => $value["FASILITAS_DESC"],
          "note"                      => $value["KETERANGAN"],
          "beasiswa_code"             => $value["CODE_BEASISWA"],
          "journal_flag"              => $value["JOURNAL_FLAG"],
          "donatur_kode"              => $value["KODE_DONATUR"],
          "donatur_name"              => $value["NAMA_DONATUR"],
          "receipt_status"            => $value["RECEIPT_NUMBER"],
          "receipt_number"            => $value["RECEIPT_STATUS"],
          "receipt_msg"               => $value["RECEIPT_MSG"],
          "created_by"                => $value["CREATED_BY"],
          "updated_by"                => $value["LAST_UPDATED_BY"]
        ];

        $TRPenerimaanBeasiswa->protect(false);
        $TRPenerimaanBeasiswa->save($data);
        $TRPenerimaanBeasiswa->protect(true);
      }
    }
    // PENERIMAAN BEASISWA

    // PROJECT ADMINISTRATION
    $AJProjectAdministration = new AJProjectAdministration();
    $TRProjectAdministration = new TRProjectAdministration();

    $getAJProjectAdministration = $AJProjectAdministration->findAll();

    foreach ($getAJProjectAdministration as $value) {
      $firstCheck = $TRProjectAdministration->where("header_id", $value["HEADER_ID"])->first();

      if (!empty($firstCheck)) {
        $data = [
          "header_id"           => $value["HEADER_ID"],
          "received_id"         => $value["ID_TERIMA"],
          "received_no"         => $value["NO_PENERIMA"],
          "received_date"       => $value["TANGGAL_TERIMA"],
          "beasiswa_code"       => $value["CODE_BEASISWA"],
          "program_name"        => $value["NAMA_PROGRAM"],
          "sponsor_name"        => $value["NAMA_SPONSOR"],
          "periode_start"       => $value["PERIOD_START"],
          "periode_end"         => $value["PERIOD_END"],
          "account_bank_id"     => $value["BANK_ACCOUNT_ID"],
          "account_bank_name"   => $value["BANK_ACCOUNT_NAME"],
          "account_bank_number" => $value["BANK_ACCOUNT_NUM"],
          "bea_amount"          => $value["BEA_AMOUNT"],
          "facility_code_type"  => $value["FASILITAS_KODE_TYPE"],
          "facility_desc"       => $value["FASILITAS_DESC"],
          "note"                => $value["KETERANGAN"],
          "gl_flag"             => $value["GL_FLAG"],
          "gl_created_at"       => $value["GL_CREATE_DATE"],
          "check_number"        => $value["CHECK_NUMBER"],
          "check_date"          => $value["CHECK_DATE"],
          "invoice_number"      => $value["INVOICE_NUM"],
          "donatur_kode"        => $value["KODE_DONATUR"],
          "donatur_name"        => $value["NAMA_DONATUR"],
          "created_by"          => $value["CREATED_BY"],
          "updated_by"          => $value["LAST_UPDATED_BY"]
        ];

        $TRProjectAdministration->protect(false);
        $TRProjectAdministration->update($firstCheck["id_aj_project_administration"], $data);
        $TRProjectAdministration->protect(true);
      } else {
        $generate_id = "AJ_PJ_ADM_" . date("ymdhis") . "_" . substr(uniqid(rand()), -6);

        $data = [
          "id_aj_project_administration" => $generate_id,
          "header_id"                    => $value["HEADER_ID"],
          "received_id"                  => $value["ID_TERIMA"],
          "received_no"                  => $value["NO_PENERIMA"],
          "received_date"                => $value["TANGGAL_TERIMA"],
          "beasiswa_code"                => $value["CODE_BEASISWA"],
          "program_name"                 => $value["NAMA_PROGRAM"],
          "sponsor_name"                 => $value["NAMA_SPONSOR"],
          "periode_start"                => $value["PERIOD_START"],
          "periode_end"                  => $value["PERIOD_END"],
          "account_bank_id"              => $value["BANK_ACCOUNT_ID"],
          "account_bank_name"            => $value["BANK_ACCOUNT_NAME"],
          "account_bank_number"          => $value["BANK_ACCOUNT_NUM"],
          "bea_amount"                   => $value["BEA_AMOUNT"],
          "facility_code_type"           => $value["FASILITAS_KODE_TYPE"],
          "facility_desc"                => $value["FASILITAS_DESC"],
          "note"                         => $value["KETERANGAN"],
          "gl_flag"                      => $value["GL_FLAG"],
          "gl_created_at"                => $value["GL_CREATE_DATE"],
          "check_number"                 => $value["CHECK_NUMBER"],
          "check_date"                   => $value["CHECK_DATE"],
          "invoice_number"               => $value["INVOICE_NUM"],
          "donatur_kode"                 => $value["KODE_DONATUR"],
          "donatur_name"                 => $value["NAMA_DONATUR"],
          "created_by"                   => $value["CREATED_BY"],
          "updated_by"                   => $value["LAST_UPDATED_BY"]
        ];

        $TRProjectAdministration->protect(false);
        $TRProjectAdministration->save($data);
        $TRProjectAdministration->protect(true);
      }
    }
    // PROJECT ADMINISTRATION

  }
}
