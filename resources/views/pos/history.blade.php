@extends('layouts.app')
@section('title', 'Riwayat')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col">
                <div class="card" style="min-height: 85vh">
                    <div class="card-header bg-white">
                        <h4 class="font-weight-bold">Riwayat Transaksi</h4>
                    </div>
                    <div class="card-body">
                        <form action="history" method="get">
                            <div class="row mb-1">
                                <div class="col-md-3">
                                    <input type="date" name="tanggal" class="form-control">
                                </div>
                                <div class="col-md-3 p-0">
                                    <button class="btn btn-primary btn-sm" type="submit">Cari</button>
                                </div>
                            </div>
                        </form>

                        <table class="table table-sm">
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nomor Invoices</th>
                                <th>Customer</th>
                                <th>Admin</th>
                                <th>Bayar</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                            @foreach ($history as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $item->created_at }}</td>
                                    <td>{{ $item->invoices_number }}</td>
                                    <td>{{ $item->customer?->nama }}</td>
                                    <td>{{ $item->user?->name }}</td>
                                    <td>{{ $item->pay }}</td>
                                    <td>{{ $item->total }}</td>
                                    <td>
                                        <a href="{{ url('/transcation/edit', $item->invoices_number) }}" class="btn btn-info btn-sm"><i class="fas fa-edit"></i></a>
                                        <a href="{{ url('/transcation/laporan', $item->invoices_number) }}" class="btn btn-primary btn-sm"><i class="fas fa-print"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                        <div>{{ $history->links() }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
