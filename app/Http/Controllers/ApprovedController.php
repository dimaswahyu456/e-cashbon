<?php

namespace App\Http\Controllers;

use App\Models\Approved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApprovedController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $res_cashbon = DB::connection('firebird')
            ->table("CASHBON_LPJ as a")
            ->select(
                'a.LPJ_ID',
                'a.DOC_ID',
                'a.DOC_DATE',
                'a.DOC_TYPE',
                'a.TOTAL',
                'a.VEND_ID',
                'a.STATUS_APPROVED',
                'b.VEND_NAME as nama_vendor'
            )
            ->leftJoin('VENDOR as b', 'a.VEND_ID', '=', 'b.VEND_ID')
            ->where('a.STATUS_APPROVED', '0')
            ->get()
            ->groupBy('DOC_ID');    
        // dd($res_cashbon);

        $docIds = $res_cashbon->keys();
        $res_approved = Approved::whereIn('doc_id', $docIds)
            ->where('status', 'tolak')
            ->get()
            ->keyBy('doc_id');

        // dd($res_cashbon);
        return view('approved.list-approved', compact('res_cashbon','res_approved'));
    }

    public function create()
    {
        return view('approved.add-approved');
    }

    public function store(Request $request)
    {
        $docId = str_replace('_', '/', $request->id_cashbon);

        $existing = Approved::where('doc_id', $docId)->first();

        if ($existing) {
            $existing->update([
                'name_approved' => Auth::user()->fname,
                'role_id' => Auth::user()->role_id,
                'notes' => $request->notes,
                'status' => $request->status,
                'approved_date' => now(),
                'updated_at' => now(),
            ]);
        } else {
            Approved::create([
                'doc_id' => $docId,
                'name_approved' => Auth::user()->fname,
                'role_id' => Auth::user()->role_id,
                'notes' => $request->notes,
                'status' => $request->status,
                'approved_date' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        DB::connection('firebird')->update("
            UPDATE CASHBON_LPJ
            SET STATUS_APPROVED = ?
            WHERE DOC_ID = ?
        ", [
            $request->status,
            $docId
        ]);

        return redirect()
            ->route('approved.show', ['id' => str_replace('/', '_', $request->id_cashbon)]);
    }

    public function show($id)
    {
        $data = Approved::find($id);
        $co_id = Auth::user()->co_id;
        $id_cashbon = $id;
        $id = str_replace('_', '/', $id);
        
        $cashbon = DB::connection('firebird')->select("
            SELECT * 
            FROM cashbon_settlament_s(?) 
            WHERE co_id = ? AND doc_id = ?
            ", [$co_id, $co_id, $id]);
        $find = $cashbon[0]; 
        $res_detail = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->get();

        $total_lpj = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->sum('TOTAL');
        
        $belum_lpj = $find->NOMINAL - $total_lpj;
        $kelebihan = $total_lpj - $find->NOMINAL;
        if ($kelebihan < 0){
            $kelebihan = 0;
        } else if($belum_lpj < 0){
            $belum_lpj = 0;
        }

        $res_approved = Approved::select(
            'approved.id',
            'approved.doc_id',
            'approved.name_approved',
            'approved.role_id',
            'role.role_name',
            'approved.notes',
            'approved.status',
            'approved.approved_date',
        )
            ->leftJoin('role', 'approved.role_id', '=', 'role.role_id')
            ->where('doc_id', $id)

            // ->where('status', 'tolak')
            ->get();
        
        return view('approved.show-approved', compact('data', 'find', 'res_detail', 'belum_lpj', 'kelebihan', 'total_lpj', 'res_approved', 'id_cashbon'));
    }
    
    public function edit($id)
    {
        $data = Approved::find($id);
        return view('approved.show-approved', compact('data'));
    }

    public function update(Request $request, $id)
    {
        dd($request->id);
        $input = $request->id;
        $data = Approved::find($input);
        $data->name_approved = Auth::user()->fname;
        $data->role_id = Auth::user()->role_id;
        $data->notes = $request->notes;
        $data->approved_date = now();
        $data->updated_at = now();
        $data->update();

        return redirect()
            ->route('approved.list');
    }

    public function destroy($id)
    {
        Approved::destroy($id);
        return redirect()->back();
    }    
    
    public function cashbonAcc()
    {
        $res_cashbon = DB::connection('firebird')
            ->table("CASHBON_LPJ as a")
            ->select(
                'a.LPJ_ID',
                'a.DOC_ID',
                'a.DOC_DATE',
                'a.DOC_TYPE',
                'a.TOTAL',
                'a.VEND_ID',
                'a.STATUS_APPROVED',
                'b.VEND_NAME as nama_vendor'
            )
            ->leftJoin('VENDOR as b', 'a.VEND_ID', '=', 'b.VEND_ID')
            ->where('STATUS_APPROVED', '1')
            ->get()
            ->groupBy('DOC_ID');    
                    
        $docIds = $res_cashbon->keys();
        $res_approved = Approved::whereIn('doc_id', $docIds)
            ->where('status', 'Approved')
            ->get()
            ->keyBy('doc_id');

        // dd($docIds);
        return view('approved.list_acc-approved', compact('res_cashbon','res_approved'));
    }

    public function notifcount()
    {
        $countlist = Approved::where('status', 'Pending')
        // ->where('status_mg', 'Belum Dikonfirmasi') 
        ->count();
        // dd($countlist);
        $countlistqc = Approved::where('status', 'Belum Dikoreksi')
        ->where('status_mg', 'Tidak di ACC Manager') 
        ->count();

        return view('layouts.sidebar', compact('countlist','countlistqc'));
    }
    
    public function showAcc($id)
    {
        $data = Approved::find($id);
        $co_id = Auth::user()->co_id;
        $id_cashbon = $id;
        $id = str_replace('_', '/', $id);
        
        $cashbon = DB::connection('firebird')->select("
            SELECT * 
            FROM cashbon_settlament_s(?) 
            WHERE co_id = ? AND doc_id = ?
            ", [$co_id, $co_id, $id]);
        $find = $cashbon[0]; 
        $res_detail = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->get();

        $total_lpj = DB::connection('firebird')
            ->table("CASHBON_LPJ")
            ->where('DOC_ID', $id)
            ->sum('TOTAL');
        
        $belum_lpj = $find->NOMINAL - $total_lpj;
        $kelebihan = $total_lpj - $find->NOMINAL;
        if ($kelebihan < 0){
            $kelebihan = 0;
        } else if($belum_lpj < 0){
            $belum_lpj = 0;
        }

        $res_approved = Approved::select(
            'approved.id',
            'approved.doc_id',
            'approved.name_approved',
            'approved.role_id',
            'role.role_name',
            'approved.notes',
            'approved.status',
            'approved.approved_date',
        )
            ->leftJoin('role', 'approved.role_id', '=', 'role.role_id')
            ->where('doc_id', $id)

            // ->where('status', 'tolak')
            ->get();
        
        return view('approved.show_acc-approved', compact('data', 'find', 'res_detail', 'belum_lpj', 'kelebihan', 'total_lpj', 'id_cashbon'));
    }
}
