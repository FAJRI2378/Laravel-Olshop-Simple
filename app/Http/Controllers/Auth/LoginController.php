<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        //$this->middleware('guest')->except('logout');
    }

    public function login(Request $request): RedirectResponse
    {
        $input = $request->all();

        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt(['email' => $input['email'], 'password' => $input['password']])) {
            $user = Auth::user(); // Mendapatkan user yang sedang login

            if ($user->role === 'admin') {
                return redirect()->route('admin.index'); // Redirect ke halaman admin
            } elseif ($user->role === 'manager') {
                return redirect()->route('manager.home'); // Redirect ke halaman manager
            } else {
                return redirect()->route('home'); // Redirect ke halaman user biasa
            }
        } else {
            return redirect()->route('login')
                ->with('error', 'Email atau password salah.');
        }
    }
}
