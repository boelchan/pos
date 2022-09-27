@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
<div class="container-xl">
    <div class="col-12">
        <div class="row row-cards">
            <div class="col-md-6 col-lg-3">
                <div class="card border-primary">
                    <div class="card-body p-4 text-center">
                        <span class="avatar avatar-xl mb-3 avatar-rounded bg-indigo">{{ $user->acronym }}</span>
                        <h2 class="m-0 "><a href="#">{{ $user->name }}</a></h2>
                        <div class="">{{ $user->email }}</div>
                        <div class="mt-3">
                            <span class="badge bg-indigo-lt">{{ Str::upper($user->roles->first()->name) }}</span>
                        </div>
                    </div>

                </div>
            </div>
            <div class="col-md-6 col-lg-9">
                <div class="card">
                    <ul class="nav nav-tabs" data-bs-toggle="tabs">
                        <li class="nav-item">
                            <a href="#tabs-profile" class="nav-link active" data-bs-toggle="tab">
                                <i class="ti ti-user fs-3"></i> &nbsp;Profil</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-password" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-key fs-3"></i> &nbsp;Password</a>
                        </li>
                        <li class="nav-item">
                            <a href="#tabs-riwayat" class="nav-link" data-bs-toggle="tab">
                                <i class="ti ti-history fs-3"></i> &nbsp;Riwayat Login</a>
                        </li>
                    </ul>
                    <div class="card-body">
                        <div class="tab-content">
                            @if(Session::has('message'))
                            <div class="alert alert-important alert-success alert-dismissible" role="alert">
                                <div class="d-flex">
                                    <div>
                                        {{ Session::get('message') }}
                                    </div>
                                </div>
                                <a class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></a>
                            </div>
                            @endif
                            <div class="tab-pane active show" id="tabs-profile">
                                <div class="col-md-6">
                                    <x-form :action="route('profile.store')">
                                        @bind($user)
                                        <x-form-input name="name" label="Nama" floating class="mb-1" />
                                        <x-form-input name="email" label="Email" floating class="mb-1" />
                                        @endbind
                                        <x-form-submit class="mt-3">Simpan Profil</x-form-submit>
                                    </x-form>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-password">
                                <div class="col-md-6">
                                    <x-form :action="route('profile.change-password', $user->id)">
                                        <x-form-input name="password_old" type="password" label="Password Lama" floating class="mb-1" />
                                        <x-form-input name="password" type="password" label="Password Baru" floating class="mb-1" />
                                        <x-form-input name="password_confirmation" type="password" label="Ulangi Password Baru" floating class="mb-1" />
                                        <x-form-submit class="mt-3">Ubah Password</x-form-submit>
                                    </x-form>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-riwayat">
                                <div>
                                    @forelse ($user->lastLogin as $l)
                                    <div class="alert alert-{{ $l->login_successful ? 'success' : 'danger' }}" role="alert">
                                        <h4 class="alert-title">
                                            @if ($l->login_at)
                                            {{ $l->login_at->diffForHumans() }}
                                            @endif
                                        </h4>
                                        <div class="text-muted">{{ browser_agent($l->user_agent) }} - {{ $l->login_at }}</div>
                                    </div>
                                    @empty
                                    <div class="alert alert-info" role="alert">
                                        <div class="text-muted">Anda belum pernah login</div>
                                    </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection