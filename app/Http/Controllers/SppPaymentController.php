<?php

namespace App\Http\Controllers;

use App\Models\SppPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Produk;
use App\Models\User;

class SppPaymentController extends Controller
{
    // Menampilkan daftar pembayaran SPP untuk admin
    public function adminIndex()
    {
        $payments = SppPayment::all();
        return view('admin.spp.index', compact('payments')); // Perbaikan: Mengubah view ke 'admin.spp.index'
    }

    // Menampilkan daftar pembayaran SPP untuk user
    public function userIndex()
{
    // Ambil data pembayaran SPP terkait pengguna yang sedang login
    $payments = Auth::user()->sppPayments()->with('produk')->get();
    // Pastikan sppPayments() ada di model User

    // Kirim data pembayaran ke tampilan
    return view('spp.user_index', compact('payments'));
}


    // Menampilkan form tambah pembayaran
    public function create()
    {
        return view('admin.create');
    }

    // Menyimpan pembayaran baru
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric',
            'status' => 'required|string'
        ]);

        SppPayment::create($request->all());
        return redirect()->route('admin.spp')->with('success', 'Pembayaran SPP berhasil ditambahkan');
    }

    // Menampilkan form edit pembayaran
    public function edit(SppPayment $payment)
    {
        return view('spp.edit', compact('payment'));
    }
    
    
    public function update(Request $request, $id)
{
    $payment = SppPayment::findOrFail($id);

    // Validasi input
    $validated = $request->validate([
        'kelas' => 'required|string',
        'jumlah' => 'required|numeric',
        'bukti_pembayaran' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $payment->kelas = $validated['kelas'];
    $payment->jumlah = $validated['jumlah'];

    // Cek jika ada file bukti pembayaran
    if ($request->hasFile('bukti_pembayaran')) {
        $file = $request->file('bukti_pembayaran');
        $filename = time().'_'.$file->getClientOriginalName();
        $file->storeAs('public/bukti_pembayaran', $filename);
        $payment->bukti_pembayaran = 'bukti_pembayaran/' . $filename;
    }

    $payment->save();

    // Redirect ke halaman index
    return redirect()->route('user.spp.index')->with('success', 'Pembayaran berhasil diperbarui.');
}
 

    // Menghapus pembayaran
    public function destroy($id)
    {
        $payment = SppPayment::findOrFail($id); // Perbaikan: Menghapus SppPayment, bukan Produk
        $payment->delete();
    
        return redirect()->route('admin.spp')->with('success', 'Pembayaran SPP berhasil dihapus');
    }

    // Update status pembayaran oleh admin
   // Update status pembayaran oleh admin atau user
   public function updateStatus(Request $request, $id)
   {
       $payment = SppPayment::findOrFail($id);
       $status = $request->input('status');
   
       // Pastikan status hanya bisa 'lunas' atau 'gagal'
       if (in_array($status, ['lunas', 'gagal'])) {
           $payment->status = $status;
           $payment->save();
   
           return redirect()->back()->with('success', 'Status pembayaran berhasil diperbarui.');
       }
   
       return redirect()->back()->with('error', 'Status tidak valid.');
   }   
   
public function upload(Request $request, $id)
{
    $request->validate([
        'bukti_pembayaran' => 'required|image|max:2048',
    ]);

    $payment = SppPayment::findOrFail($id);
    
    if ($request->hasFile('bukti_pembayaran')) {
        $path = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
        $payment->bukti_pembayaran = $path;
        $payment->status = 'waiting';  // Status diubah menjadi 'waiting' setelah bukti pembayaran diunggah
        $payment->save();
    }

    return redirect()->back()->with('success', 'Bukti pembayaran berhasil diunggah.');
}

// Controller: Pembayaran SPP
public function confirm($id)
{
    $payment = SppPayment::findOrFail($id);

    // Pastikan hanya bisa mengonfirmasi yang statusnya 'waiting'
    if ($payment->status == 'waiting') {
        $payment->status = 'lunas';  // Jika statusnya 'waiting', ubah menjadi 'lunas'
        $payment->save();
        return redirect()->route('admin.spp')->with('success', 'Pembayaran berhasil dikonfirmasi sebagai lunas.');
    }

    return redirect()->route('admin.spp')->with('error', 'Status pembayaran tidak dapat diubah.');
}

public function storeByUser(Request $request)
{
    $validated = $request->validate([
        'bulan' => 'required|string',
        'jumlah' => 'required|numeric',
        'bukti_pembayaran' => 'required|file|mimes:jpg,png,pdf',
    ]);

    $payment = new SppPayment();
    $payment->bulan = $request->bulan;
    $payment->jumlah = $request->jumlah;
    $payment->status = 'pending';  // Status pertama kali adalah pending
    $payment->user_id = Auth::user()->id;

    // Upload bukti pembayaran
    if ($request->hasFile('bukti_pembayaran')) {
        $payment->bukti_pembayaran = $request->file('bukti_pembayaran')->store('bukti_pembayaran', 'public');
    }

    $payment->save();

    return redirect()->route('user.spp.index')->with('success', 'Pembayaran berhasil ditambahkan!');
}

public function check($id)
{
    $payment = SppPayment::findOrFail($id);

    // Lakukan logika pengecekan atau perubahan status, misalnya:
    if ($payment->status == 'waiting') {
        $payment->status = 'lunas';
        $payment->save();
    }

    return redirect()->route('user.spp.index')->with('success', 'Pembayaran SPP telah diperiksa.');
}

public function approveIndex()
{
    // Ambil transaksi dengan status 'pending' untuk di-approve
    $transactions = SppPayment::where('status', 'pending')->get();
    return view('admin.transactions.approve', compact('transactions'));
}

// Mengubah status transaksi menjadi 'approved'
public function approveTransaction($id)
{
    $transaction = SppPayment::findOrFail($id);
    $transaction->status = 'approved';
    $transaction->save();

    return redirect()->route('admin.transactions.approve')->with('success', 'Transaksi telah disetujui');
}
public function userTransactions()
    {
        $transactions = SppPayment::where('user_id', Auth::id())->get(); 
        return view('user.transactions.index', compact('transactions'));
    }

    
}
