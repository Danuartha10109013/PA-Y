<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use App\Models\SimpananPokok;
use App\Models\SimpananWajib;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showIuran()
    {
        $data = [
            'title' => 'Iuran Registrasi',
        ];

        return view('auth.iuran', $data);
    }

    public function showRegistrationForm()
    {
        return view('auth.register', [
            'title' => 'Registrasi',
        ]);
    }

    public function store(Request $request)
    {
        // Validasi data form
        $validated = $request->validate(
            [
                'email_kantor' => 'required|email',
                'nama' => 'required|string|max:255',
                'alamat_domisili' => 'required|string',
                'tempat_lahir' => 'required|string',
                'tgl_lahir' => 'required|date',
                'alamat_ktp' => 'required|string',
                'nik' => 'required|digits:16',
                'no_handphone' => 'required|string|max:255|regex:/^8[0-9]{0,254}$/',
            ],
            [
                'nama.required' => 'Nama wajib diisi',
                'alamat_domisili.required' => 'Alamat domisili wajib diisi',
                'tempat_lahir.required' => 'Tempat Lahir wajib diisi',
                'tgl_lahir.required' => 'Tanggal Lahir wajib diisi',
                'tgl_lahir.date' => 'Tanggal Lahir harus berupa tanggal yang valid',
                'alamat_ktp.required' => 'Alamat KTP wajib diisi',
                'nik.required' => 'NIK wajib diisi',
                'email_kantor.required' => 'Email wajib diisi',
                'email_kantor.email' => 'Silahkan masukkan email yang valid',
                'email_kantor.unique' => 'Email sudah ada, silahkan pilih email lain',
                'no_handphone.required' => 'No handphone wajib diisi',
                'no_handphone.regex' => 'Format no handphone tidak valid. Contoh: 82258756166.',
            ]


        );

        // Jika validasi sukses, teruskan data ke halaman iuran
        return view('auth.iuran', ['data' => $validated]);
    }



    public function register(Request $request)
    {
        try {
            // Log request yang diterima untuk memeriksa data yang masuk
            Log::info('Data received for registration:', $request->all());


            // Validasi data yang masuk
            $validated = $request->validate(
                [
                    'nama' => 'required|string|max:255',
                    'alamat_domisili' => 'required|string|max:255',
                    'tempat_lahir' => 'required|string|max:255',
                    'tgl_lahir' => 'required|date',
                    'alamat_ktp' => 'required|string|max:255',
                    'nik' => 'required|string|max:255',
                    'email_kantor' => 'required|email|string|max:255|unique:anggota',
                    'no_handphone' => 'required|string|max:255|regex:/^8[0-9]{0,254}$/',
                    'simpanan_pokok' => 'required|numeric|min:1000000',
                    'simpanan_wajib' => 'required|numeric|min:50000',
                    'metode_pembayaran' => 'required|string|max:255',
                ],
                [
                    'nama.required' => 'Nama wajib diisi',
                    'alamat_domisili.required' => 'Alamat domisili wajib diisi',
                    'tempat_lahir.required' => 'Tempat Lahir wajib diisi',
                    'tgl_lahir.required' => 'Tanggal Lahir wajib diisi',
                    'tgl_lahir.date' => 'Tanggal Lahir harus berupa tanggal yang valid',
                    'alamat_ktp.required' => 'Alamat KTP wajib diisi',
                    'nik.required' => 'NIK wajib diisi',
                    'email_kantor.required' => 'Email wajib diisi',
                    'email_kantor.email' => 'Silahkan masukkan email yang valid',
                    'email_kantor.unique' => 'Email sudah ada, silahkan pilih email lain',
                    'no_handphone.required' => 'No handphone wajib diisi dan format 82258756166',
                ]
            );

            // Log validasi data untuk memastikan data sudah benar
            Log::info('Validated data:', $validated);

            // Jika validasi lolos, data anggota disimpan ke dalam database
            $anggota = Anggota::create([
                'nama' => $request->nama,
                'alamat_domisili' => $request->alamat_domisili,
                'tempat_lahir' => $request->tempat_lahir,
                'tgl_lahir' => $request->tgl_lahir,
                'alamat_ktp' => $request->alamat_ktp,
                'nik' => $request->nik,
                'email_kantor' => $request->email_kantor,
                'no_handphone' => '0' . ltrim($request->no_handphone, '0'),
                'password' => Hash::make($request->password),
            ]);

            // Buat data simpanan_wajib untuk anggota yang baru terdaftar
            $simpananWajib = SimpananWajib::create([
                'no_simpanan_wajib' => $this->generateNomorSimpananWajib(),
                'nominal' => $request->simpanan_wajib,
                'metode_pembayaran' => 'Potongan Gaji Otomatis',
                'status_pembayaran' => 'pending', // Set default status pembayaran
                'anggota_id' => $anggota->id, // Relasi ke anggota yang baru dibuat
            ]);


            $simpananPokok = SimpananPokok::create([
                'no_simpanan_pokok' => $this->generateNomorSimpanan(),
                'nominal' => $request->simpanan_pokok,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status_pembayaran' => 'pending', // Set default status pembayaran
                'anggota_id' => $anggota->id, // Relasi ke anggota yang baru dibuat
            ]);

            // Log data yang berhasil disimpan
            Log::info('Anggota and Simpanan Wajib successfully registered:', [
                'anggota' => $anggota->toArray(),
                'simpanan_wajib' => $simpananWajib->toArray(),
                'simpanan_pokok' => $simpananPokok->toArray(),

            ]);

            return redirect()->back()->with('success', 'Anggota dan simpanan wajib berhasil didaftarkan.');
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Registration failed: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all()
            ]);

            return back()->withErrors(['msg' => 'Terjadi kesalahan, silahkan coba lagi.']);
        }
    }

    public function index()
    {

        return view('auth.login', [
            'title' => 'Login',
        ]);
    }

    public function validasilogin(Request $request)
    {
        $credentials = $request->validate(
            [
                'email' => 'required',
                'password' => 'required'
            ],

            [
                'email.required' => 'Email Wajib Di Isi',
                'password.required' => 'Password Wajib Di Isi',
            ]
        );

        // dd($credentials);

        if (Auth::attempt($credentials)) {
            $userRole = Auth::user()->roles;

            switch ($userRole) {
                case 'anggota':
                    return redirect()->route('home-anggota')->with('success', 'Anda Berhasil Login!');
                case 'manager':
                    return redirect()->route('home.manager')->with('success', 'Anda Berhasil Login!');
                case 'ketua':
                    return redirect()->route('home-ketua')->with('success', 'Anda Berhasil Login!');
                case 'admin':
                    return redirect()->route('home-admin')->with('success', 'Anda Berhasil Login!');
                case 'bendahara':
                    return redirect()->route('bendahara.index')->with('success', 'Anda Berhasil Login!');
                default:
                    Auth::logout(); // Untuk keamanan jika role tidak sesuai
                    return redirect()->back()->withErrors(['Role pengguna tidak valid.'])->withInput();
            }
        } else {
            return redirect()->back()->withErrors(['username dan password yang dimasukan tidak sesuai'])->withInput();
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
    // Fungsi untuk menghasilkan nomor simpanan unik
    private function generateNomorSimpanan()
    {
        // Format nomor simpanan: SS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "SP-{$date}-{$random}";
    }
    // Fungsi untuk menghasilkan nomor simpanan unik
    private function generateNomorSimpananWajib()
    {
        // Format nomor simpanan: SS-YYYYMMDD-RANDOM
        $date = now()->format('Ymd');
        $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
        return "SW-{$date}-{$random}";
    }
}
