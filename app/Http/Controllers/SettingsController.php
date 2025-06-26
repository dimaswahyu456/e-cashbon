<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     
     public function showSettingsForm()
     {
         $settings = DB::table('datetime')->first();
 
         return view('settings.form', compact('settings'));
     }
 
     public function saveSettings(Request $request)
     {
         $this->validate($request, [
             'date' => 'required|date',
             'time' => 'required|date_format:H:i', // Validasi format jam
         ]);
 
         // Menggabungkan tanggal dan waktu menjadi format DateTime
         $dateTime = $request->date . ' ' . $request->time;
 
         // Mengonversi ke format unix timestamp hingga detik
         $timestamp = strtotime($dateTime);
 
         // Simpan tanggal dan waktu pengiriman otomatis ke dalam database
         DB::table('datetime')->updateOrInsert(
             [],
             ['scheduled_datetime' => date('Y-m-d H:i:s', $timestamp)] // Simpan dalam format MySQL datetime
         );
 
         return redirect()->back()->with('success', 'Pengaturan tanggal dan waktu berhasil disimpan.');
        }
 
     // Metode lain tetap tidak berubah sesuai dengan kebutuhan Anda

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
