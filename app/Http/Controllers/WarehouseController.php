<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Warehouse;
// use Illuminate\Http\Request;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $res_wh = DB::connection('firebird')
            ->table('WAREHOUSE')
            ->orderBy('WAREHOUSE_ID', 'ASC')
            ->get();

        // return response()->json($res_wh);
        return view('warehouse.list-warehouse', compact('res_wh'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('warehouse.add-warehouse');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'WAREHOUSE_NAME' => 'required'
        ]);
    
        try {
            $warehouse_id = $this->generateWarehouseCode();
    
            DB::connection('firebird')->table('WAREHOUSE')->insert([
                'CO_ID' => 'AAS',
                'WAREHOUSE_ID' => $warehouse_id,
                'WAREHOUSE_NAME' => $request->WAREHOUSE_NAME,
                'ADDRESS' => $request->ADDRESS,
                'CITY' => $request->CITY,
                'REMARKS' => $request->REMARKS,
                'STATUS' => 1,
                'TIMEEDIT' => now(),
                'TIMEINPUT' => now()
            ]);
    
            return redirect()
                ->route('warehouse.list')
                ->with('success', 'Warehouse created!');
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }

    private function generateWarehouseCode()
    {
        $coId = 'AAS';

        $warehouses = DB::connection('firebird')
            ->table('WAREHOUSE')
            ->where('CO_ID', $coId)
            ->get();

        $lastNumber = 0;

        foreach ($warehouses as $warehouse) {
            $numberPart = intval(substr($warehouse->WAREHOUSE_ID, 3)); 
            if ($numberPart > $lastNumber) {
                $lastNumber = $numberPart;
            }
        }

        $newNumber = $lastNumber + 1;
        return 'GDG' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        $find = DB::connection('firebird')->table('WAREHOUSE')->where('WAREHOUSE_ID', $id)->first();
        return view('warehouse.edit-warehouse', compact('find'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'WAREHOUSE_NAME' => 'required'
        ]);
    
        $update = DB::connection('firebird')->table('WAREHOUSE')
            ->where('WAREHOUSE_ID', $id)
            ->update([
                'WAREHOUSE_NAME' => $request->WAREHOUSE_NAME,
                'ADDRESS' => $request->ADDRESS,
                'CITY' => $request->CITY,
                'REMARKS' => $request->REMARKS,
                'STATUS' => 1,
                'TIMEEDIT' => now()
            ]);
    
        if ($update) {
            return redirect()->route('warehouse.list')->with('success', 'Warehouse updated!');
        } else {
            return back()->with('error', 'Update failed!');
        }
    }

    public function destroy($id)
    {
        $delete = DB::connection('firebird')->table('WAREHOUSE')->where('WAREHOUSE_ID', $id)->delete();

        if ($delete) {
            return redirect()->route('warehouse.list')->with('success', 'Warehouse deleted!');
        } else {
            return back()->with('error', 'Delete failed!');
        }
    }
}
