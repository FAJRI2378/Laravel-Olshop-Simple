<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama untuk user biasa.
     */
    public function index()
    {
        // Mengambil produk untuk semua pengguna
        $produks = Produk::all();

        // Menampilkan tampilan berdasarkan role user
        if (Auth::check()) {
            $user = Auth::user();  // Mendapatkan user yang sedang login

            // Menampilkan halaman berdasarkan role user
            if ($user->role == 'admin') {
                return redirect()->route('admin.home'); // Redirect ke halaman admin
            } elseif ($user->role == 'manager') {
                return redirect()->route('manager.home'); // Redirect ke halaman manager
            } else {
                return view('home', compact('produks')); // Halaman untuk user biasa
            }
        }

        // Jika user belum login, tampilkan halaman login
        return redirect()->route('login');
    }

    /**
     * Menampilkan halaman utama untuk admin.
     */
    public function adminHome()
    {
        $produks = Produk::all(); // Mengambil semua produk
        return view('adminHome', compact('produks')); // Mengirim data ke view adminHome.blade.php
    }

    /**
     * Menampilkan halaman utama untuk manager.
     */
    public function managerHome()
    {
        $produks = Produk::all(); // Mengambil semua produk
        return view('managerHome', compact('produks')); // Mengirim data ke view managerHome.blade.php
    }
}
