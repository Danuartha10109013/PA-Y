<?php

namespace App\Http\Controllers;

use App\Models\PenarikanBerjangka;
use App\Models\SimpananBerjangka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PenarikanBerjangkaController extends Controller
{
    // Fungsi untuk menghasilkan nomor penarikan unik
    private function generateNomorSimpanan()
    {
        // Format nomor penarikan: PS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5)); // 5 karakter unik
        return "PS-{$date}-{$random}";
    }

    public function sendOTP(Request $request)
    {
        // Pastikan user login
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Validasi input metode pengiriman
        $request->validate([
            'method' => 'required|in:email,whatsapp,sms', // Menambahkan opsi SMS
        ]);

        // Pastikan data yang dibutuhkan tersedia
        if ($request->method === 'whatsapp' && empty($user->anggota->no_handphone)) {
            return response()->json(['message' => 'No Handphone is required for WhatsApp OTP.'], 400);
        }
        if ($request->method === 'email' && empty($user->email)) {
            return response()->json(['message' => 'Email is required for Email OTP.'], 400);
        }
        if ($request->method === 'sms' && empty($user->anggota->no_handphone)) {
            return response()->json(['message' => 'No Handphone is required for SMS OTP.'], 400);
        }

        // Generate OTP dan nomor penarikan
        $otpCode = random_int(100000, 999999);
        $otpExpiry = now()->addMinutes(5); // OTP valid selama 5 menit
        $noPenarikan = $this->generateNomorSimpanan();

        // Simpan OTP ke database
        $penarikan = PenarikanBerjangka::create([
            'user_id' => $user->id,
            'no_penarikan' => $this->generateNomorSimpanan(),
            'bank' => $request->input('bank', 'Menunggu OTP'), // Isi default jika tidak diberikan
            'nominal' => 0, // Nilai default // Isi default jika tidak diberikan
            'otp_code' => $otpCode,
            'otp_expired_at' => $otpExpiry,
            'status_manager' => 'pending',
            'status_ketua' => 'pending',
        ]);


        // Kirim OTP sesuai metode yang dipilih
        if ($request->method === 'whatsapp') {
            $this->sendWhatsApp($user->anggota->no_handphone, $otpCode);
        } elseif ($request->method === 'email') {
            Mail::raw("Your OTP code is: $otpCode", function ($message) use ($user) {
                $message->to($user->email)
                        ->subject('Your OTP Code');
            });
        } elseif ($request->method === 'sms') {
            $this->sendSMS($user->anggota->no_handphone, $otpCode);
        }

        return response()->json([
            'message' => 'OTP sent successfully via ' . $request->method . '.',
            'data' => $penarikan,
        ]);
    }

public function verifyOTP(Request $request)
{
    // Pastikan user login
    $user = Auth::user();

    if (!$user) {
        Log::error('User not authenticated.');
        return response()->json(['message' => 'User not authenticated.'], 401);
    }

    Log::info('User authenticated', ['user_id' => $user->id]);

    try {
        // Validasi input
        $request->validate([
            'otp_code' => 'required',
            'bank' => 'required|string',
            'nominal' => 'required|numeric',
        ]);

        Log::info('Validation passed', $request->all());

        // Hitung saldo terkini
        $totalSimpanan = SimpananBerjangka::where('user_id', $user->id)
            ->where('status_payment', 'Simpanan Sukses')
            ->sum('nominal');

        $totalPenarikan = PenarikanBerjangka::where('user_id', $user->id)
            ->where('status_ketua', 'diterima')
            ->sum('nominal');

        $saldo = $totalSimpanan - $totalPenarikan;

        Log::info('Saldo calculated', [
            'user_id' => $user->id,
            'total_simpanan' => $totalSimpanan,
            'total_penarikan' => $totalPenarikan,
            'saldo' => $saldo,
        ]);

        // Periksa apakah nominal penarikan melebihi saldo
        if ($request->nominal > $saldo) {
            Log::error('Nominal exceeds saldo', [
                'user_id' => $user->id,
                'nominal' => $request->nominal,
                'saldo' => $saldo,
            ]);
            return response()->json([
                'message' => 'Saldo Anda tidak cukup.'], 400);
        }

        // Cari data penarikan berdasarkan OTP dan user ID
        $penarikan = PenarikanBerjangka::where('user_id', $user->id)
            ->where('otp_code', $request->otp_code)
            ->first();

        if (!$penarikan) {
            Log::error('Invalid OTP', [
                'user_id' => $user->id,
                'otp_code' => $request->otp_code,
            ]);
            return response()->json(['message' => 'Invalid OTP.'], 400);
        }

        // Periksa apakah OTP sudah kadaluarsa
        if (now()->greaterThan($penarikan->otp_expired_at)) {
            Log::error('OTP has expired', [
                'user_id' => $user->id,
                'penarikan_id' => $penarikan->id,
                'otp_expired_at' => $penarikan->otp_expired_at,
            ]);
            return response()->json(['message' => 'Kode OTP Sudah Kadaluwarsa.'], 202);
        }

        Log::info('OTP verified', ['penarikan_id' => $penarikan->id]);

        // Jika belum kadaluarsa, perbarui data
        $penarikan->update([
            'bank' => $request->bank,
            'nominal' => $request->nominal,
        ]);

        Log::info('Penarikan updated', $penarikan->toArray());

        return response()->json([
            'message' => 'Data saved successfully after OTP verification.',
            'data' => $penarikan,
        ]);
    } catch (\Exception $e) {
        // Log error detail
        Log::error('Error in verifyOTP', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
        return response()->json(['message' => 'Internal server error.'], 500);
    }
}




    private function sendWhatsApp($phone, $otpCode)
    {
        if (!$phone) {
            return false; // Jika nomor handphone tidak tersedia
        }

        $apiUrl = 'https://api.whatsapp.com/send'; // Ganti dengan API WhatsApp yang sesuai
        $response = Http::post($apiUrl, [
            'phone' => $phone,
            'message' => "Your OTP code is: $otpCode",
        ]);

        return $response->successful();
    }

    private function sendSMS($phone, $otpCode)
    {
        if (!$phone) {
            return false; // Jika nomor handphone tidak tersedia
        }

        $apiUrl = 'https://api.smsprovider.com/send'; // Ganti dengan API SMS yang sesuai
        $response = Http::post($apiUrl, [
            'phone' => $phone,
            'message' => "Your OTP code is: $otpCode",
        ]);

        return $response->successful();
    }


    public function getContactInfo(Request $request)
    {
        $user = Auth::user(); // Ambil user yang login

        if (!$user) {
            return response()->json(['message' => 'User not authenticated.'], 401);
        }

        // Ambil data nomor HP dari tabel anggota
        $noHandphone = $user->anggota->no_handphone ?? null;

        Log::info('User Data:', [
            'email' => $user->email,
            'whatsapp' => $noHandphone,
            'sms' => $noHandphone,
        ]);

        return response()->json([
            'email' => $user->email ?? 'Email tidak ditemukan',
            'whatsapp' => $noHandphone ?? 'WhatsApp tidak ditemukan',
            'sms' => $noHandphone ?? 'SMS tidak ditemukan',
        ]);
    }


      // Fungsi untuk melihat semua simpanan sukarela milik user yang login
public function index()
{
    // Mendapatkan user yang sedang login
    $user = auth()->user();

    if (!$user) {
        // Jika user belum login, berikan respon error
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    Log::info('Menampilkan semua simpanan sukarela milik user dengan ID: ' . $user->id);

    // Mengambil data simpanan sukarela milik user yang login
    $simpananSukarela = PenarikanBerjangka::with('user')
        ->where('user_id', $user->id)
        ->get();

    return response()->json($simpananSukarela);
}





}
