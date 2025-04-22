<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();

            if ($user->role == 'admin') {
                return redirect()->route('admin.index');  // Redirect to the admin home page
            } else {
                return redirect()->route('user.spp.index');  // Redirect to the user's SPP page
            }
        }
        return redirect()->route('login');  // If user is not authenticated, redirect to login
    }
}
