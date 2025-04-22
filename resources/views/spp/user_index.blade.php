@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Daftar Pembayaran SPP Anda</h1>
    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>

    <!-- Form Tambah Pembayaran -->
    <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf

        <!-- Dropdown kelas -->
        <select name="kelas" class="form-control mb-2" required>
            <option value="">-- Pilih Kelas --</option>
            <option value="X IPA 1">X IPA 1</option>
            <option value="X IPA 2">X IPA 2</option>
            <option value="X IPS 1">X IPS 1</option>
            <option value="XI IPA 1">XI IPA 1</option>
            <option value="XI IPS 1">XI IPS 1</option>
            <option value="XII IPA 1">XII IPA 1</option>
            <option value="XII IPS 1">XII IPS 1</option>
        </select>

        <input type="date" name="tanggal" placeholder="Tanggal" class="form-control mb-2" required>
        <input type="number" name="jumlah" placeholder="Jumlah" class="form-control mb-2" required>
        <input type="text" name="bulan" placeholder="Bulan (contoh: Januari)" class="form-control mb-2" required>
        <input type="file" name="bukti_pembayaran" class="form-control mb-2" required>
        <button type="submit" class="btn btn-success">Kirim Pembayaran</button>
    </form>

    <!-- Tabel Daftar Pembayaran -->
    <div class="table-responsive">
        <table class="table table-hover table-bordered">
            <thead class="table-primary text-center">
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th>Tanggal</th>
                    <th>Bulan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Bukti Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr class="text-center">
                        <td>{{ $payment->user->name ?? '-' }}</td>
                        <td>{{ $payment->kelas ?? '-' }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->tanggal)->format('d-m-Y') ?? '-' }}</td>
                        <td>{{ $payment->bulan ?? '-' }}</td>
                        <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($payment->status == 'pending')
                                <span class="badge bg-warning">Menunggu Pembayaran</span>
                            @elseif($payment->status == 'waiting')
                                <span class="badge bg-info text-dark">Menunggu Konfirmasi</span>
                            @else
                                <span class="badge bg-success">Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($payment->bukti_pembayaran)
                                <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-secondary">Lihat Bukti</a>
                            @elseif($payment->status == 'pending')
                                <form action="{{ route('payments.upload', $payment->id) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="bukti_pembayaran" class="form-control form-control-sm mb-2" required>
                                    <button type="submit" class="btn btn-sm btn-primary">Upload</button>
                                </form>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('payments.edit', $payment->id) }}" class="btn btn-sm btn-info">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Styling tambahan -->
<style>
    .table-hover tbody tr:hover {
        background-color: #e9ecef !important;
        transition: 0.3s ease;
    }
    .badge {
        font-size: 14px;
        padding: 6px 12px;
        border-radius: 5px;
    }
</style>
@endsection
