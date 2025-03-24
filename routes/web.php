<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\KetuaController;
use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SimpananController;
use App\Http\Controllers\BendaharaController;
use App\Http\Controllers\PenarikanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\VerifikasiController;
use App\Http\Controllers\LandingpageController;
use App\Http\Controllers\SalaryStatusController;
use App\Http\Controllers\SimpananPokokController;
use App\Http\Controllers\SimpananWajibController;
use App\Http\Controllers\DashboardPagesControlller;
use App\Http\Controllers\PengajuanPinjamanController;
use App\Http\Controllers\SimpananBerjangkaController;
use App\Http\Controllers\HistoryTransactionController;
use App\Http\Controllers\SimpananSukarelaController;
use GuzzleHttp\Middleware;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Landing Page
Route::get('/', [LandingpageController::class, 'page'])->name('landingpage');

Route::get('/error', function () {
    return view('errors');
})->name('error')->middleware('auth');
Route::get('/redirect-dashboard', [RedirectController::class, 'redirect'])->name('redirect.dashboard');

// Auth Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::any('/register/iuran', 'register')->name('register-verifikasi');
    Route::get('/iuran', 'store')->name('iuran-register');

    Route::get('/login', 'index')->name('login');
    Route::post('/login', 'validasilogin')->name('login-verifikasi');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/informasi-verifikasi', [VerifikasiController::class, 'verifikasi'])->name('informasi-verifikasi');
});

// Profile Routes
Route::prefix('profile')->middleware('auth')->group(function () {
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/forgot-password', 'forgotpassword')->name('forgot-password');
        Route::post('/forgot-password', 'changePassword')->name('cange-password');
        Route::get('/setting-profile', 'setting')->name('setting-profile');
        Route::post('/virtual-account', 'storeVA')->name('virtual-account');
    });
});

// Anggota Routes
Route::prefix('anggota')->middleware(['auth', 'role:anggota,admin'])->group(function () {
    Route::get('/home-anggota', [AnggotaController::class, 'homeanggota'])->name('home-anggota');
    Route::get('/tambah-simpanan', [AnggotaController::class, 'tambahsimpanan'])->name('anggota.tambah-simpanan');

    Route::controller(DashboardPagesControlller::class)->group(function () {
        Route::get('/data-emergency', 'emergencyAnggota')->name('data.emergency');
        Route::get('/data-angunan', 'angunanyAnggota')->name('data.angunan');
        Route::get('/data-non-angunan', 'nonangunanAnggota')->name('data.nonangunan');
    });

    // Simpanan Routes
    Route::controller(SimpananController::class)->group(function () {
        // Rute untuk menampilkan halaman simpanan
        Route::get('/simpanan-wajib', 'simpananwajib')->name('simpanan-wajib');
        Route::get('/simpanan-sukarela', 'simpanansukarela')->name('simpanan-sukarela');
        Route::get('/simpanan-berjangka/form', [SimpananBerjangkaController::class, 'showForm'])->name('simpanan-berjangka.form');
        Route::post('/simpanan-berjangka/create', [SimpananBerjangkaController::class, 'simpananBerjangka'])->name('simpanan-berjangka.create');
    });

    Route::get('/simpanan-wajib', [SimpananWajibController::class, 'homewajib'])->name('index.simpanan-wajib');
    // Simpanan Berjangka Routes
    Route::controller(SimpananBerjangkaController::class)->group(function () {
        Route::get('/simpanan-berjangka', 'showForm')->name('simpanan-berjangka');
        Route::post('/simpanan-berjangka', 'createSimpananBerjangka')->name('create.simpanan.berjangka');
        Route::get('/simpanan-berjangka/{invoice}', 'getSimpananBerjangka')->name('get.simpanan.berjangka');
        Route::get('/payment-result', 'showPaymentResult')->name('payment.result');
        Route::get('/pengajuan-simpanan-berjangka/create', 'addSimpananBerjangka')->name('anggota.berjangka.add');
    });


    Route::controller(SimpananSukarelaController::class)->group(function () {
        Route::get('/pengajuan-simpanan-sukarela/create', 'createsimpanansukarela')->name('anggota.sukarela.create');
    });


    // Pinjaman Routes
    Route::controller(PengajuanPinjamanController::class)->group(function () {
        Route::get('/pengajuan-pinjaman-emergency/create', 'createEmergency')->name('anggota.emergency.create');
        Route::get('/pengajuan-pinjaman-angunan/create', 'createAngunan')->name('anggota.angunan.create');
        Route::get('/pengajuan-pinjaman-non-angunan/create', 'createNonAngunan')->name('anggota.nonangunan.create');
    });
    Route::resource('pengajuan-pinjaman', PengajuanPinjamanController::class);

    Route::controller(SimpananBerjangkaController::class)->group(function () {
        // Menampilkan form simpanan berjangka
        Route::get('/simpanan-berjangka', 'showForm')->name('simpanan-berjangka');

        // Menyimpan data simpanan berjangka

    });
    Route::get('/penarikan-simpanan', [PenarikanController::class, 'viewPenarikan'])->name('penarikan.view');
    Route::post('/penarikan-simpanan', [PenarikanController::class, 'ajukanPenarikan'])->name('penarikan.ajukan');
    Route::get('/penarikan-simpanan/verifikasi', [PenarikanController::class, 'verifikasi'])->name('penarikan.verifikasi');
    Route::post('/penarikan-simpanan/verifikasi', [PenarikanController::class, 'verifikasi'])->name('penarikan1.verifikasi');
});

// Payment Routes
Route::prefix('payment')->middleware('auth')->group(function () {
    Route::controller(PaymentController::class)->group(function () {
        Route::get('/transfer', 'showPaymentPage')->name('transfer-form');
        Route::post('/create-payment', 'sendRequest')->name('createPayment');
        Route::post('/payment-sukarela', 'processPaymentSukarela')->name('payment-sukarela');
        Route::post('/payment-wajib', 'processPaymentWajib')->name('payment-wajib');
        Route::post('/cancel-callback', 'handlePaymentCancelCallback')->name('payment.cancel.callback');
        Route::post('/success-callback', 'handlePaymentSuccessCallback')->name('payment.success.callback');
        Route::post('/expired-callback', 'handlePaymentExpiredCallback')->name('payment.expired.callback');
        Route::post('/process-payment', 'processPayment')->name('process-payment');
    });
});

// Admin Routes
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/home-admin', [AdminController::class, 'homeadmin'])->name('home-admin');
    Route::get('/data-anggota', [AdminController::class, 'dataanggota'])->name('data-anggota');

    Route::controller(AnggotaController::class)->group(function () {
        Route::post('/store', 'store')->name('anggota.store');
        Route::get('/{id}/edit', 'edit')->name('anggota.edit');
        Route::put('/{id}/edit', 'update')->name('anggota.update');
        Route::delete('/anggota/{id}/delete', 'delete')->name('anggota.delete');
    });

    Route::controller(NewsController::class)->group(function () {
        Route::get('/kelola-news', [NewsController::class, 'news'])->name('kelola-news');
        Route::post('/create-news', 'create')->name('create-news');
        Route::get('/news/{id}/edit', 'edit')->name('news.edit');
        Route::put('/news/{id}', 'update')->name('news.update');
        Route::delete('/{id}/delete', 'delete')->name('news.delete');
    });

    Route::get('/mutasi-simpanan', [AdminController::class, 'getAllSimpanan'])->name('mutasi-simpanan');
    Route::get('/simpanan', [AdminController::class, 'getSimpanan'])->name('getSimpanan');
    Route::get('/export-pdf', [AdminController::class, 'exportPDF'])->name('export.pdf.simpanan');
    Route::get('/dashboard/export-pdf', [AdminController::class, 'exportPdfDashboard'])->name('dashboard.export.pdf');

    Route::controller(DashboardPagesControlller::class)->group(function () {
        Route::get('/pinjaman-emergency', 'adminEmergency')->name('admin.mutasi.emergency');
        Route::get('/pinjaman-angunan', 'adminAngunan')->name('admin.mutasi.angunan');
        Route::get('/pinjaman-non-angunan', 'adminNonAngunan')->name('admin.mutasi.nonangunan');
        Route::get('pinjaman/export/excel/{jenisPinjaman}',  'exportExcelByJenis')->name('export.excel');
        Route::get('/simpanan-sukarela', 'adminSukarela')->name('admin.mutasi.sukarela');
    });
    Route::get('/export-pdf/{jenisPinjaman}', [PDFController::class, 'exportPDF'])->name('export.pdf');

    Route::controller(SalaryStatusController::class)->group(function () {
        Route::get('/data-potongan-gaji', 'index')->name('data.potongan.gaji');
        Route::get('/input-potongan-gaji/create/{uuid}', 'create')->name('input.potongan.gaji.create');
        Route::post('/input-potongan-gaji/store/{uuid}', 'store')->name('potongan.gaji.store');
        Route::get('/detail-potongan-gaji/{uuid}', 'show')->name('detail.potongan.gaji');
    });
});

// Manager Routes
Route::prefix('manager')->middleware(['auth', 'role:manager'])->group(function () {
    Route::controller(DashboardPagesControlller::class)->group(function () {
        Route::get('/home-manager', 'indexManager')->name('home.manager');
        Route::get('/approve-pinjaman-emergency', 'managerEmergency')->name('approve.manager.emergency');
        Route::get('/approve-pinjaman-angunan', 'managerAngunan')->name('approve.manager.angunan');
        Route::get('/approve-pinjaman-non-angunan', 'managerNonAngunan')->name('approve.manager.nonangunan');
        Route::get('/data-simpanan', 'indexSimpananWajib')->name('data.simpanan.manager');
        Route::get('/approve-manager', 'ManagerApproveRegister')->name('approve.manager');
    });

    Route::controller(ManagerController::class)->group(function () {
        Route::get('/data-simpanan-sukarela', [ManagerController::class, 'indexsimpanansukarela'])->name('data.simpanan.sukarela');
        Route::get('/data-simpanan-berjangka', [ManagerController::class, 'indexsimpananberjangka'])->name('data.simpanan.berjangka');
        Route::post('/pinjaman/{uuid}/{status}', 'updateStatusPinjaman')->name('manager.pinjaman.status');
        Route::post('/approve/{id}/{status}', 'updateStatus')->name('approve.update-status-manager');
        Route::post('/update-status-simpanan/{id}/{status}', [ManagerController::class, 'updateApprovalManagerSimpananSukarela'])->name('status.simpanan.sukarela');
        Route::post('/update-status-berjangka/{id}/{status}', [ManagerController::class, 'updateApprovalManagerSimpananBerjangka'])->name('status.simpanan.berjangka');
        Route::get('/count-data/{status}', 'countData');
        Route::get('/count-data-simpanan-sukarela/{status}', [ManagerController::class, 'countDataRekeningSimpananSukarela']);
        Route::get('/count-data-simpanan-berjangka/{status}', [ManagerController::class, 'countDataRekeningSimpananBerjangka']);
        Route::get('/send-registration-email/{id}', 'email')->name('send.email');
        Route::get('/data/filter', 'filter')->name('data.filter');
        Route::get('/data/search', 'search')->name('data.search');
        Route::delete('/data/delete/{id}', [ManagerController::class, 'delete'])->name('manager.data.delete');
        Route::get('/anggota/detail/{id}', 'getDetail')->name('data.detail');
        Route::put('/anggota/update/{id}', 'update')->name('anggota.update.data');
        Route::post('/anggota/reject/{id}', [ManagerController::class, 'reject'])->name('manager.anggota.reject');
        Route::get('/count-data-penarikan-sukarela/{status}', [ManagerController::class, 'countDataRekeningPenarikanSukarela']);
        Route::get('/count-data-penarikan-berjangka/{status}', [ManagerController::class, 'countDataRekeningPenarikanBerjangka']);
        Route::post('/update-status-penarikan/{id}/{status}', [ManagerController::class, 'updateApprovalManagerPenarikanSukarela'])->name('status.penarikan.sukarela');
        Route::post('/update-status-penarikan-berjangka/{id}/{status}', [ManagerController::class, 'updateApprovalManagerPenarikanBerjangka'])->name('status.penarikan.berjangka');
        Route::get('/penarikan/sukarela/approval', [ManagerController::class, 'penarikanSukarelaApproval'])->name('penarikan.sukarela.approval.manager');
        Route::get('/penarikan/berjangka/approval', [ManagerController::class, 'penarikanBerjangkaApproval'])->name('penarikan.berjangka.approval.manager');
    });
});

Route::get('/pinjaman/detail/{uuid}', [PengajuanPinjamanController::class, 'show'])->name('pinjaman.detail.uuid')->middleware('auth');

// Ketua Routes
Route::prefix('ketua')->middleware(['auth', 'role:ketua'])->group(function () {
    Route::controller(DashboardPagesControlller::class)->group(function () {
        Route::get('/home-ketua', 'indexKetua')->name('home-ketua');
        Route::get('/approve-ketua', 'dataRegister')->name('approve-ketua');
        Route::get('/approve-pinjaman-emergency', 'ketuaEmergency')->name('approve.ketua.emergency');
        Route::get('/approve-pinjaman-angunan', 'ketuaAngunan')->name('approve.ketua.angunan');
        Route::get('/approve-pinjaman-non-angunan', 'ketuaNonAngunan')->name('approve.ketua.nonangunan');
        Route::get('/approve-simpanan-berjangka', 'indexSimpananBerjangka')->name('approve.ketua.berjangka');
        Route::get('/approve-simpanan-sukarela', 'indexSimpananSukarela')->name('approve.ketua.sukarela');
    });

    Route::controller(KetuaController::class)->group(function () {
        Route::post('/pinjaman/{id}/{status}', 'updateStatusPinjamanKetua')->name('ketua.pinjaman.status');
        Route::get('/approve-simpanan',  'indexForApproval')->name('approve-simpanan-ketua');
        Route::get('/data-simpanan-sukarela-ketua', [KetuaController::class, 'indexsimpanansukarela'])->name('data.simpanan.sukarela.ketua');
        Route::get('/data-simpanan-berjangka-ketua', [KetuaController::class, 'indexsimpananberjangka'])->name('data.simpanan.berjangka.ketua');
        Route::put('/simpanan/approve/{id}', 'approve')->name('simpanan.approve-ketua');
        Route::put('/simpanan/reject/{id}',  'reject')->name('simpanan.reject-ketua');
        Route::post('/ketua/diterima/{id}/{status}', 'diterima')->name('approve.diterima-ketua');
        Route::post('/ketua/ditolak/{id}/{status}', 'ditolak')->name('approve.ditolak-ketua');
        Route::get('/count-data-simpanan-sukarela/{status}', [KetuaController::class, 'countDataRekeningSimpananSukarela']);
        Route::get('/count-data-simpanan-berjangka/{status}', [KetuaController::class, 'countDataRekeningSimpananBerjangka']);
        Route::post('/update-approval-ketua/{id}/{status}', [KetuaController::class, 'updateApprovalKetuaSimpananSukarela'])->name('update.approval.ketua');
        Route::post('/update-approval-ketua-berjangka/{id}/{status}', [KetuaController::class, 'updateApprovalKetuaSimpananBerjangka'])->name('update.approval.ketua.berjangka');
        Route::get('/data/filter', [KetuaController::class, 'filter'])->name('Ketua.data.filter');
        Route::get('/data/search', [KetuaController::class, 'search'])->name('Ketua.data.search');
        Route::delete('/data/delete/{id}', [KetuaController::class, 'delete'])->name('Ketua.data.delete');
        Route::get('/anggota/detail/{id}', [KetuaController::class, 'getDetail'])->name('Ketua.data.detail');
        Route::put('/anggota/update/{id}', [KetuaController::class, 'update'])->name('ketua.anggota.update.data');
        Route::post('/anggota/reject/{id}', [KetuaController::class, 'reject'])->name('Ketua.anggota.reject');
        Route::get('/count-data-penarikan-sukarela/{status}', [KetuaController::class, 'countDataRekeningPenarikanSukarela']);
        Route::get('/count-data-penarikan-berjangka/{status}', [KetuaController::class, 'countDataRekeningPenarikanBerjangka']);
        Route::post('/update-status-penarikan/{id}/{status}', [KetuaController::class, 'updateApprovalManagerPenarikanSukarela'])->name('status.penarikan.sukarela.ketua');
        Route::post('/update-status-penarikan-berjangka/{id}/{status}', [KetuaController::class, 'updateApprovalManagerPenarikanBerjangka'])->name('status.penarikan.berjangka.ketua');
        Route::get('/penarikan/sukarela/approval', [KetuaController::class, 'penarikanSukarelaApproval'])->name('penarikan.sukarela.approval.ketua');
        Route::get('/penarikan/berjangka/approval', [KetuaController::class, 'penarikanBerjangkaApproval'])->name('penarikan.berjangka.approval.ketua');
    });
});

// Bendahara Routes
Route::prefix('bendahara')->middleware(['auth', 'role:bendahara'])->group(function () {
    Route::controller(BendaharaController::class)->group(function () {
        Route::get('/home-bendahara', 'index')->name('bendahara.index');
        Route::get('/data-anggota-bendahara', [BendaharaController::class, 'dataAnggota'])->name('anggota.bendahara');
        Route::get('/approve-bendahara', 'indexanggota')->name('approve-bendahara');
        Route::get('/data-simpanan-sukarela', 'indexsimpanansukarela')->name('data.simpanan.sukarela.bendahara');
        Route::get('/data-simpanan-berjangka', 'indexsimpananberjangka')->name('data.simpanan.berjangka.bendahara');
        Route::get('/approve-pinjaman-emergency', 'bendaharaEmergency')->name('approve.bendahara.emergency');
        Route::get('/approve-pinjaman-angunan', 'bendaharaAngunan')->name('approve.bendahara.angunan');
        Route::post('/approve/{id}/{status}', 'updateStatus')->name('approve.update-status-bendahara');
        Route::get('/approve-pinjaman-non-angunan', 'bendaharaNonAngunan')->name('approve.bendahara.nonangunan');
        Route::get('/count-data-simpanan-sukarela/{status}', 'countDataRekeningSimpananSukarela');
        Route::get('/count-data-simpanan-berjangka/{status}', 'countDataRekeningSimpananBerjangka');
        Route::get('/count-data/{status}', 'countData');
        Route::get('/count-data/emergency/{status}', 'countDataEmergency');
        Route::get('/count-data/anggunan/{status}', 'countDataanggunan');
        Route::post('/update-status-simpanan/{id}/{status}', 'updateApprovalManagerSimpananSukarela')->name('status.simpanan.sukarela.bendahara');
        Route::post('/update-status-berjangka/{id}/{status}', 'updateApprovalManagerSimpananBerjangka')->name('status.simpanan.berjangka.bendahara');
        Route::post('/pinjaman/{id}/{status}', 'updateStatusPinjamanBendahara')->name('bendahara.pinjaman.status');
        Route::get('/data/filter', 'filter')->name('bendahara.data.filter');
        Route::get('/data/search', 'search')->name('bendahara.data.search');
        Route::delete('/data/delete/{id}', 'delete')->name('bendahara.data.delete');
        Route::get('/anggota/detail/{id}', 'getDetail')->name('bendahara.data.detail');
        Route::put('/anggota/update/{id}', 'update')->name('bendahara.anggota.update.data');
        Route::post('/anggota/reject/{id}', 'reject')->name('bendahara.anggota.reject');
        Route::get('/count-data-penarikan-sukarela/{status}', 'countDataRekeningPenarikanSukarela');
        Route::get('/count-data-penarikan-berjangka/{status}', 'countDataRekeningPenarikanBerjangka');
        Route::post('/update-status-penarikan/{id}/{status}', 'updateApprovalManagerPenarikanSukarela')->name('status.penarikan.sukarela.bendahara');
        Route::post('/update-status-penarikan-berjangka/{id}/{status}', 'updateApprovalManagerPenarikanBerjangka')->name('status.penarikan.berjangka.bendahara');
        Route::get('/penarikan/sukarela/approval', 'penarikanSukarelaApproval')->name('penarikan.sukarela.approval.bendahara');
        Route::get('/penarikan/berjangka/approval', 'penarikanBerjangkaApproval')->name('penarikan.berjangka.approval.bendahara');
    });
    Route::controller(HistoryTransactionController::class)->group(function () {
        Route::get('/check-status', 'index')->name('status.index');
        Route::get('/doku/order-status', 'create')->name('check-status.create');
        Route::post('/doku/order-status', 'store')->name('check-status.store');
    });

    // Route::resource('spk', PenilaianController::class);
    Route::controller(PenilaianController::class)->group(function () {
        Route::get('/spk/calculate/{id}', 'calculate')->name('spk.calculate');
    });
});

// General Simpanan Routes
Route::middleware(['auth'])->group(function () {
    Route::post('/simpanan-wajib', [SimpananWajibController::class, 'store'])->name('simpanan.wajib.store');
    Route::post('/simpanan-pokok', [SimpananPokokController::class, 'store'])->name('simpanan.pokok.store');
    Route::put('/simpanan/{id}/approve', [SimpananController::class, 'approveSimpanan'])->name('simpanan.approve');
    Route::put('/simpanan/{id}/reject', [SimpananController::class, 'rejectSimpanan'])->name('simpanan.reject');
});




// Group route untuk Simpanan Sukarela
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/simpanan-sukarela', [SimpananSukarelaController::class, 'index']); // Lihat semua simpanan sukarela
    Route::get('/simpanan-sukarela/{id}', [SimpananSukarelaController::class, 'show']); // Detail simpanan sSSukarela
    Route::post('/simpanan-sukarela/create', [SimpananSukarelaController::class, 'store'])->name('payment.store.sukarela'); // Buat simpanan sukarela baru
    Route::put('/simpanan-sukarela/{id}', [SimpananSukarelaController::class, 'update']); // Perbarui simpanan sukarela
    Route::delete('/simpanan-sukarela/{id}', [SimpananSukarelaController::class, 'destroy']); // Hapus simpanan sukarela
    Route::get('/rekening-sukarela/check', [SimpananSukarelaController::class, 'checkRekening'])->name('check-rekening');
    Route::get('/saldo-simpanan', [SimpananSukarelaController::class, 'calculateSaldo']);
    Route::post('/simpanan-sukarela/update-status-failed', [SimpananSukarelaController::class, 'updateStatusToFailed']);
    Route::any('/get-remaining-time', [SimpananSukarelaController::class, 'getRemainingTime']);
    Route::get('/simpanan-sukarela/menunggu-pembayaran', [SimpananSukarelaController::class, 'getMenungguPembayaran']);
    Route::get('/hasil-simpanan/sukarela', [SimpananSukarelaController::class, 'hasilSimpanan'])->name('hasil.simpanan.sukarela');
});

// Route untuk Simpanan Berjangka
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/simpanan-berjangka', [SimpananBerjangkaController::class, 'index']); // Lihat semua simpanan berjangka
    Route::get('/simpanan-berjangka/{id}', [SimpananBerjangkaController::class, 'show']); // Detail simpanan berjangka
    Route::post('/simpanan-berjangka/create', [SimpananBerjangkaController::class, 'store'])->name('payment.store'); // Buat simpanan berjangka baru
    Route::put('/simpanan-berjangka/{id}', [SimpananBerjangkaController::class, 'update']); // Perbarui simpanan berjangka
    Route::delete('/simpanan-berjangka/{id}', [SimpananBerjangkaController::class, 'destroy']); // Hapus simpanan berjangka
    Route::get('/rekening-berjangka/check', [SimpananBerjangkaController::class, 'checkRekening']);
    Route::get('/saldo-berjangka', [SimpananBerjangkaController::class, 'calculateSaldo']);
    Route::post('/simpanan-berjangka/update-status-failed', [SimpananBerjangkaController::class, 'updateStatusToFailed']);
    Route::any('/simpanan-berjangka/get-remaining-time', [SimpananBerjangkaController::class, 'getRemainingTime']);
    Route::get('/simpanan-berjangka/menunggu-pembayaran', [SimpananBerjangkaController::class, 'getMenungguPembayaran']);
    Route::get('/hasil-simpanan/berjangka', [SimpananBerjangkaController::class, 'hasilSimpanan'])->name('hasil.simpanan');
});

Route::middleware(['auth'])->group(function () {
    Route::post('/simpanan-pokok/create', [SimpananPokokController::class, 'requestVirtualAccountToDoku'])->name('payment.store.pokok'); // Buat simpanan sukarela baru
    Route::get('/hasil-simpanan/pokok', [SimpananPokokController::class, 'hasilSimpanan'])->name('hasil.simpanan.pokok');
    Route::get('/simpanan-pokok', [SimpananPokokController::class, 'simpananpokok'])->name('simpanan-pokok');
});
