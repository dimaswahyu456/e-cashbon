<?php

namespace App\Http\Controllers;

use App\Models\CashbonDetail;
use App\Models\Approved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CashbonDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $res_detail = DB::select('select * from cashbon_detail');
        $title = 'Data Detail Cashbon';
        return view('cashbon_detail.list-detail', compact('title', 'res_detail'));
    }

    public function create()
    {
        $type = DB::connection('firebird')
            ->table("LPJ_TYPE")
            ->where("STATUS", '1')
            ->get();
        return view('cashbon_detail.add-detail', compact('type'));
    }

    public function store(Request $request)
    {
        // dd($request->id_cashbon);
        $request->validate([
            'id_cashbon' => 'required',
            'doc_date' => 'required|date',
            'keterangan' => 'nullable|string',
            'doc_type' => 'required',
            'total' => 'required',
            'image.*' => 'nullable|file|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $folderName = str_replace('/', '_', $request->id_cashbon);
        // $destinationPath = public_path('temp/' . $folderName);
        
        $destinationPath = '/www/wwwroot/divisitic.saraswanti.info/upload/' . $folderName;

        if (!file_exists($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }

        $imageNames = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $file) {
                $newName = uniqid() . '.' . $file->extension();
                $file->move($destinationPath, $newName);
                $imageNames[] = $newName;
            }
        }

        $imageString = implode(',', $imageNames);
        // dd($request->doc_type);

        // $lpjBaru = DB::connection('firebird')
        // ->selectOne("SELECT NEXT VALUE FOR GEN_CASHBON_LPJ_ID AS ID")->id;

        try {
            DB::connection('firebird')->insert("
                INSERT INTO CASHBON_LPJ (
                    CO_ID, DOC_ID, DOC_DATE, REMARKS, TOTAL,
                    DOC_TYPE, IMAGE, VEND_ID, STATUS_APPROVED, USERINPUT, TIMEINPUT, TIMEEDIT
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", 
                [
                // $lpjBaru,
                Auth::user()->co_id,
                $request->id_cashbon,
                $request->doc_date,
                $request->keterangan ?? '',
                str_replace('.', '', $request->total),
                $request->doc_type,
                $imageString ?: null,
                Auth::user()->vend_id,
                '0',
                Auth::user()->name,
                now(),
                now()
            ]);
            
            $existing = Approved::where('doc_id', $request->id_cashbon)->first();
            // dd($existing);

            if ($existing) {
                $existing->update([
                    'status' => '0',
                ]);
            } else {
                Approved::create([
                    'doc_id' => $request->id_cashbon,
                    'status' => '0',
                ]);
            }

            return redirect()
                ->route('cashbon.show', ['id' => str_replace('/', '_', $request->id_cashbon)])
                ->with(['success' => 'New post has been created successfully']);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan cashbon detail: '.$e->getMessage());

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Gagal menyimpan cashbon detail: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $res_find = DB::select('select * from cashbon_detail where id=', [$id]);
        $find = $res_find[0];
        return view('cashbon_detail.show-detail', compact('find'));
    }

    public function edit($id)
    {
        $res_find = DB::select('select * from cashbon_detail where id=', [$id]);
        $find = $res_find[0];

        return view('cashbon_detail.show-detail', compact('find'));
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'id_cashbon' => 'required',
            'doc_date' => 'required',
            'image.*' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ];

        $validatedData = $request->validate($rules);
        
        // dd($request->id);

        $folderName = str_replace('/', '_', $request->id_cashbon);
        $destinationPath = '/www/wwwroot/divisitic.saraswanti.info/upload/' . $folderName;

        $oldImages = $request->oldImage ? explode(',', $request->oldImage) : [];
        $newImages = [];

        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $img) {
                $imageName = uniqid() . '.' . $img->getClientOriginalExtension();
                $img->move($destinationPath, $imageName);
                $newImages[] = $imageName;
            }
        }

        $imageNames = array_merge($oldImages, $newImages);
        $imageString = implode(',', $imageNames);
        // $total = str_replace('.', '', $request->total);
        $total = floatval(preg_replace('/[^\d]/', '', $request->total));
        // dd($total);

        try {
            DB::connection('firebird')->update("
                    UPDATE CASHBON_LPJ
                    SET
                        CO_ID = ?,
                        DOC_ID = ?,
                        DOC_DATE = ?,
                        REMARKS = ?,
                        TOTAL = ?,
                        DOC_TYPE = ?,
                        IMAGE = ?,
                        VEND_ID = ?,
                        STATUS_APPROVED = ?,
                        USEREDIT = ?,
                        TIMEEDIT = ?
                    WHERE LPJ_ID = ?
                ", [
                    Auth::user()->co_id,
                    $request->id_cashbon,
                    $request->doc_date,
                    $request->keterangan ?? '',
                    $total,
                    $request->doc_type,
                    $imageString,
                    Auth::user()->vend_id,
                    '0',
                    Auth::user()->name,
                    now(),
                    $request->id
                ]);
            
            return redirect()
                ->route('cashbon.show', ['id' => str_replace('/', '_', $request->id_cashbon)])
                ->with(['success' => 'Detail berhasil diupdate']);
        } catch (\Exception $e) {
            Log::error("Update gagal: ".$e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Gagal update data: '.$e->getMessage()]);
        }
    }

    public function sendResponse(Request $request)
    {
        try {
        DB::connection('firebird')->update("
            UPDATE CASHBON_LPJ
            SET NOTE_REASON = ?
            WHERE LPJ_ID = ? AND DOC_ID = ?
        ", [
            $request->response,
            $request->lpj_id,
            $request->id_cashbon,
        ]);

        return redirect()->back()->with('success', 'Tanggapan berhasil dikirim.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal mengirim tanggapan: '.$e->getMessage()]);
        }
    }


    public function destroy(Request $request, $id)
    {
        $lpj = DB::connection('firebird')->table('CASHBON_LPJ')
            ->where('LPJ_ID', $id)
            ->where('DOC_ID', $request->id_cashbon)
            ->first();
        
        // dd($lpj);

        if ($lpj && $lpj->IMAGE) {
            $folderName = str_replace('/', '_', $request->id_cashbon);
            $destinationPath = '/www/wwwroot/divisitic.saraswanti.info/upload/' . $folderName;
            $imageFiles = explode(',', $lpj->IMAGE);
            foreach ($imageFiles as $filename) {
                $filePath = $destinationPath . '/' . $filename;
                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }
        }

        $deleted = DB::connection('firebird')->table('CASHBON_LPJ')
            ->where('LPJ_ID', $id)
            ->delete();
        
        $lpjRemaining = DB::connection('firebird')->table('CASHBON_LPJ')
        ->where('DOC_ID', $request->id_cashbon)
        ->exists();

        if (!$lpjRemaining && File::isDirectory($destinationPath)) {
            File::deleteDirectory($destinationPath);
        }

        if ($deleted) {
            return redirect()
                ->route('cashbon.show', ['id' => str_replace('/', '_', $request->id_cashbon)])
                ->with(['success' => 'LPJ dan file terkait berhasil dihapus.']);
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with(['error' => 'Gagal menghapus data LPJ.']);
        }
    }

    public function deleteImage(Request $request)
    {
        $lpjId = $request->lpj_id;
        $image = $request->image;
        $folder = $request->folder;

        $folderPath = public_path("temp/" . $folder);
        $filePath = $folderPath . '/' . $image;

        try {
            $data = DB::connection('firebird')
                ->table('CASHBON_LPJ')
                ->where('LPJ_ID', $lpjId)
                ->first();

            if (!$data) {
                return response()->json(['success' => false, 'msg' => 'Data tidak ditemukan']);
            }

            $images = explode(',', $data->IMAGE);
            $updatedImages = array_filter($images, function($img) use ($image) {
                return trim($img) !== trim($image);
            });

            if (File::exists($filePath)) {
                File::delete($filePath);
            }

            $imageString = implode(',', $updatedImages);

            DB::connection('firebird')->update("
                UPDATE CASHBON_LPJ
                SET IMAGE = ?
                WHERE LPJ_ID = ?
            ", [$imageString, $lpjId]);

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('Gagal hapus gambar: ' . $e->getMessage());
            return response()->json(['success' => false, 'msg' => 'Gagal hapus: ' . $e->getMessage()]);
        }
    }
}
