<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $res_supplier = DB::connection('firebird')
            ->table('VENDOR')
            ->where('STATUS',1)
            ->orderBy('VEND_ID', 'ASC')
            ->get();
        return view('supplier.list-supplier', compact('res_supplier'));
    }
    public function show($id)
    {
        $find = DB::connection('firebird')->table('VENDOR')->where('VEND_ID', $id)->first();
        return view('supplier.show-supplier', compact('find'));
    }
}
