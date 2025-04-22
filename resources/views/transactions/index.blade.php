@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Status Transaksi Anda</h2>

        <table class="table">
            <thead>
                <tr>
                    <th>ID Transaksi</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->id }}</td>
                        <td>{{ $transaction->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
