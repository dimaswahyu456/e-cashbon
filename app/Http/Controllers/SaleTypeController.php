<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\SaleType;

class SaleTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $analisa = SaleType::with('saleType:co_id,st_id,st_name')->get();
        return view('analisa.list-analisa', compact('analisa'));
    }

    public function create()
    {
        $res_type = DB::select('select * from sale_type');
        return view('analisa.add-analisa', compact('res_type'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'ANALISA_NAME' => 'required'
        ]);
    
        try {
            $analisa_id = $this->generateAnalisaCode();
    
            DB::table('ANALISA')->insert([
                'CO_ID' => 'AAS',
                'ANALISA_ID' => $analisa_id,
                'ANALISA_NAME' => $request->ANALISA_NAME,
                'ST_ID' => $request->ST_ID,
                'REMARKS' => $request->REMARKS,
                'STATUS' => 1,
                'TIMEEDIT' => now(),
                'TIMEINPUT' => now()
            ]);
    
            return redirect()
                ->route('analisa.list')
                ->with('success', 'Analisa created!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }
    private function generateAnalisaCode()
    {
        $coId = 'AAS';

        $latestAnalisa = SaleType::where('CO_ID', $coId)
        ->orderByDesc(DB::raw('CAST(ANALISA_ID AS INTEGER)'))
        ->first();

        $lastNumber = $latestAnalisa ? (int) $latestAnalisa->ANALISA_ID : 0;

        return $lastNumber + 1;
    }

    public function edit($id)
    {
        $find = DB::table('ANALISA')->where('ANALISA_ID', $id)->first();
        return view('analisa.edit-analisa', compact('find'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'ANALISA_NAME' => 'required'
        ]);
    
        $update = DB::table('ANALISA')
            ->where('ANALISA_ID', $id)
            ->update([
                'ANALISA_NAME' => $request->ANALISA_NAME,
                'ST_ID' => $request->ST_ID,
                'REMARKS' => $request->REMARKS,
                'STATUS' => $request->STATUS,
                'TIMEEDIT' => now()
            ]);
    
        if ($update) {
            return redirect()->route('analisa.list')->with('success', 'Analisa updated!');
        } else {
            return back()->with('error', 'Update failed!');
        }
    }

    public function destroy($id)
    {
        $delete = DB::table('ANALISA')->where('ANALISA_ID', $id)->delete();

        if ($delete) {
            return redirect()->route('analisa.list')->with('success', 'Analisa deleted!');
        } else {
            return back()->with('error', 'Delete failed!');
        }
    }
}
