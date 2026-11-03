<?php

namespace App\Http\Controllers;

use App\Repositories\Otentikasi\AuthRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Services\SimpegService;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $simpeg;
    protected $authRepository;

    public function __construct(SimpegService $simpeg, AuthRepository $authRepository)
    {
        $this->simpeg = $simpeg;
        $this->authRepository = $authRepository;
    }

    public function showLogin()
    {
        if (session('is_login')) {
            return redirect('/dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {

        $request->validate([
            'nip' => 'required',
            'password' => 'required'
        ]);

        $result = $this->simpeg->login(
            $request->nip,
            $request->password
        );

        if (!$result['success']) {

            return back()->withErrors([
                'login' => $result['message']
            ]);
        }

        // simpan ke session
        session([
            'token' => $result['token_simpeg'],
            'user' => $result['pengguna'],
            'pegawai' => $result['pegawai'],
            'is_login' => true
        ]);
        session()->regenerate();

        //Jalankan otentikasi lokal
        $id_user = session('user.id_user');
        $pengguna = $this->authRepository->attemptext($id_user);
        // $pengguna = session('user'); // atau dari
        // dd(auth()->user()->role);
        // dd($result['pengguna']);

        // $user = auth()->user();
        // $role = $user ? $user->getRoleNames()->first() : null;
        // dd(auth()->user()->getRoleNames());
                return redirect('/dashboard');
    }
    public function logout()
    {
        Auth::logout(); // kalau pakai auth Laravel
    
        Session::flush();
        Session::invalidate();
        Session::regenerateToken();
    
        return redirect('/login');
    }
}
