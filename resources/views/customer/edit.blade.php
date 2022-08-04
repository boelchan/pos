@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <form action="{{ route('customer.destroy', $customer->id ) }}" method="POST">
                        <label class="font-weight-bold">
                            <h4 class="font-weight-bold">Customer</h4>
                        </label>
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger btn-sm float-right"
                            onclick="return confirm('Apakah anda yakin menghapus data ini ?');">Delete customer</button>
                    </form>
                </div>
                <div class="card-body">
                    @if(Session::has('error'))
                    @include('layouts.flash-error',[ 'message'=> Session('error') ])
                    @endif
                    @if(Session::has('success'))
                    @include('layouts.flash-success',[ 'message'=> Session('success') ])
                    @endif
                    <form action="{{route('customer.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $customer->id }}">
                        <div class="form-group">
                            <label for="customer">Nama</label>
                            <input type="text" class="form-control" name="nama" value="{{ old('nama', $customer->nama) }}">
                            @include('layouts.error', ['name' => 'nama'])
                        </div>
                        <div class="form-group">
                            <label for="customer">telepon</label>
                            <input type="text" class="form-control" name="telepon" value="{{ old('telepon', $customer->telepon) }}">
                            @include('layouts.error', ['name' => 'telepon'])
                        </div>
                        <div class="form-group">
                            <label for="customer">alamat</label>
                            <textarea class="form-control" name="alamat" id="" cols="5" rows="10">{{ old('alamat', $customer->alamat) }}</textarea>
                            @include('layouts.error', ['name' => 'alamat'])
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Update customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection