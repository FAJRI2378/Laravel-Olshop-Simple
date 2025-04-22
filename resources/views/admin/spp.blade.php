@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="text-center my-4">Daftar Pembayaran SPP</h2>
    
    <div class="table-responsive">
        <table class="table table-striped table-bordered">
            <thead class="table-dark text-center">
                <tr>
                    <th>Nama</th>
                    <th>Bulan</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pembayaran as $data)
                    <tr class="align-middle text-center">
                        <td>{{ $data->user->name }}</td>
                        <td>{{ $data->bulan }}</td>
                        <td>Rp {{ number_format($data->jumlah, 0, ',', '.') }}</td>
                        <td>
                            @if($data->status == 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @else
                                <span class="badge bg-success">Lunas</span>
                            @endif
                        </td>
                        <td>
                            @if($data->status == 'pending')
                                <form method="POST" action="{{ route('admin.spp.update', $data->id) }}">
                                    @csrf
                                    <button class="btn btn-sm btn-success">Konfirmasi</button>
                                </form>
                            @else
                                <span class="badge bg-secondary">✔</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .table th, .table td {
        text-align: center;
        vertical-align: middle;
    }
    .table tbody tr:hover {
        background-color: #f8f9fa !important;
    }
    .badge {
        font-size: 14px;
        padding: 5px 10px;
    }
</style>
@endsection
