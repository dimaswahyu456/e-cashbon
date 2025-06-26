<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $co   = Auth::user()->co_id;
        $vend = Auth::user()->vend_id;
        $tahun= $request->get('tahun', Carbon::now()->year);

        $totalSupplier = DB::connection('firebird')->table('VENDOR')->count();

        // 1 query ringkasan
        $summary = DB::connection('firebird')->selectOne("
            SELECT
                SUM(NOMINAL) AS total_cashbon,
                SUM(NOMINAL_SETTLAMENT) AS total_lpj,
                SUM(OUTSTANDING) AS total_outstanding
            FROM (
                SELECT * FROM CASHBON_LPJ_VEND_S(?,?)
            ) AS cb
            WHERE EXTRACT(YEAR FROM DOC_DATE) = ?
        ", [$vend, $co, $tahun]);

        // 1 query per bulan agregasi
        $monthly = DB::connection('firebird')->select("
            SELECT 
                EXTRACT(MONTH FROM DOC_DATE) AS bulan,
                SUM(NOMINAL) AS sum_cashbon,
                SUM(OUTSTANDING) AS sum_outstanding
            FROM CASHBON_LPJ_VEND_S(?,?)
            WHERE EXTRACT(YEAR FROM DOC_DATE) = ?
            GROUP BY EXTRACT(MONTH FROM DOC_DATE)
        ", [$vend, $co, $tahun]);
        // dd($monthly);

        $grafikCashbon = array_fill(0, 12, 0);
        $grafikLPJ = array_fill(0, 12, 0);
        foreach($monthly as $row) {
            $i = intval($row->BULAN) - 1;
            $grafikCashbon[$i] = (float) $row->SUM_CASHBON;
            $grafikLPJ[$i]     = (float) $row->SUM_OUTSTANDING;
        }

        return view('dashboard', [
            'totalSupplier'       => $totalSupplier,
            'totalCashbonTahunIni'=> (float) $summary->TOTAL_CASHBON,
            'totalSudahLPJ'       => (float) $summary->TOTAL_LPJ,
            'totalBelumLPJThn'    => (float) $summary->TOTAL_OUTSTANDING,
            'grafikCashbon'       => $grafikCashbon,
            'grafikLPJ'           => $grafikLPJ,
            'tahun'               => $tahun,
        ]);
    }
}
