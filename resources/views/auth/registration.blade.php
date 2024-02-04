@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <h1>{{ $title }}</h1>

    <form id="auth" action="{{ route('RegistrationAction') }}" method="post">
        @csrf

        <label for="email">
            Пошта
            <input
                type="email"
                name="email"
                id="email"
                placeholder="ivan.melnik@gmail.com"
                class="{{ $errors->has('email') ? 'error' : '' }}"
                value="{{ old('email') }}"
                required>

            @error('email')
                <p class="error">{{ $message }}</p>
            @enderror
        </label>


        <label for="name">
            Псевдонім
            <input type="text"
                   name="name"
                   id="name"
                   placeholder="IvanMelnik"
                   class="{{ $errors->has('name') ? 'error' : '' }}"
                   value="{{ old('name') }}"
                   required>

            @error('name')
                <p class="error">{{ $message }}</p>
            @enderror
        </label>

        <label for="password">
            Пароль
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
                   placeholder="Як мінімум 8 символів"
                   min="8"
                   class="{{ $errors->has('password') ? 'error' : '' }}"
                   required>

            @error('password')
                <p class="error-password">{{ $message }}</p>
            @enderror
        </label>


        <div>
            <input type="submit" value="Зареєструватися">
        </div>

        <a href="{{ route('LoginPage') }}">Вже є акаунт?</a>

    </form>
@endsection
