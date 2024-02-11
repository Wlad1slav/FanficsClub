@extends('fanfic-edit.layout')


@section('content')

    <h1>Користувачі, які мають доступ</h1>

    <div style="display: flex; align-items: baseline;">
        <div>
            <form action="{{ route('GiveAccessAction', ['ff_slug' => $fanfic->slug, 'right' => 'coauthor']) }}" method="post">
                @csrf
                <h3>Додати співавтора</h3>
                <label for="email">Пошта</label>
                <input type="email" name="email" id="email">
                <input type="submit" value="Додати">
            </form>

            <form action="{{ route('GiveAccessAction', ['ff_slug' => $fanfic->slug, 'right' => 'editor']) }}" method="post">
                @csrf
                <h3>Додати редактора</h3>
                <label for="email">Пошта</label>
                <input type="email" name="email" id="email">
                <input type="submit" value="Додати">
                @error('email')
                    <p class="error">{{ $message }}</p>
                @enderror
            </form>
        </div>

        <table style="margin-left: var(--indent-medium);">
            <caption>
                Користувачі, що мають доступ до фанфіка {{ $fanfic->name }}
            </caption>

            <thead>
                <tr>
                    <th>Користувач</th>
                    <th>Статус</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users_with_access as $user)
                    <tr>
                        <th>{{ $user->name }}</th>
                        <td>{{ $fanfic_access[$user->id] == 'coauthor' ? 'Співавтор' : 'Редактор' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>

@endsection
