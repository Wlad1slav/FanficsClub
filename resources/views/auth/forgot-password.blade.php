@extends('layouts.main')

@section('title')
    {{ $title }} :: Фанфіки українською
@endsection


@section('content')
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">

    <h1>{{ $title }}</h1>

    <form id="auth" method="post">
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

        <div>
            <input type="submit" value="Відновити">
        </div>

        @if(session('status'))
            <p class="alert-success">Ми відправили вам лист для відновлення паролю</p>
        @endif
    </form>

@endsection
