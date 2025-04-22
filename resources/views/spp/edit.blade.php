@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Pembayaran SPP</h2>

    <form action="{{ route('payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Nama (readonly) -->
        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" value="{{ $payment->user->name }}" readonly>
        </div>

        <!-- Kelas -->
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas" class="form-control" required>
                <option value="">-- Pilih Kelas --</option>
                @foreach(['X IPA 1', 'X IPA 2', 'X IPS 1', 'XI IPA 1', 'XI IPS 1', 'XII IPA 1', 'XII IPS 1'] as $kelas)
                    <option value="{{ $kelas }}" {{ $payment->kelas == $kelas ? 'selected' : '' }}>{{ $kelas }}</option>
                @endforeach
            </select>
        </div>

        <!-- Tanggal -->
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $payment->tanggal }}" required>
        </div>

        <!-- Bulan -->
        <div class="mb-3">
            <label class="form-label">Bulan</label>
            <input type="text" name="bulan" class="form-control" value="{{ $payment->bulan }}" required>
        </div>

        <!-- Jumlah -->
        <div class="mb-3">
            <label class="form-label">Jumlah</label>
            <input type="number" name="jumlah" class="form-control" value="{{ $payment->jumlah }}" required>
        </div>

        <!-- Upload bukti pembayaran -->
        <div class="mb-3">
            <label class="form-label">Bukti Pembayaran</label>
            @if($payment->bukti_pembayaran)
                <p>
                    <a href="{{ asset('storage/' . $payment->bukti_pembayaran) }}" target="_blank" class="btn btn-sm btn-secondary mb-2">Lihat Bukti Saat Ini</a>
                </p>
            @endif
            <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
    </form>
</div>
@endsection
