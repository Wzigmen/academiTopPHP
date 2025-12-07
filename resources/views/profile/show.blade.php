@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Профиль пользователя</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3 text-center">
                            @if (Auth::user()->avatar)
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Аватар пользователя" class="img-fluid rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle" style="font-size: 8rem;"></i>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Имя</label>
                            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', Auth::user()->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', Auth::user()->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Изменить аватар (PNG)</label>
                            <input type="file" name="avatar" id="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/png">
                            @error('avatar')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Обновить профиль</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
