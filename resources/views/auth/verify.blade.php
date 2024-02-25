@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')
{{--    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">--}}

    <h1>{{ $title }}</h1>

    <p style="text-align: center;">На адресу <b>{{ $user->email }}</b> відправлена форма для підтвердження електроної пошти.</p>

    <p style="text-align: center;"><a href="{{ route('verification.resend') }}">Не прийшов лист?</a></p>

    @if (session('message'))
        <p class="alert-success" style="text-align: center;">
            {{ session('message') }}
        </p>
    @endif

@endsection


{{--@extends('layouts.app')--}}

{{--@section('content')--}}
{{--<div class="container">--}}
{{--    <div class="row justify-content-center">--}}
{{--        <div class="col-md-8">--}}
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Verify Your Email Address') }}</div>--}}

{{--                <div class="card-body">--}}
{{--                    @if (session('resent'))--}}
{{--                        <div class="alert alert-success" role="alert">--}}
{{--                            {{ __('A fresh verification link has been sent to your email address.') }}--}}
{{--                        </div>--}}
{{--                    @endif--}}

{{--                    {{ __('Before proceeding, please check your email for a verification link.') }}--}}
{{--                    {{ __('If you did not receive the email') }},--}}
{{--                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">--}}
{{--                        @csrf--}}
{{--                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}
{{--@endsection--}}
