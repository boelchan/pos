@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white"><h4 class="font-weight-bold">Customer</h4>

                </div>
                <div class="card-body">
                    @if(Session::has('error'))
                        @include('layouts.flash-error',[ 'message'=> Session('error') ])
                    @endif
                    <form action="{{route('customer.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="Customer">Nama</label>
                            <input type="text" class="form-control" name="nama" value="{{ old('nama') }}">
                            @include('layouts.error', ['name' => 'name'])
                        </div>
                        <div class="form-group">
                            <label for="Customer">Telepon</label>
                            <input type="text" class="form-control" name="telepon" value="{{ old('telepon') }}">
                            @include('layouts.error', ['name' => 'telepon'])
                        </div>
                        <div class="form-group">
                            <label for="Customer">Alamat</label>
                            <textarea class="form-control" name="alamat" id="" cols="5" rows="10">{{ old('alamat') }}</textarea>
                            @include('layouts.error', ['name' => 'alamat'])
                        </div>


                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Submit Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection