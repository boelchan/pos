@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card" style="min-height: 85vh">
                <div class="card-header bg-white">
                    <form action="{{ route('operators.index') }}" method="get">
                        <div class="row">  
                            <div class="col"><h4 class="font-weight-bold">Operator</h4></div>
                            <div class="col"><input type="text" name="search"
                                    class="form-control form-control-sm col-sm-10 float-right"
                                    placeholder="Search operator..." onblur="this.form.submit()"></div>
                            <div class="col-sm-2"><a href="{{ url('/operators/create')}}"
                                    class="btn btn-primary btn-sm float-right btn-block">Add Operator</a></div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    @if(Session::has('success'))
                    @include('layouts.flash-success',[ 'message'=> Session('success') ])
                    @endif
                    <div class="row">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Email</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($operators as $i => $operator)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td>{{ $operator->name }}</td>
                                    <td>{{ $operator->email }}</td>
                                    <td>
                                        <a href="{{ route('operators.edit', $operator->id) }}" class="btn btn-primary btn-block btn-sm">Details</a>
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
                <div>{{ $operators->links() }}</div>
            </div>
        </div>
    </div>
    @endsection

    @push('style')
    <style>
        .gambar {
            width: 100%;
            height: 175px;
            padding: 0.9rem 0.9rem
        }

        @media only screen and (max-width: 600px) {
            .gambar {
                width: 100%;
                height: 100%;
                padding: 0.9rem 0.9rem
            }
        }

    </style>
    @endpush