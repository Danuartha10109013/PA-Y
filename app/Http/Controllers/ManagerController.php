<?php

namespace App\Http\Controllers;

use App\Models\Tenor;
use App\Models\Anggota;
use App\Mail\Mailkonfir;
use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use App\Mail\information_registrasi;
use App\Mail\RejectNotification;
use App\Models\PenarikanBerjangka;
use App\Models\PenarikanSukarela;
use App\Models\PengajuanPinjaman;
use App\Models\RekeningSimpananBerjangka;
use App\Models\RekeningSimpananSukarela;
use App\Models\SimpananBerjangka;
use App\Models\SimpananSukarela;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Mail;

class ManagerController extends Controller
{
    public function indexpinjaman()
    {
        $data = [
            'title' => 'Data Pinjaman Emergency',
            'tenors' => Tenor::get()->all(),
            'virtualAccounts' => VirtualAccount::get()->all(),
            'pinjamans' => PengajuanPinjaman::all()
        ];

        return view('pages.manager.pinjaman.index', $data);
    }

    public function dataAnggota()
    {
        $anggota = Anggota::orderBy('created_at', 'desc')->get();

        return view('pages.manager.data_anggota_manager', [
            'title' => 'Data Anggota',
            'anggota' => $anggota

        ]);
    }

    public function index()
    {
        // Mengambil semua data anggota dari database dan mengurutkan berdasarkan kolom created_at secara descending
        $anggota = Anggota::orderBy('created_at', 'desc')->get();

        // Mengirimkan data anggota ke view
        return view('pages.manager.approve_regis_manager', [
            'title' => 'Data Approve Registrasi',
            'anggota' => $anggota,
        ]);
    }


    public function penarikanSukarelaApproval()
    {
        // Ambil data dengan kondisi di mana kolom 'bank' tidak sama dengan 'Menunggu OTP'
        $data = PenarikanSukarela::where('bank', '!=', 'Menunggu OTP')
            ->orderBy('created_at', 'desc')
            ->get();

        // Log data yang akan ditampilkan
        Log::info('Data Approval Penarikan Sukarela:', ['data' => $data]);

        // Return ke view dengan data yang sudah difilter
        return view('pages.manager.penarikan.penarikan_sukarela_approval', [
            'title' => 'Data Approval Penarikan Sukarela',
            'data' => $data,
        ]);
    }


    public function penarikanBerjangkaApproval()
    {
        $data = PenarikanBerjangka::where('bank', '!=', 'Menunggu OTP')
            ->orderBy('created_at', 'desc')
            ->get();
        Log::info('Data Approval Simpanan Berjangka:', ['data' => $data]);
        return view('pages.manager.penarikan.penarikan_berjangka_approval', [
            'title' => 'Data Approval Simpanan Berjangka',
            'data' => $data,
        ]);
    }


    public function filter(Request $request)
    {
        // Ambil nilai status dari permintaan
        $status = $request->input('status');

        // Jika status adalah "all", ambil semua data
        if ($status === 'all') {
            $anggota = Anggota::orderBy('created_at', 'desc')->get();
        } else {
            // Filter berdasarkan status_manager
            $anggota = Anggota::where('status_manager', $status)
                ->orderBy('created_at', 'desc') // Mengurutkan berdasarkan kolom created_at secara descending
                ->get();
        }


        // Kembalikan view partial untuk memperbarui tabel
        return view('pages.manager.table_registrasi', compact('anggota'))->render();
    }


    public function search(Request $request)
    {
        $query = $request->input('query');

        // Cari berdasarkan nama, NIK, atau status_manager
        $anggota = Anggota::where('nama', 'LIKE', "%{$query}%")
            ->orWhere('nik', 'LIKE', "%{$query}%")
            ->orWhere('status_manager', 'LIKE', "%{$query}%")
            ->get();

        // Kembalikan partial view dengan hasil pencarian
        return view('pages.manager.table_registrasi', compact('anggota'))->render();
    }



    public function indexsimpanansukarela()
    {
        return view('pages.manager.simpanan.index', [
            'title' => 'Data Pengajuan Simpanan Sukarela',
            'simpananSukarelas' => SimpananSukarela::with('rekeningSimpananSukarela', 'user')->get(),
        ]);
    }


    public function indexsimpananberjangka()
    {
        return view('pages.manager.simpanan.index2', [
            'title' => 'Data Pengajuan Simpanan Sukarela',
            'simpananBerjangkas' => SimpananBerjangka::with('rekeningSimpananBerjangka', 'user')->get(),
        ]);
    }

    public function updateStatus($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status anggota berdasarkan parameter $status
            $anggota->status_manager = $status;
            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function updateStatusManager($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Update status dari manager
            $anggota->status_manager = $status;
            $anggota->save();

            // Cek status keseluruhan
            $this->FinalStatus($anggota);

            return response()->json(['message' => 'Status manager updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateStatusPinjaman($id, $status)
    {
        try {
            // Temukan anggota berdasarkan ID
            $anggota = PengajuanPinjaman::findOrFail($id);

            // Update status anggota berdasarkan parameter $status
            $anggota->status_manager = $status;
            $anggota->save();

            return response()->json(['message' => 'Status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update status!', 'error' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            // Cari data berdasarkan ID
            $anggota = Anggota::findOrFail($id);

            // Hapus data
            $anggota->delete();

            return response()->json(['message' => 'Data berhasil dihapus'], 200);
        } catch (ModelNotFoundException $e) {
            Log::error("Data dengan ID {$id} tidak ditemukan: " . $e->getMessage()); // Log jika data tidak ditemukan
            return response()->json(['message' => 'Data tidak ditemukan'], 404);
        } catch (\Exception $e) {
            Log::error("Error saat menghapus data dengan ID {$id}: " . $e->getMessage()); // Log jika ada error lain
            return response()->json(['message' => 'Terjadi kesalahan'], 500);
        }
    }


    public function getDetail($id)
    {
        $anggota = Anggota::findOrFail($id); // Cari anggota berdasarkan ID
        return response()->json($anggota); // Kembalikan data sebagai JSON
    }



    public function updateApprovalManagerSimpananSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananSukarela::findOrFail($id);

            // Update status approval manager
            $rekening->approval_manager = $status;

            if ($status === 'rejected') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->approval_bendahara = 'rejected';
                $rekening->approval_ketua = 'rejected';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }



    public function update(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Validasi data
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat_domisili' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'nik' => 'required|string|max:255',
            'email_kantor' => 'required|email|max:255',
            'no_handphone' => 'required|string|max:15',
        ]);

        // Perbarui data anggota
        $anggota->update($request->all());

        return response()->json(['message' => 'Data berhasil diperbarui'], 200);
    }


    public function reject(Request $request, $id)
    {
        $anggota = Anggota::findOrFail($id);

        // Mengubah status anggota menjadi 'Ditolak'
        $anggota->status_manager = 'Ditolak';
        $anggota->status_bendahara = 'Ditolak';
        $anggota->status = 'Ditolak';
        $anggota->status_ketua = 'Ditolak';
        $anggota->alasan_ditolak = $request->input('alasan_ditolak'); // Tetap bisa menyimpan alasan jika dikirim
        $anggota->save();

        // Mengubah status_pembayaran di tabel simpanan_pokok menjadi 'Gagal'
        $anggota->simpananPokok()->update(['status_pembayaran' => 'Gagal']);

        // Mengubah status_pembayaran di tabel simpanan_wajib menjadi 'Gagal'
        $anggota->simpananWajib()->update(['status_pembayaran' => 'Gagal']);

        // Kirim email penolakan
        Mail::to($anggota->email_kantor)->send(new RejectNotification($anggota));

        return response()->json(['message' => 'Anggota berhasil ditolak, status pembayaran diperbarui menjadi Gagal, dan email pemberitahuan telah dikirim.']);
    }


    public function updateApprovalManagerSimpananBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['approved', 'rejected', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = RekeningSimpananBerjangka::findOrFail($id);

            // Update status approval manager
            $rekening->approval_manager = $status;

            if ($status === 'rejected') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->approval_bendahara = 'rejected';
                $rekening->approval_ketua = 'rejected';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }


    // Fungsi lainnya tetap sama
    public function email($id)
    {
        $email = Anggota::where('id', $id)->first()->email_kantor;
        Mail::to($email)->send(new Mailkonfir($email));

        // dd($email);
        // Mail::to($email)->send(new information_($email));
    }

    public function homemanager()
    {
        return view('pages.manager.home_manager', [
            'title' => 'Dashboard Manager',
        ]);
    }

    public function countData($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = Anggota::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua atau manager
            $count = Anggota::where(function ($query) {
                $query->where('status_ketua', 'Diterima')
                    ->orWhere('status_manager', 'Diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua atau manager)
            $count = Anggota::whereNull('status_ketua')
                ->orWhereNull('status_manager')
                ->where(function ($query) {
                    $query->where('status_ketua', '!=', 'Diterima')
                        ->where('status_ketua', '!=', 'Ditolak')
                        ->orWhere('status_manager', '!=', 'Diterima')
                        ->orWhere('status_manager', '!=', 'Ditolak');
                })->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua atau manager
            $count = Anggota::where(function ($query) {
                $query->where('status_ketua', 'Ditolak')
                    ->orWhere('status_manager', 'Ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }

    public function countDataRekeningPenarikanSukarela($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = PenarikanSukarela::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = PenarikanSukarela::where(function ($query) {
                $query->where('status_ketua', 'diterima')
                    ->orWhere('status_manager', 'diterima')
                    ->orWhere('status_bendahara', 'diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = PenarikanSukarela::where('status_ketua', 'pending')
                ->orWhere('status_manager', 'pending')
                ->orWhere('status_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = PenarikanSukarela::where(function ($query) {
                $query->where('status_ketua', 'ditolak')
                    ->orWhere('status_manager', 'ditolak')
                    ->orWhere('status_bendahara', 'ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function countDataRekeningPenarikanBerjangka($status)
    {
        if ($status == 'all') {
            // Total semua data
            $count = PenarikanBerjangka::count();
        } elseif ($status == 'diterima') {
            // Data yang diterima oleh ketua, manager, atau bendahara
            $count = PenarikanBerjangka::where(function ($query) {
                $query->where('status_ketua', 'diterima')
                    ->orWhere('status_manager', 'diterima')
                    ->orWhere('status_bendahara', 'diterima');
            })->count();
        } elseif ($status == 'pengajuan') {
            // Data yang masih dalam proses (belum diterima/ditolak oleh ketua, manager, atau bendahara)
            $count = PenarikanBerjangka::where('status_ketua', 'pending')
                ->orWhere('status_manager', 'pending')
                ->orWhere('status_bendahara', 'pending')
                ->count();
        } elseif ($status == 'ditolak') {
            // Data yang ditolak oleh ketua, manager, atau bendahara
            $count = PenarikanBerjangka::where(function ($query) {
                $query->where('status_ketua', 'ditolak')
                    ->orWhere('status_manager', 'ditolak')
                    ->orWhere('status_bendahara', 'ditolak');
            })->count();
        }

        // Kembalikan hasil dalam format JSON
        return response()->json(['count' => $count]);
    }


    public function updateApprovalManagerPenarikanBerjangka($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanBerjangka::findOrFail($id);

            // Update status approval manager
            $rekening->status_manager = $status;

            if ($status === 'ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->status_bendahara = 'ditolak';
                $rekening->status_ketua = 'ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }


    public function updateApprovalManagerPenarikanSukarela($id, $status)
    {
        try {
            // Validasi status
            if (!in_array($status, ['diterima', 'ditolak', 'pending'])) {
                return response()->json(['message' => 'Invalid status provided!'], 400);
            }

            // Temukan data berdasarkan ID
            $rekening = PenarikanSukarela::findOrFail($id);

            // Update status approval manager
            $rekening->status_manager = $status;

            if ($status === 'ditolak') {
                // Jika status ditolak oleh manager, maka status bendahara dan ketua juga menjadi Ditolak
                $rekening->status_bendahara = 'ditolak';
                $rekening->status_ketua = 'ditolak';
            }


            $rekening->save();

            return response()->json(['message' => 'Approval Manager status updated successfully!'], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update Approval Manager status!', 'error' => $e->getMessage()], 500);
        }
    }
};
