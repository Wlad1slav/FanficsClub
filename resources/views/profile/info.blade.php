@extends('profile.layout')


@section('content')

    <div class="name-avatar">
        <img class="avatar large"
             src="{{ Auth::user()->image !== null ?
                            asset('storage/avatars/' . Auth::user()->image) :
                            asset('images/default_avatar.webp') }}"
             alt="avatar">

        <h1>{{ Auth::user()->name }}</h1>
    </div>

    <form action="{{ route('AvatarUploadAction') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file"
               name="avatar"
               id="avatar"
               accept=".jpg, .jpeg, .png, .webp"
               required>
        <input type="submit" value="Зберегти">
        @error('avatar')
        <p class="error">{{ $message }}</p>
        @enderror
    </form>

    <script>
        // Перевірка розміру завантаженної аватарки
        document.getElementById('avatar').addEventListener('change', function () {
            const maxSize = 1024 * 1024; // 1 MB
            const fileSize = this.files[0].size;

            if (fileSize > maxSize) {
                alert('Фото перевищує допустимий розмір 1 МБ.');
                this.value = ''; // Очистити вибір файлу
            }
        });
    </script>

@endsection
