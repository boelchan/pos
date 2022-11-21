@extends('layouts.app')
@section('title', 'Produk')
@section('content')
<div class="container-xl">
    <div class="row row-cards">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    Ubah data
                    <div class="card-actions">
                        <a href="javascript:void(0)" class="btn btn-danger delete-data" data-url="{{ route('product.destroy', $product->id) }}" data-token="{{ csrf_token() }}" data-label="{{ $product->name }}">
                            <i class="ti ti-trash fs-2 me-1"></i> Hapus
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <x-form action="{{url('/product')}}" enctype="multipart/form-data">
                        @bind($product)
                        <input type="hidden" name="id" value="{{ $product->id }}">
                        <input type="hidden" name="qty" value="{{ $product->qty }}">
                        <x-form-input label="nama" name="name" floating class="mb-2" />
                        <div class="row">
                            <div class="col">
                                <x-form-input label="Harga" name="price" type="number" floating class="mb-2" />
                                <div class="form-group">

                                    <x-form-input label="Gambar" name="image" type="file" floating class="mb-2" accept="image/*" onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0]); document.getElementById('preview').style.display = 'none'" />
                                    <div class="col-sm-12 col-md-6">
                                        <img id="output" src="" class="img-fluid">
                                    </div>

                                    @if($product->image)
                                    <img src="{{asset($product->image)}}" class="img-fluid" id="preview">
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <x-form-input label="Qty" name="qty" type="number" floating class="mb-2" />
                                <x-form-input label="Tambah / Kurangi Qty" name="addQty" type="number" floating />
                                <i class="text-muted">gunakan positif utk tambah | negatif utk mengurangi</i>
                            </div>
                        </div>
                        @endbind
                        <div class="form-group mt-6">
                            <button type="submit" class="btn btn-primary">Ubah Produk</button>
                        </div>
                    </x-form>
                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    History
                </div>
                <div class="card-table">
                    <table class="table" id="dtMaterialDesignExample">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Updated By</th>
                                <th>Qty Before</th>
                                <th>Qty Added/Reduce</th>
                                <th>Qty</th>
                                <th>Time</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($history as $index=>$item)
                            <tr>
                                <td>{{$index+1}}</td>
                                <td>{{$item->user->name}}</td>
                                <td>{{$item->qty}}</td>
                                <td>{{$item->qtyChange}}</td>
                                <td>{{$item->qty + $item->qtyChange}}</td>
                                <td>{{$item->created_at->diffForHumans()}}</td>
                                <td>{{$item->tipe}}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection