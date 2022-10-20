<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('LoginController');
$routes->setDefaultMethod('Index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

// Login
$routes->group('login', function ($routes) {
	$routes->get('/', 'LoginController::Index');
	$routes->post('process_login', 'LoginController::DoLogin');
});

// Integration
$routes->get('/integrate-profit', 'Integration/Universitas/IntegrationProfitController::Index');
$routes->get('/integrate-beasiswa', 'Integration/Universitas/IntegrationBeasiswaController::Index');
$routes->get('/integrate-mahasiswa', 'Integration/Universitas/IntegrationMahasiswaController::Index');
$routes->get('/integrate-mahasiswabaru', 'Integration/Universitas/IntegrationMahasiswaBaruController::Index');
$routes->get('/integrate-aging', 'Integration/Universitas/IntegrationPiutangAgingController::Index');
$routes->get('/integrate-sdmreport', 'Integration/Universitas/IntegrationSDMReportController::Index');

$routes->get('/integrate-finance-yayasan', 'Integration/Yayasan/IntegrationFinanceYayasanController::Index');
$routes->get('/integrate-finance-universitas', 'Integration/Universitas/IntegrationFinanceUniversitasController::Index');
$routes->get('/integrate-finance-rumahsakit', 'Integration/RumahSakit/IntegrationFinanceRumahSakitController::Index');

// Error Passing
$routes->get('/503', 'ErrorController::Forbidden');

// Portal
$routes->get('/', 'HomeController::Index');
$routes->post('/res', 'HomeController::Res');
$routes->post('/chart-headcount', 'HomeController::headcountChart');
// $routes->post('/filter-PB_Konsolidasi', 'HomeController::PBKonsolidasiChart');
// $routes->post('/filter-Kenaikan_PB_Konsolidasi', 'HomeController::KenaikanPBKonsolidasiChart');
// $routes->post('/pendapatan_beban_konsolidasi-table', 'HomeController::PendapatanBebanTableKonsolidasi');
$routes->post('/chart-pb-konsolidasi', 'HomeController::pbKonsolidasiChart');
$routes->post('/chart-kenaikan-pb-konsolidasi', 'HomeController::kenaikanPbKonsolidasiChart');
$routes->post('/table-pb-konsolidasi', 'HomeController::pbKonsolidasiTable');
$routes->post('/prodi_PiutangAging', 'HomeController::ProdiPiutangAging');

// Routing Yayasan
$routes->group('yayasan', function ($routes) {

	$routes->get('/', 'Yayasan/DashboardController::Index');

	$routes->group('profile', function ($routes) {
		$routes->get('/', 'ProfilController::IndexYayasan');
		$routes->post('update', 'ProfilController::Update');
		$routes->post('update_password', 'ProfilController::UpdatePassword');
	});

	$routes->group('rencana_kerja', function ($routes) {
		$routes->get('/', 'Yayasan/RencanaKerjaController::Index');
		$routes->post('res', 'Yayasan/RencanaKerjaController::res');
		$routes->post('read', 'Yayasan/RencanaKerjaController::read');
		$routes->post('create', 'Yayasan/RencanaKerjaController::Create');
		$routes->post('edit', 'Yayasan/RencanaKerjaController::Edit');
		$routes->post('save', 'Yayasan/RencanaKerjaController::Save');
		$routes->post('update', 'Yayasan/RencanaKerjaController::Update');
		$routes->post('delete', 'Yayasan/RencanaKerjaController::Delete');
		$routes->post('delete-selected', 'Yayasan/RencanaKerjaController::DeleteSelected');
		$routes->post('import', 'Yayasan/RencanaKerjaController::Import');
		$routes->post('import_preview', 'Yayasan/RencanaKerjaController::ImportInsertData_Preview');
		$routes->post('import_save', 'Yayasan/RencanaKerjaController::ImportInsertData_Proses');
		$routes->get('upload', 'Yayasan/RencanaKerjaController::ViewUpload');
	});

	$routes->group('rencana_kerja_detail', function ($routes) {
		$routes->post('res', 'Yayasan/RencanaKerjaDetailController::Res');
		$routes->post('read', 'Yayasan/RencanaKerjaDetailController::read');
		$routes->post('edit', 'Yayasan/RencanaKerjaDetailController::Edit');
		$routes->post('save', 'Yayasan/RencanaKerjaDetailController::Save');
		$routes->post('update', 'Yayasan/RencanaKerjaDetailController::Update');
		$routes->post('delete', 'Yayasan/RencanaKerjaDetailController::Delete');
		$routes->post('verify', 'Yayasan/RencanaKerjaDetailController::Verify');
	});

	$routes->group('anggaran', function ($routes) {
		$routes->get('/', 'Yayasan/AnggaranController::Index');
		$routes->post('res', 'Yayasan/AnggaranController::Res');
		$routes->post('read', 'Yayasan/AnggaranController::Read');
		$routes->post('edit', 'Yayasan/AnggaranController::Edit');
		$routes->post('update', 'Yayasan/AnggaranController::Update');
		$routes->post('delete', 'Yayasan/AnggaranController::Delete');
		$routes->post('delete-selected', 'Yayasan/AnggaranController::DeleteSelected');
		$routes->post('import', 'Yayasan/AnggaranController::Import');
		$routes->post('import_preview', 'Yayasan/AnggaranController::ImportInsertData_Preview');
		$routes->post('import_save', 'Yayasan/AnggaranController::ImportInsertData_Proses');
		$routes->get('upload', 'Yayasan/AnggaranController::ViewUpload');
	});

	$routes->group('laporan_keuangan', function ($routes) {
		$routes->get('/', 'Yayasan/LaporanKeuanganController::Chart');
		$routes->get('chart', 'Yayasan/LaporanKeuanganController::Chart');
		$routes->post('neraca-filter', 'Yayasan/LaporanKeuanganController::FilterChartNeraca');
		$routes->post('laba_rugi-filter', 'Yayasan/LaporanKeuanganController::FilterChartLabaRugi');
		$routes->post('realisasi_apb-filter', 'Yayasan/LaporanKeuanganController::FilterChartRealisasiAPB');
		$routes->post('posisi_keuangan-filter', 'Yayasan/LaporanKeuanganController::FilterChartPosisiKeuangan');
		$routes->post('cashflow-filter', 'Yayasan/LaporanKeuanganController::FilterChartCashflow');
		$routes->post('capex-filter', 'Yayasan/LaporanKeuanganController::FilterChartCapex');

		$routes->post('pendapatan_beban-table', 'Yayasan/LaporanKeuanganController::PendapatanBebanTable');
		$routes->post('neraca-table', 'Yayasan/LaporanKeuanganController::NeracaTable');
		$routes->post('laba_rugi-table', 'Yayasan/LaporanKeuanganController::LabaRugiTable');
		$routes->post('cashflow-table', 'Yayasan/LaporanKeuanganController::CashflowTable');
		$routes->post('capex-table', 'Yayasan/LaporanKeuanganController::CapexTable');

		// 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
		$routes->post('kas-setara-kas-filter', 'Yayasan/LaporanKeuanganController::FilterChartKasSetaraKas');
		$routes->post('trend-investasi-filter', 'Yayasan/LaporanKeuanganController::FilterChartTrendInvestasi');
		$routes->post('investasi-filter', 'Yayasan/LaporanKeuanganController::FilterChartInvestasi');
		$routes->post('pengeluaran-investasi-capex-filter', 'Yayasan/LaporanKeuanganController::FilterChartPengeluaranVesCapex');
		$routes->post('aset-tetap-filter', 'Yayasan/LaporanKeuanganController::FilterChartAsetTetap');
		$routes->post('aset-tetap-konsolidasi-filter', 'Yayasan/LaporanKeuanganController::FilterChartAsetTetapKonsolidasi');
		// 8F4A9811B95C1D7C3CECD0BFB660B9C4A29E1042DF308F56763BA5EB4847C211
	});

});

// Routing Universitas
$routes->group('universitas', function ($routes) {
	
	// Dashboard
	$routes->get('/', 'DashboardController::index');

	// Profile
	$routes->group('profile', function ($routes) {
		$routes->get('/', 'ProfilController::IndexUniversitas');
		$routes->post('update', 'ProfilController::Update');
		$routes->post('update_password', 'ProfilController::UpdatePassword');
	});

	// Mahasiswa dan Dosen
	$routes->group('mahasiswa_dan_dosen', function ($routes) {
		$routes->get('/', 'Dashboard/MahasiswaDosenController::Index');
	
		$routes->post('total_mahasiswa', 'Dashboard/MahasiswaDosenController::TotalMahasiswa');
		$routes->post('compare_total_mahasiswa', 'Dashboard/MahasiswaDosenController::CompareTotalMahasiswa');
		$routes->post('get_prodi', 'Dashboard/MahasiswaDosenController::GetProdi');
	
		$routes->post('total_mahasiswa_baru', 'Dashboard/MahasiswaDosenController::TotalMahasiswaBaru');
		$routes->post('compare_total_mahasiswabaru', 'Dashboard/MahasiswaDosenController::CompareTotalMahasiswaBaru');
	
		$routes->post('total_dosen', 'Dashboard/MahasiswaDosenController::TotalDosen');
		$routes->post('compare_total_dosen', 'Dashboard/MahasiswaDosenController::CompareTotalDosen');
		
		$routes->post('rasio_dosen_mahasiswa', 'Dashboard/MahasiswaDosenController::RasioDosenMahasiswa');
		$routes->post('compare_rasio_dosenmahasiswa', 'Dashboard/MahasiswaDosenController::CompareRasioDosenMahasiswa');

		$routes->post('set_preference', 'Dashboard/MahasiswaDosenController::SetPreference');
	});

	// Kegiatan Tridharma PT
	$routes->group('tridharma', function ($routes) {
		$routes->get('/', 'Dashboard/KegiatanTridharma/TridharmaController::Index');

		$routes->post('jumlah_penelitian', 'Dashboard/KegiatanTridharma/TridharmaController::jumlahPenelitian');
		$routes->post('resjumlahpenelitian', 'Dashboard/KegiatanTridharma/TridharmaController::resJumlahPenelitian');
		$routes->post('chartjumlahpenelitian', 'Dashboard/KegiatanTridharma/TridharmaController::chartJumlahPenelitian');

		$routes->post('jumlah_pengabdian', 'Dashboard/KegiatanTridharma/TridharmaController::jumlahPengabdian');
		$routes->post('resjumlahpengabdian', 'Dashboard/KegiatanTridharma/TridharmaController::resJumlahPengabdian');
		$routes->post('chartjumlahpengabdian', 'Dashboard/KegiatanTridharma/TridharmaController::chartJumlahPengabdian');
	});

	// Hibah
	$routes->group('hibah', function ($routes) {
		$routes->get('/', 'Dashboard/KegiatanTridharma/HibahController::Index');
		$routes->post('res', 'Dashboard/KegiatanTridharma/HibahController::Res');
		$routes->post('read', 'Dashboard/KegiatanTridharma/HibahController::Read');
		$routes->post('create', 'Dashboard/KegiatanTridharma/HibahController::Create');
		$routes->post('save', 'Dashboard/KegiatanTridharma/HibahController::Save');
		$routes->post('edit', 'Dashboard/KegiatanTridharma/HibahController::Edit');
		$routes->post('update', 'Dashboard/KegiatanTridharma/HibahController::Update');
		$routes->post('delete', 'Dashboard/KegiatanTridharma/HibahController::Delete');
		$routes->post('periode', 'Dashboard/KegiatanTridharma/HibahController::Periode');
		$routes->post('import', 'Dashboard/KegiatanTridharma/HibahController::Import');
		$routes->post('import_preview', 'Dashboard/KegiatanTridharma/HibahController::ImportInsertData_Preview');
		$routes->post('import_save', 'Dashboard/KegiatanTridharma/HibahController::ImportInsertData_Proses');
		$routes->post('chart', 'Dashboard/KegiatanTridharma/HibahController::ChartHibah');
	});

	// Kerja Sama
	$routes->group('kerjasama', function ($routes) {
		$routes->get('/', 'Dashboard/KegiatanTridharma/KerjaSamaController::Index');

		$routes->post('chartFacultyInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::ChartFaculty');
		$routes->post('resFacultyInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::ResFaculty');
		$routes->post('dtFacultyInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::DataTablesFaculty');

		$routes->post('chartStudentInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::ChartStudent');
		$routes->post('resStudentInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::ResStudent');
		$routes->post('dtStudentInboundOutbound', 'Dashboard/KegiatanTridharma/KerjaSamaController::DataTablesStudent');
	});

	// Laporan Keuangan (Universitas)
	$routes->group('laporan_keuangan', function ($routes) {
		$routes->get('/', 'Dashboard/LaporanKeuanganController::Index');

		$routes->post('konsolidasi_pb_yaj-filter', 'Dashboard/LaporanKeuanganController::FilterKonsolidasiPBYAJ');

		$routes->post('kenaikan_pb-filter', 'Dashboard/LaporanKeuanganController::FilterKenaikanPB');

		$routes->post('realisasi_apb-filter', 'Dashboard/LaporanKeuanganController::FilterChartRealisasiAPB');

		$routes->post('posisi_keuangan-filter', 'Dashboard/LaporanKeuanganController::FilterChartPosisiKeuangan');

		$routes->post('kas-setara-kas-filter', 'Dashboard/LaporanKeuanganController::FilterChartKasSetaraKas');

		$routes->post('cashflow-filter', 'Dashboard/LaporanKeuanganController::FilterChartCashflow');
		$routes->post('cashflow_detail-filter', 'Dashboard/LaporanKeuanganController::FilterChartCashflowDetail');

		$routes->post('aset_tetap-filter', 'Dashboard/LaporanKeuanganController::FilterChartAsetTetap');
		$routes->post('capex-filter', 'Dashboard/LaporanKeuanganController::FilterChartCapex');
		
		$routes->post('piutang_aging-filter', 'Dashboard/LaporanKeuanganController::piutangAging');

		$routes->post('pendapatan_beban-table', 'Dashboard/LaporanKeuanganController::PendapatanBebanTable');
	});

	// Rencana Kerja
	$routes->group('rencana_kerja', function ($routes) {
		$routes->get('/', 'Dashboard/RencanaKerjaController::Index');
		$routes->post('res', 'Dashboard/RencanaKerjaController::res');
		$routes->post('read', 'Dashboard/RencanaKerjaController::read');
		$routes->post('create', 'Dashboard/RencanaKerjaController::Create');
		$routes->post('edit', 'Dashboard/RencanaKerjaController::Edit');
		$routes->post('save', 'Dashboard/RencanaKerjaController::Save');
		$routes->post('update', 'Dashboard/RencanaKerjaController::Update');
		$routes->post('delete', 'Dashboard/RencanaKerjaController::Delete');
		$routes->post('delete-selected', 'Dashboard/RencanaKerjaController::DeleteSelected');
	});

	// Detail Rencana Kerja
	$routes->group('rencana_kerja_detail', function ($routes) {
		$routes->post('res', 'Dashboard/RencanaKerjaDetailController::Res');
		$routes->post('read', 'Dashboard/RencanaKerjaDetailController::read');
		$routes->post('edit', 'Dashboard/RencanaKerjaDetailController::Edit');
		$routes->post('save', 'Dashboard/RencanaKerjaDetailController::Save');
		$routes->post('update', 'Dashboard/RencanaKerjaDetailController::Update');
		$routes->post('delete', 'Dashboard/RencanaKerjaDetailController::Delete');
		$routes->post('verify', 'Dashboard/RencanaKerjaDetailController::Verify');
	});

	// Anggaran
	$routes->group('anggaran', function ($routes) {
		$routes->get('/', 'Dashboard/AnggaranController::Index');
		$routes->post('res', 'Dashboard/AnggaranController::Res');
		$routes->post('read', 'Dashboard/AnggaranController::Read');
		$routes->post('edit', 'Dashboard/AnggaranController::Edit');
		$routes->post('update', 'Dashboard/AnggaranController::Update');
		$routes->post('delete', 'Dashboard/AnggaranController::Delete');
		$routes->post('delete-selected', 'Dashboard/AnggaranController::DeleteSelected');
		$routes->post('import', 'Dashboard/AnggaranController::Import');
		$routes->post('import_preview', 'Dashboard/AnggaranController::ImportInsertData_Preview');
		$routes->post('import_save', 'Dashboard/AnggaranController::ImportInsertData_Proses');
		$routes->get('upload', 'Dashboard/AnggaranController::ViewUpload');
	});

	// Marketing / Kompetitor
	$routes->group('marketing_kompetitor', function ($routes) {
		$routes->get('/', 'Dashboard/MarketingKompetitorController::Index');
		$routes->post('res', 'Dashboard/MarketingKompetitorController::Res');
		$routes->post('read', 'Dashboard/MarketingKompetitorController::Read');
		$routes->post('create', 'Dashboard/MarketingKompetitorController::Create');
		$routes->post('save', 'Dashboard/MarketingKompetitorController::Save');
		$routes->post('edit', 'Dashboard/MarketingKompetitorController::Edit');
		$routes->post('update', 'Dashboard/MarketingKompetitorController::Update');
		$routes->post('delete', 'Dashboard/MarketingKompetitorController::Delete');
		$routes->post('delete-selected', 'Dashboard/MarketingKompetitorController::DeleteSelected');
		$routes->post('import', 'Dashboard/MarketingKompetitorController::Import');
		$routes->post('import_preview', 'Dashboard/MarketingKompetitorController::ImportInsertData_Preview');
		$routes->post('import_save', 'Dashboard/MarketingKompetitorController::ImportInsertData_Proses');
		$routes->post('chart', 'Dashboard/MarketingKompetitorController::ChartMarketingKompetitor');
		$routes->get('upload', 'Dashboard/MarketingKompetitorController::ViewUpload');
	});

	// Laporan Beasiswa
	$routes->group('laporan_beasiswa', function ($routes) {
		$routes->get('/', 'Dashboard/LaporanBeasiswaController::Index');
		$routes->post('jenisbeasiswa', 'Dashboard/LaporanBeasiswaController::ChartJenisBeasiswa');
		$routes->post('sumberdanabeasiswa', 'Dashboard/LaporanBeasiswaController::ChartSumberDanaBeasiswa');
	});	

	// Reset Filter
	$routes->post('reset_filter', 'DashboardController::ResetFilter');
	// Reset Compare
	$routes->post('reset_compare', 'DashboardController::ResetCompare');
	// Reset Global Compare
	$routes->post('reset_global_compare', 'DashboardController::ResetGlobalCompare');
	// Reset Global Filter
	$routes->post('reset_global_filter', 'DashboardController::ResetGlobalFilter');
});

// User Role
$routes->group('user_role', function ($routes) {
	$routes->get('/', 'UserRoleController::Index');
	$routes->post('res', 'UserRoleController::Res');
	$routes->post('add', 'UserRoleController::Add');
	$routes->post('save', 'UserRoleController::Save');
	$routes->post('delete', 'UserRoleController::Delete');
	$routes->post('get_data', 'UserRoleController::GetData');
	$routes->get('get_data_add', 'UserRoleController::GetDataAdd');
	$routes->get('get_data_update', 'UserRoleController::GetDataUpdate');
});

// User Management
$routes->group('user_management', function ($routes) {
	$routes->get('/', 'UserManagementController::index');
	$routes->post('res', 'UserManagementController::res');
	$routes->post('read', 'UserManagementController::read');
	$routes->post('create', 'UserManagementController::create');
	$routes->post('store', 'UserManagementController::store');
	$routes->post('edit', 'UserManagementController::edit');
	$routes->post('update', 'UserManagementController::update');
	$routes->post('destroy', 'UserManagementController::destroy');
});





// Logout
$routes->get('logout', function () {
	$session = \Config\Services::session();
	$session->destroy();
	return redirect()->to('login');
});

// Get Session
$routes->get('session', function () {
	$session = session();
	echo "<pre>";
	print_r($session->get());
	echo "</pre>";
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */

if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
