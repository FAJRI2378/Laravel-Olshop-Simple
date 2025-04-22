<?php

namespace App\Http\Controllers;
use App\Models\SppPayment;
use Illuminate\Http\Request;


class AdminController extends Controller
{
    public function index()
{
$payments = SppPayment::all(); // Modify this as per your actual data source

    // Pass the payments variable to the view
    return view('admin.index', compact('payments'));
}

}
