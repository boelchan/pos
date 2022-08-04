@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-white"><h4 class="font-weight-bold">Operator</h4>

                </div>
                <div class="card-body">
                    @if(Session::has('error'))
                        @include('layouts.flash-error',[ 'message'=> Session('error') ])
                    @endif
                    <form action="{{route('operators.store')}}" method="POST">
                        @csrf
                        <input type="hidden" name="id">
                        <div class="form-group">
                            <label for="operator">Nama</label>
                            <input type="text" class="form-control" name="name" value="{{ old('name') }}">
                            @include('layouts.error', ['name' => 'name'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ old('email') }}"">
                            @include('layouts.error', ['name' => 'email'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Password</label>
                            <input type="password" class="form-control" name="password" value="{{ old('password') }}">
                            @include('layouts.error', ['name' => 'password'])
                        </div>
                        <div class="form-group">
                            <label for="operator">Konfirmasi Password</label>
                            <input type="password" class="form-control" name="password_confirmation" value="{{ old('password_confirmation') }}">
                            @include('layouts.error', ['name' => 'password_confirmation'])
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