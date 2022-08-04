@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white">
                    <form action="{{ route('operators.destroy', $operator->id ) }}" method="POST">
                        <label class="font-weight-bold">
                            <h4 class="font-weight-bold">Edit Operator</h4>
                        </label>
                        @method('delete')
                        @csrf
                        <button class="btn btn-danger btn-sm float-right"
                            onclick="return confirm('Apakah anda yakin menghapus data ini ?');">Delete operator</button>
                    </form>
                </div>
                <div class="card-body">
                    @if(Session::has('error'))
                    @include('layouts.flash-error',[ 'message'=> Session('error') ])
                    @endif
                    @if(Session::has('success'))
                    @include('layouts.flash-success',[ 'message'=> Session('success') ])
                    @endif
                    <form action="{{route('operators.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $operator->id }}">
                        <div class="form-group">
                            <label for="operator">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name', $operator->name) }}">
                            @include('layouts.error', ['name' => 'name'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email', $operator->email) }}">
                            @include('layouts.error', ['name' => 'email'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Password</label>
                            <input type="password" class="form-control" name="password">
                            @include('layouts.error', ['name' => 'password'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation">
                            @include('layouts.error', ['name' => 'password_confirmation'])
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary btn-block">Update operator</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection