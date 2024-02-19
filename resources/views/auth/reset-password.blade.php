@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <h1>{{ $title }}</h1>

    <form id="auth" method="post" novalidate>
        @csrf

        <input type="hidden" name="token" value="{{ $request->token }}">

{{--        <label for="email">--}}
{{--            Пошта--}}
{{--            <input--}}
{{--                type="email"--}}
{{--                name="email"--}}
{{--                id="email"--}}
{{--                placeholder="ivan.melnik@gmail.com"--}}
{{--                class="{{ $errors->has('email') ? 'error' : '' }}"--}}
{{--                value="{{ $request->email }}"--}}
{{--                required>--}}

{{--            @error('email')--}}
{{--                <p class="error">{{ $message }}</p>--}}
{{--            @enderror--}}
{{--        </label>--}}

        <label for="password">
            Новий пароль
            <input type="password"
                   name="password"
                   id="password"
                   placeholder="Як мінімум 8 символів"
                   min="8"
                   class="{{ $errors->has('password') ? 'error' : '' }}"
                   value="{{ old('password') }}"
                   required>
        </label>

        <label for="password_confirmation">
            Підтвердження паролю
            <input type="password"
                   name="password_confirmation"
                   id="password_confirmation"
                   min="8"
                   class="{{ $errors->has('password') ? 'error' : '' }}"
                   required>

            @error('password')
            <p class="error-password">{{ $message }}</p>
            @enderror
        </label>

        <div>
            <input type="submit" value="Змінити">
        </div>

        @if(session('status'))
            <p class="alert-success">Ми відправили вам лист для відновлення паролю.</p>
        @endif



    </form>
@endsection
