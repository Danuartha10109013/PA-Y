<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use App\Models\Penilaian;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\PengajuanPinjaman;
use Illuminate\Support\Facades\Log;

class PenilaianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() {}


    public function calculate(Request $request, $id)
    {
        try {
            // Step 1: Define criteria, weights, and type
            $weights = [
                'gaji' => 0.5,
                'amount' => 0.3,
                'jangka_waktu' => 0.2,
            ];

            $criteriaType = [
                'gaji' => 'benefit',
                'amount' => 'cost',
                'jangka_waktu' => 'cost',
            ];

            // Step 2: Fetch data berdasarkan ID
            $data = PengajuanPinjaman::select('id', 'gaji', 'amount', 'jangka_waktu')
                ->where('id', $id)
                ->first();

            if (!$data) {
                return view('errors', [
                    'message' => "Data pengajuan pinjaman dengan ID $id tidak ditemukan.",
                ]);
            }

            // Step 3: Define rating scale
            $rating = function ($value, $type) {
                if ($type === 'gaji') {
                    return $value > 10000000 ? 5 : ($value > 5000000 ? 4 : ($value > 3000000 ? 3 : ($value > 2000000 ? 2 : 1)));
                } elseif ($type === 'amount') {
                    return $value <= 10000000 ? 5 : ($value <= 25000000 ? 4 : ($value <= 50000000 ? 3 : ($value <= 75000000 ? 2 : 1)));
                } elseif ($type === 'jangka_waktu') {
                    return $value <= 6 ? 5 : ($value <= 12 ? 4 : ($value <= 24 ? 3 : ($value <= 36 ? 2 : 1)));
                }
            };

            // Step 4: Build decision matrix untuk pengajuan tertentu
            $decisionMatrix = [
                'gaji' => $rating($data->gaji, 'gaji'),
                'amount' => $rating($data->amount, 'amount'),
                'jangka_waktu' => $rating($data->jangka_waktu, 'jangka_waktu'),
            ];

            // Step 5: Normalize matrix
            $normalizedMatrix = [];
            foreach (['gaji', 'amount', 'jangka_waktu'] as $key) {
                $max = $decisionMatrix[$key]; // Untuk kriteria benefit
                $min = $decisionMatrix[$key]; // Untuk kriteria cost

                if ($criteriaType[$key] === 'benefit') {
                    $normalizedMatrix[$key] = $decisionMatrix[$key] / max($max, 1);
                } else {
                    $normalizedMatrix[$key] = min($min, 1) / $decisionMatrix[$key];
                }
            }

            // Step 6: Calculate score
            $score = ($weights['gaji'] * $normalizedMatrix['gaji']) +
                    ($weights['amount'] * $normalizedMatrix['amount']) +
                    ($weights['jangka_waktu'] * $normalizedMatrix['jangka_waktu']);

            // Step 7: Determine level
            $level = $score >= 0.8 ? 'Level 1 (Bagus)' :
                    ($score >= 0.5 ? 'Level 2 (Cukup Bagus)' : 'Level 3 (Buruk)');

            // Step 8: Simpan hasil SPK ke database
            Penilaian::create([
                'uuid' => Str::uuid(),
                'pengajuan_pinjamans_id' => $data->id,
                'score' => $score,
                'level' => $level,
            ]);
            

            // Step 9: Return hasil ke view
            return view('pages.spk.index', [
                'title' => 'Hasil SPK',
                'spks' => Penilaian::with('user')->get(),
            ]);
            
        } catch (\Exception $e) {
            return view('errors', [
                'message' => $e->getMessage(),
            ]);
        }
    }

    
}
