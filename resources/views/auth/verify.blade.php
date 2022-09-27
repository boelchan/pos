@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                {{-- <div class="card-header">{{ __('Verify Your Email Address') }}</div> --}}
                <div class="card-header">Verifikasi email Anda</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{-- {{ __('A fresh verification link has been sent to your email address.') }} --}}
                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                        </div>
                    @endif

                    {{-- {{ __('Before proceeding, please check your email for a verification link.') }} --}}
                    Sebelum melanjutkan, harap periksa email Anda kembali untuk melihat tautan verifikasi.

                    {{-- {{ __('If you did not receive the email') }}, --}}
                    <br>Jika Anda tidak menerima email
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">
                            {{-- {{ __('click here to request another') }} --}}
                            klik di sini untuk meminta verifikasi baru
                        </button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
