@extends('layouts.app')

@section('title', 'User')
@section('sub-title', 'Tambah')

@section('content')
<div class="container-xl">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Tambah User</h3>
            </div>
            <div class="card-body">
                <div class="col-md-6">
                    <x-form :action="route('user.store')">
                        <x-form-input name="name" label="Nama" floating class="mb-1"/>
                        <x-form-select name="role" :options="$roleOption" label="Role" placeholder="Pilih Role" floating class="mb-1"/>
                        <x-form-input name="email" label="Email" floating class="mb-1"/>
                        <x-form-input name="password" type="password" label="Password" floating class="mb-1"/>
                        <x-form-input name="password_confirmation" type="password" label="Konfirmasi Password" floating class="mb-1"/>
                        <x-form-submit class="mt-3">Simpan</x-form-submit>
                    </x-form>
                </div>
            </div>
    </div>
</div>
@endsection