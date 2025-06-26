<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function index()
    {
        return View('Login.login');
    }

    public function auth(Request $request)
    {
        $credentials = $request->validate([
            'name' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            // dd($request);
            return redirect()->intended('/dashboard');
        }
        return redirect('/login')->withErrors([
            'login' => 'Login gagal. Nama atau password salah.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
    public function setting()
    {
        return view('setting');
    }

    public function edituser(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
        ]);
        //password kosong
        if ($request->password == null) {
            $user = [
                'name' => $request->name,
                'updated_at' => date("Y-m-d H:i:s")
            ];
        }
        //password tidak kosong
        else {
            $user = [
                'name' => $request->name,
                'password' => bcrypt($request->password),
                'updated_at' => date("Y-m-d H:i:s")
            ];
        }

        $cek = DB::table('users')->where('name', $request->name)->count();
        if ($cek == 0) {
            try {
                DB::table('users')->where('id', $request->id)->update($user);
                return back()->with('success', 'Update pengguna berhasil!');
            } catch (Exception $e) {
                return back()->with('failed', 'Update pengguna gagal!');
            }
        } else {
            return back()->with('failed', 'Username telah dipakai!');
        }
    }
}
