<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PinjamanAngunan;
use App\Models\PinjamanEmergency;
use App\Models\PinjamanNonAngunan;

class TotalController extends Controller
{
    public function getTotalPinjaman()
    {
        $emergencyLoans = PinjamanEmergency::where('status', 'success')->sum('amount');
        $regularLoans = PinjamanAngunan::where('status', 'success')->sum('amount') +
            PinjamanNonAngunan::where('status', 'success')->sum('amount');
        $totalLoans = $emergencyLoans + $regularLoans;
    }
}
