@extends('layouts.pos')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-md-8">
            <div class="card" style="min-height:85vh">
                <div class="card-header">
                    <div class="card-title fs-1">
                        POS
                    </div>
                    <div class="card-actions pe-1">
                        <x-form :action="url('/transcation')" method="get">
                            <x-form-input name="search" placeholder="Cari produk" onblur="this.form.submit()"
                                value="{{ request()->search }}" />
                        </x-form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row row-cards">
                        @foreach ($products as $product)
                        <div class="col-md-3 p-1 mt-0">
                            <form action="{{url('/transcation/addproduct', $product->id)}}" method="POST">
                                @csrf
                                <div class="card cursor-pointer card-product"
                                    onclick="this.closest('form').submit();return false;">
                                    <div class="card-body py-0" style="height: 65px">
                                        <div class="text-uppercase fw-bold">{{ $product->sku }}</div>
                                        {{ Str::limit($product->name, 45) }}
                                    </div>
                                    <div class="card-footer py-0">
                                        {{ rupiah($product->price) }}
                                    </div>
                                </div>
                            </form>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer text-center">
                    {!! $products->links() !!}
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <div class="card-title fs-1">Keranjang</div>
                </div>
                <div class="card-table">
                    <div class="overflow-auto" style="height:52vh">
                        <table class="table table-sm border-bottom table-hover">
                            @php $no=1 @endphp
                            @forelse($cart_data as $index=>$item)
                            <tr>
                                <td width="5%">
                                    {{$no++}}
                                </td>
                                <td width="60%">
                                    <span class="fw-bold d-block">
                                        {{ $item['name'] }}
                                    </span>
                                    <form action="{{url('/transcation/removeproduct',$item['rowId'])}}" method="POST"
                                        class="d-inline cursor-pointer">
                                        @csrf
                                        <a onclick="this.closest('form').submit();return false;" title="Hapus item">
                                            <i class="ti ti-trash-x text-red"></i></a>
                                    </form>

                                    <form action="{{url('/transcation/updateCart', $item['rowId'])}}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <input name="qty" type="number" class="form-control py-0 px-1 d-inline"
                                            style="width:50px" value="{{$item['qty']}}" onblur="this.form.submit()">
                                    </form>
                                    <div class="d-inline">
                                        x {{ angka($item['pricesingle']) }}
                                    </div>

                                </td>
                                <td width="35%" class="text-end align-middle">{{ rupiah($item['price']) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Empty Cart</td>
                            </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>

            <div class="card mt-2 border-primary">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item py-2">
                        <div class="d-flex justify-content-between">
                            <span> Diskon </span>
                            <form action="{{ url('/transcation') }}" method="get">
                                <input type="number" class="form-control text-end pe-3 py-0" name="discount"
                                    value="{{ $data_total['discount'] }}" onchange="this.form.submit()">
                            </form>
                        </div>
                    </li>
                    <li class="list-group-item py-2">
                        <div class="d-flex justify-content-between">
                            <span> Sub Total </span>
                            <span> {{ rupiah($data_total['sub_total']) }} </span>
                        </div>
                    </li>
                    <li class="list-group-item py-0">
                        <div class="d-flex justify-content-between align-center fs-1">
                            <span> TOTAL </span>
                            <span> {{ rupiah($data_total['total']) }} </span>
                        </div>
                    </li>
                    <li class="list-group-item p-0">
                        <div class="d-flex">
                            <form action="{{ url('/transcation/clear') }}" method="POST">
                                @csrf
                                <button class="btn bg-danger card-btn"
                                    onclick="return confirm('Apakah anda yakin membatalkan transaksi ini ?');"
                                    type="submit">BATAL</button>
                            </form>
                            <button class="btn bg-success card-btn" data-toggle="modal"
                                data-target="#fullHeightModalRight">BAYAR</button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="modal fade right" id="fullHeightModalRight" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">

        <!-- Add class .modal-full-height and then add class .modal-right (or other classes from list above) to set a position to the modal -->
        <div class="modal-dialog modal-full-height modal-right" role="document">

            <!-- Sorry campur2 bahasa indonesia sama inggris krn kebiasaan make b.inggris eh ternyata buat aplikasi buat indonesia jadi gini deh  -->
            <div class="modal-content">
                <div class="modal-header indigo">
                    <h6 class="modal-title w-100 text-light" id="myModalLabel">Billing Information</h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="{{ url('/transcation/bayar') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="oke">Member</label>
                            <select name="customer_id" id="" class="form-control" style="font-size: 13px">
                                @foreach ( $customers as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="oke">Input Nominal</label>
                            <input id="oke" class="form-control" type="number" name="bayar" autofocus />
                        </div>
                        <h3 class="font-weight-bold">Total:</h3>
                        <h1 class="font-weight-bold mb-5">Rp. {{ $data_total['total'] }}</h1>
                        <input id="totalHidden" type="hidden" name="totalHidden" value="{{$data_total['total']}}" />

                        <h3 class="font-weight-bold">Bayar:</h3>
                        <h1 class="font-weight-bold mb-5" id="pembayaran"></h1>

                        <h3 class="font-weight-bold text-primary">Kembalian:</h3>
                        <h1 class="font-weight-bold text-primary" id="kembalian"></h1>
                </div>

                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-info" data-dismiss="modal">Keluar</button>
                    <button type="submit" class="btn btn-primary" id="saveButton" disabled
                        onClick="openWindowReload(this)">Bayar</button>
                </div>
                </form>
            </div>
        </div>
    </div>
    @endsection
    <!-- Ini error harusnya bisa dinamis ambil value dari controller tp agar cepet ya biar aja gini silahkan modifikasi  -->
    @push('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @if(Session::has('error'))
    <script>
        toastr.error(
            'Telah mencapai jumlah maximum Product | Silahkan tambah stock Product terlebih dahulu untuk menambahkan'
        )

    </script>
    @endif

    @if(Session::has('errorTransaksi'))
    <script>
        toastr.error(
            'Transaksi tidak valid | perhatikan jumlah pembayaran | cek jumlah product'
        )

    </script>
    @endif

    @if(Session::has('success'))
    <script>
        toastr.success(
            'Transaksi berhasil'
        )
    </script>
    @endif

    <script>
        $(document).ready(function () {
            $('#fullHeightModalRight').on('shown.bs.modal', function () {
                $('#oke').trigger('focus');
            });
        });

        oke.oninput = function () {
            let jumlah = parseInt(document.getElementById('totalHidden').value) ? parseInt(document.getElementById('totalHidden').value) : 0;
            let bayar = parseInt(document.getElementById('oke').value) ? parseInt(document.getElementById('oke').value) : 0;
            let hasil = bayar - jumlah;
            document.getElementById("pembayaran").innerHTML = bayar ? 'Rp ' + rupiah(bayar) + ',00' : 'Rp ' + 0 +
                ',00';
            document.getElementById("kembalian").innerHTML = hasil ? 'Rp ' + rupiah(hasil) + ',00' : 'Rp ' + 0 +
                ',00';

            cek(bayar, jumlah);
            const saveButton = document.getElementById("saveButton");   

            if(jumlah === 0){
                saveButton.disabled = true;
            }

        };

        function cek(bayar, jumlah) {
            const saveButton = document.getElementById("saveButton");   

            if (bayar < jumlah) {
                saveButton.disabled = true;
            } else {
                saveButton.disabled = false;
            }
        }

        function rupiah(bilangan) {
            var number_string = bilangan.toString(),
                split = number_string.split(','),
                sisa = split[0].length % 3,
                rupiah = split[0].substr(0, sisa),
                ribuan = split[0].substr(sisa).match(/\d{1,3}/gi);

            if (ribuan) {
                separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }
            return rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
        }

    </script>

    @endpush

    @push('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <style>
        @media only screen and (max-width: 600px) {
            .gambar {
                width: 100%;
                height: 100%;
                padding: 0.9rem 0.9rem
            }
        }

        .card-product:hover {
            border: 1px solid red !important;
        }

        /* Chrome, Safari, Edge, Opera */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        /* Firefox */
        input[type=number] {
            -moz-appearance: textfield;
        }
    </style>
    @endpush