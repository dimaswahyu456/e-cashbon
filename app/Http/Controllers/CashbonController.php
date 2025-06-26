<?php

namespace App\Http\Controllers;

use App\Models\Cashbon;
use App\Models\Approved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\SaleType;
use JasperPHP\JasperPHP;

class CashbonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $co_id = Auth::user()->co_id;
        $vend_id = Auth::user()->vend_id;
        $res_cashbon = DB::connection('firebird')->select("
            SELECT * FROM CASHBON_LPJ_VEND_S(?,?) WHERE outstanding > 0 order by timeedit DESC
        ", [$vend_id, $co_id]);

        $res_approved = Approved::all();

        return view('cashbon.list-cashbon', compact('res_cashbon','res_approved'));
    }
    
    public function show($id)
    {
        $co_id = Auth::user()->co_id;
        $vend_id = Auth::user()->vend_id;
        $id = str_replace('_', '/', $id);
        $res_find = DB::connection('firebird')->select("
        SELECT * 
        FROM CASHBON_LPJ_VEND_S(?,?) 
        WHERE doc_id = ?
        ", [$vend_id, $co_id, $id]);
        // dd($res_find);        
        $find = $res_find[0];                

        $res_detail = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->get();   
        // dd($total_lpj);    
            
        $res_approved = DB::connection('firebird')->select("
            SELECT
                STATUS_APPROVED,
                TOTAL,
                APPROVED_DATE
            FROM cashbon_lpj
            WHERE co_id = ? AND doc_id = ?
        ", [$co_id, $id]);

        $statuses = collect($res_approved)->pluck('STATUS_APPROVED')->toArray();
        $hasData = count($statuses) > 0;
        $allApproved = $hasData && collect($statuses)->every(fn($s) => $s == '1');
        $hasPending = $hasData && collect($statuses)->contains(fn($s) => $s == '0');
        $latestApprovedDate = collect($res_approved)
            ->pluck('APPROVED_DATE')
            ->filter()
            ->sortDesc()
            ->first();
        // dd($latestApprovedDate);

        $total_lpj = collect($res_approved)->sum('TOTAL');
        
        $belum_lpj = $find->NOMINAL - $total_lpj;
        $kelebihan = $total_lpj - $find->NOMINAL;
        if ($kelebihan < 0){
            $kelebihan = 0;
        } else if($belum_lpj < 0){
            $belum_lpj = 0;
        }
                
        $type = DB::connection('firebird')
            ->table("LPJ_TYPE")
            ->where("STATUS", '1')
            ->get();

        // DB::connection->select('select * from cashbon_detail where id_cashbon= ?', [$id]);

        return view('cashbon.show-cashbon', [
                'find' => $find,
                'res_detail' => $res_detail,
                'belum_lpj' => $belum_lpj,
                'total_lpj' => $total_lpj,
                'kelebihan' => $kelebihan,
                'allApproved' => $allApproved,
                'hasPending' => $hasPending,
                'latestApprovedDate' => $latestApprovedDate,
                'type' => $type,
                'id_cashbon' => $id
            ]);
    }
    
    public function listDone()
    {
        $co_id = Auth::user()->co_id;
        $vend_id = Auth::user()->vend_id;
        $res_done = DB::connection('firebird')
            ->table("CASHBON_LPJ as a")
            ->select(
                'a.LPJ_ID',
                'a.DOC_ID',
                'a.DOC_DATE',
                'a.DOC_TYPE',
                'a.FOR_DOC_ID',
                'a.TOTAL',
                'a.VEND_ID',
                'a.STATUS_APPROVED',
                'b.VEND_NAME as nama_vendor',
                'a.TIMEEDIT',
            )
            ->leftJoin('VENDOR as b', 'a.VEND_ID', '=', 'b.VEND_ID')
            ->whereNotNull('FOR_DOC_ID')
            // ->whereNull('FOR_DOC_ID')
            ->orderBy('TIMEEDIT','DESC')
            ->get()
            ->groupBy('DOC_ID');
        // dd($res_done);

        return view('cashbon.list-done', compact('res_done'));
    }

    public function showDone($id)
    {
        $co_id = Auth::user()->co_id;
        $vend_id = Auth::user()->vend_id;
        $id = str_replace('_', '/', $id);
        $res_find = DB::connection('firebird')->select("
        SELECT * 
        FROM CASHBON_LPJ_VEND_S(?,?) 
        WHERE doc_id = ?
        ", [$vend_id, $co_id, $id]);
        // dd($res_find);        
        $find = $res_find[0];                

        $res_detail = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->where('STATUS_APPROVED', '1')
            ->get();   
        // dd($total_lpj);    
            
        $res_approved = DB::connection('firebird')->select("
            SELECT
                STATUS_APPROVED,
                TOTAL,
                APPROVED_DATE
            FROM cashbon_lpj
            WHERE co_id = ? AND doc_id = ?
        ", [$co_id, $id]);

        $statuses = collect($res_approved)->pluck('STATUS_APPROVED')->toArray();
        $hasData = count($statuses) > 0;
        $allApproved = $hasData && collect($statuses)->every(fn($s) => $s == '1');
        $hasPending = $hasData && collect($statuses)->contains(fn($s) => $s == '0');
        $latestApprovedDate = collect($res_approved)
            ->pluck('APPROVED_DATE')
            ->filter()
            ->sortDesc()
            ->first();
        // dd($latestApprovedDate);

        $total_lpj = collect($res_approved)->sum('TOTAL');
        
        $belum_lpj = $find->NOMINAL - $total_lpj;
        $kelebihan = $total_lpj - $find->NOMINAL;
        if ($kelebihan < 0){
            $kelebihan = 0;
        } else if($belum_lpj < 0){
            $belum_lpj = 0;
        }
                
        $type = DB::connection('firebird')
            ->table("LPJ_TYPE")
            ->where("STATUS", '1')
            ->get();

        // DB::connection->select('select * from cashbon_detail where id_cashbon= ?', [$id]);

        return view('cashbon.show-done', [
                'find' => $find,
                'res_detail' => $res_detail,
                'belum_lpj' => $belum_lpj,
                'total_lpj' => $total_lpj,
                'kelebihan' => $kelebihan,
                'allApproved' => $allApproved,
                'hasPending' => $hasPending,
                'latestApprovedDate' => $latestApprovedDate,
                'type' => $type,
                'id_cashbon' => $id
            ]);
    }

    public function printLpjReport($doc_id)
    {
        $jasper = new JasperPHP;

        $input = storage_path('app/reports/lpj_approval_form_report.jasper');
        $output = storage_path('app/reports/output/lpj_' . $doc_id . '_' . time());

        $jasper->process(
            $input,
            $output,
            [
                'format' => 'pdf',
                'params' => [
                    'DOC_ID' => $doc_id, // sesuaikan dengan parameter di jasper
                ],
                'db_connection' => [
                    'driver' => 'firebird',
                    'username' => 'SYSDBA',
                    'password' => 'masterkey',
                    'host' => 'divisitic.saraswanti.info',
                    'database' => '/var/db/sig.fdb',
                    'port' => '3050'
                ]
            ]
        )->execute();

        return response()->file($output . '.pdf');
    }

}
