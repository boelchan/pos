@extends('layouts.pos')
@section('title', 'Kasir')
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
                            <x-form :action="route('cart.index')" method="get">
                                <x-form-input name="search" placeholder="Cari produk" onblur="this.form.submit()" value="{{ request()->search }}" />
                            </x-form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row row-cards pt-2">
                            @foreach ($products as $product)
                                <div class="col-md-3 p-1 mt-0">
                                    <x-form action="{{ route('cart.add', $product->id) }}">
                                        <div class="card cursor-pointer card-product shadow-sm" onclick="this.closest('form').submit();return false;">
                                            <div class="card-body p-1" style="height: 65px">
                                                <div class="text-uppercase fw-bold">{{ $product->sku }}</div>
                                                {{ Str::limit($product->name, 60) }}
                                            </div>
                                            <div class="card-footer py-0 px-1">
                                                <div class="d-flex justify-content-between">
                                                    <div> {{ angka($product->qty) }} </div>
                                                    <div class="fw-bold"> {{ rupiah($product->price) }} </div>
                                                </div>
                                            </div>
                                        </div>
                                    </x-form>
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
                        <div class="card-title fs-1">Keranjang @if ($cart_data)
                                ({{ $cart_data->count() }})
                            @endif
                        </div>
                    </div>
                    <div class="card-body overflow-auto" style="height:53vh">
                        @forelse($cart_data as $index=>$item)
                            <div class="card mb-1 shadow-sm p-1">
                                <div class="px-1">
                                    {{ $item['name'] }}
                                </div>
                                <div class="d-flex py-0 px-1">
                                    <div>
                                        <x-form action="{{ route('cart.remove', $item['rowId']) }}" class="d-inline cursor-pointer">
                                            <a onclick="this.closest('form').submit();return false;" title="Hapus item"><i class="ti ti-x text-red rounded-pill fw-bold fs-2"></i></a>
                                        </x-form>
                                    </div>
                                    <div class="d-flex me-1 border rounded shadow-lg">
                                        <span class="p-0">
                                            <x-form action="{{ route('cart.decrease', $item['rowId']) }}" class="cursor-pointer">
                                                <a onclick="this.closest('form').submit();return false;" title="kurang"><i class="ti ti-minus fs-3 fw-bold"></i></a>
                                            </x-form>
                                        </span>
                                        <x-form action="{{ route('cart.update', $item['rowId']) }}">
                                            <input name="qty" type="text" class="form-control text-center text-black p-0 border-0" style="width:30px" value="{{ $item['qty'] }}"
                                                onblur="this.form.submit()" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                        </x-form>
                                        <span class="p-0">
                                            <x-form action="{{ route('cart.increase', $item['rowId']) }}" method="POST" class="cursor-pointer">
                                                <a onclick="this.closest('form').submit();return false;" title="tambah"><i class="ti ti-plus fs-3 fw-bold"></i></a>
                                            </x-form>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        {{ angka($item['pricesingle']) }}
                                    </div>
                                    <div class="text-end fw-bold">
                                        {{ rupiah($item['price']) }}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row">
                                <span class="text-center">Kosong</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <div class="card mt-2 border-primary">
                    <ul class="list-group list-group-flush fw-bold">
                        <li class="list-group-item py-2">
                            <div class="d-flex justify-content-between ">
                                <span> Diskon </span>
                                <x-form action="{{ route('cart.index') }}" method="get">
                                    <div class="input-icon">
                                        <span class="input-icon-addon">
                                            Rp
                                        </span>
                                        <input type="number" class="form-control text-end pe-3 py-1" name="discount" value="{{ $data_total['discount'] }}" onchange="this.form.submit()">
                                    </div>
                                </x-form>
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
                                <x-form action="{{ route('cart.clear') }}">
                                    <button class="btn bg-danger card-btn" onclick="return confirm('Apakah anda yakin membatalkan transaksi ini ?');" type="submit">BATAL</button>
                                </x-form>
                                <button class="btn bg-success card-btn" data-bs-toggle="offcanvas" data-bs-target="#modalBayar">BAYAR</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="offcanvas offcanvas-end" id="modalBayar">
            <div class="offcanvas-header">
                <h1 class="offcanvas-title">Billing Information</h1>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas"></button>
            </div>
            <div class="offcanvas-body">
                <x-form action="{{ route('cart.bayar') }}">
                    <div class="modal-body">
                        <x-form-select name="customer_id" :options="$customers" default="1" label="Pelanggan" floating class="mb-2" />

                        <div class="card mb-2">
                            <div class="card-footer p-2">
                                Total
                            </div>
                            <div class="card-body p-2 text-center">
                                <label class="fw-bold fs-1">{{ angka($data_total['total']) }}</label>
                                <input id="totalHidden" type="hidden" name="totalHidden" value="{{ $data_total['total'] }}" />
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-footer p-2">
                                Bayar
                            </div>
                            <div class="card-body p-2 text-center mb-1">
                                <x-form-input name="bayar" type="number" id="oke" autofocus class="fs-2 px-1 py-0 text-center" />
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-footer p-2">
                                Kembalian
                            </div>
                            <div class="card-body p-2 text-center">
                                <label class="fw-bold fs-1" id="kembalian">-</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-lg btn-success w-100" id="saveButton" disabled onClick="openWindowReload(this)">Bayar</button>
                    </div>
                </x-form>
            </div>
        </div>

    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#modalBayar').on('shown', function() {
                console.log('adas');
                $('#oke').trigger('focus');
            });
        });

        oke.oninput = function() {
            let jumlah = parseInt(document.getElementById('totalHidden').value) ? parseInt(document.getElementById('totalHidden').value) : 0;
            let bayar = parseInt(document.getElementById('oke').value) ? parseInt(document.getElementById('oke').value) : 0;
            let hasil = bayar - jumlah;

            document.getElementById("kembalian").innerHTML = hasil ? (hasil) : 'Rp 0';

            cek(bayar, jumlah);
            const saveButton = document.getElementById("saveButton");

            if (jumlah === 0) {
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
    <style>
        @media only screen and (max-width: 600px) {
            .gambar {
                width: 100%;
                height: 100%;
                padding: 0.9rem 0.9rem
            }
        }

        .card-product:hover {
            border: 1px solid black !important;
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
