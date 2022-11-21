@extends('layouts.app')

@section('title', 'Produk')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-white">
                        <h4 class="font-weight-bold">Produk</h4>

                    </div>
                    <div class="card-body">
                        @if (Session::has('error'))
                            {{ $message }}
                        @endif
                        <form action="{{ url('/products') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="id">
                            <div class="form-group">
                                <x-form-input name="name" label="Nama" floating />
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="form-group">
                                        <x-form-input name="price" label="Harga" type="number" floating />
                                    </div>
                                    <div class="form-group">
                                        <label>Gambar</label>
                                        <div>
                                            <div class="custom-file">
                                                <br>
                                                <input name="image" id="image" type="file" class="custom-file-input" accept="image/*"
                                                    onchange="document.getElementById('output').src = window.URL.createObjectURL(this.files[0])"><label class="custom-file-label">Choose File</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-12"><img id="output" src="" class="img-fluid"></div>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <x-form-input name="qty" label="qty" type="number" floating />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <x-form-textarea name="description" label="description" cols="30" rows="10" floating />
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Submit Product</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
