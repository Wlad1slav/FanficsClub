@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <h1>{{ $title }}</h1>

    <form id="auth" action="{{ route('LoginAction') }}" method="post">
        @csrf

        <label for="email">
            Пошта
            <input
                type="email"
                name="email"
                id="email"
                placeholder="ivan.melnik@gmail.com"
                class="{{ $errors->has('error') ? 'error' : '' }}"
                value="{{ old('email') }}"
                required>

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </label>

        <label for="password">
            Пароль
            <input type="password"
                   name="password"
                   id="password"
                   placeholder="********"
                   class="{{ $errors->has('error') ? 'error' : '' }}"
                   value="{{ old('password') }}"
                   required>

            @error('password')
                <p class="error-password">{{ $message }}</p>
            @enderror

            @error('error')
                <p class="error-password">{{ $message }}</p>
            @enderror

            <div class="additional">
                <label for="remember">
                    <input type="checkbox" name="remember" id="remember">
                    Запам'ятати мене
                </label>
                <a href="#">Забули пароль?</a>
            </div>
        </label>

        <div>
            <input type="submit" value="Увійти">
        </div>

    </form>
@endsection
