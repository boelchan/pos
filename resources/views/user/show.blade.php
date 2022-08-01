@extends('layouts.app')

@section('title', 'User')
@section('sub-title', 'Overview')

@section('content')
<div class="container-xl">
    <div class="col-12">
        <div class="row row-cards">
            <div class="col-md-6 col-lg-3">
                <div class="card border-primary">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 avatar-rounded bg-indigo">{{ $user->acronym }}</span>
                        <h3 class="m-0 mb-1"><a href="#">{{ $user->name }}</a></h3>
                        <div class="text-muted">{{ $user->email }}</div>
                        <div class="mt-3">
                            <span class="badge bg-indigo-lt">{{ Str::upper($user->roles->first()->name) }}</span>
                        </div>
                    </div>
                    <div class="d-flex">
                        <a href="javascript:void(0)" class="card-btn delete-user" data-url="{{ route('user.destroy', $user->id) }}"
                            data-token="{{ csrf_token() }}" data-label="{{ $user->name }}">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-trash"
                                width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <line x1="4" y1="7" x2="20" y2="7"></line>
                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12"></path>
                                <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3"></path>
                            </svg>&nbsp;
                            Hapus
                        </a>
                        <a href="{{ route('user.edit', $user->id) }}" class="card-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-edit" width="20"
                                height="20" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                stroke-linecap="round" stroke-linejoin="round">
                                <desc>Download more icon variants from https://tabler-icons.io/i/edit</desc>
                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1"></path>
                                <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"></path>
                                <path d="M16 5l3 3"></path>
                            </svg>&nbsp;
                            Edit
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-9">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Riwayat Login</div>
                    </div>
                    <div class="card-table">
                        {{ $dataTable->table(['class' => 'table table-hover w-100 border-bottom']) }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('page-script')
{{ $dataTable->scripts() }}
@endsection