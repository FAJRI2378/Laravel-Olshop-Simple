@extends('layouts.app')

@section('content')
<div class="container">
    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <h2>Data Pembayaran SPP (Admin)</h2>

    <table class="table table-bordered mt-3">
        <thead class="table-primary text-center">
            <tr>
                <th>No</th>
                <th>Bulan</th>
                <th>Jumlah</th>
                <th>Status</th>
                <th>Bukti Pembayaran</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($payments as $key => $payment)
                <tr class="text-center align-middle">
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $payment->produk->bulan ?? 'Tidak ada produk' }}</td>
                    <td>Rp {{ number_format($payment->jumlah, 0, ',', '.') }}</td>
                    <td>
                        @if($payment->status == 'waiting')
                            <span class="badge bg-info">Menunggu Konfirmasi</span>
                        @elseif($payment->status == 'pending')
                            <span class="badge bg-warning text-dark">Menunggu Pembayaran</span>
                        @elseif($payment->status == 'gagal')
                            <span class="badge bg-danger">Gagal</span>
                        @else
                            <span class="badge bg-success">Lunas</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->bukti_pembayaran)
                            <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-secondary">Lihat Bukti</a>
                        @else
                            <span class="text-muted">Belum ada bukti</span>
                        @endif
                    </td>
                    <td>
                        @if($payment->status == 'waiting')
                            <div class="dropdown">
                                <button class="btn btn-sm btn-warning dropdown-toggle" type="button" id="aksiDropdown{{ $payment->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                    Pilih Aksi
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="aksiDropdown{{ $payment->id }}">
                                    <li>
                                        <form action="{{ route('payments.updateStatus', $payment->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="lunas">
                                            <button type="submit" class="dropdown-item text-success">Setujui (Lunas)</button>
                                        </form>
                                    </li>
                                    <li>
                                        <form action="{{ route('payments.updateStatus', $payment->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="status" value="gagal">
                                            <button type="submit" class="dropdown-item text-danger">Tandai Gagal</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @elseif($payment->status == 'lunas')
                            <form action="{{ route('payments.check', $payment->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-primary">Cek Pembayaran</button>
                            </form>
                        @else
                            <span class="text-muted">-</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
