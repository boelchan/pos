@extends('layouts.app')

@section('title', 'User')
@section('sub-title', 'Overview')

@section('content')
    <div class="container-xl">
        <div class="col-12">
            <div class="row row-cards">
                <div class="col-md-6 col-lg-3">
                    <div class="card border-primary">
                        <div class="card-body p-3 text-center">
                            <span class="avatar avatar-xl mb-3 avatar-rounded bg-indigo">{{ $user->acronym }}</span>
                            <h3 class="m-0 mb-1"><a href="#">{{ $user->name }}</a></h3>
                            <div class="text-muted">{{ $user->email }}</div>
                            <div class="mt-2">
                                <span class="badge bg-indigo-lt">{{ Str::upper($user->roles->first()->name) }}</span>
                            </div>
                        </div>
                        @if ($user->isBanned())
                            <div class="ribbon bg-red"> Akun Diblokir </div>
                        @else
                            <div class="ribbon bg-success"> Aktif </div>
                        @endif
                        <div class="d-flex ">
                            <a href="{{ route('user.edit', [$user->id, 'uuid' => $user->uuid]) }}" class="card-btn p-2 text-success">
                                <i class="ti ti-edit fs-2 me-1"></i> Edit
                            </a>
                            <a href="javascript:void(0)" class="card-btn p-2 delete-data text-red" data-url="{{ route('user.destroy', [$user->id, 'uuid' => $user->uuid]) }}"
                                data-token="{{ csrf_token() }}" data-label="{{ $user->name }}">
                                <i class="ti ti-trash fs-2 me-1"></i> Hapus
                            </a>

                            @if (auth()->user()->hasRole('superadmin'))
                                @if (!$user->hasRole('superadmin'))
                                    @if ($user->isBanned())
                                        <a href="javascript:void(0)" class="card-btn p-2 block-user bg-red" data-url="{{ route('user.banned', [$user->id, 'unbanned', 'uuid' => $user->uuid]) }}"
                                            data-token="{{ csrf_token() }}" data-label="Aktifkan {{ $user->name }}">
                                            <i class="ti ti-x fs-2 me-1"></i> Aktifkan
                                        </a>
                                    @else
                                        <a href="javascript:void(0)" class="card-btn p-2 block-user bg-warning" data-url="{{ route('user.banned', [$user->id, 'banned', 'uuid' => $user->uuid]) }}"
                                            data-token="{{ csrf_token() }}" data-label="Blokir {{ $user->name }}">
                                            <i class="ti ti-x fs-2 me-1"></i> Blokir
                                        </a>
                                    @endif
                                @endif
                            @endif
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
