@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-center align-items-center" style="height: 80vh;">
        <div class="text-center">
            <h1>Добро пожаловать в academiTOP</h1>
            <p class="lead">Место для ваших новостей.</p>
            @guest
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg">Войти</a>
                <a href="{{ route('register.view') }}" class="btn btn-secondary btn-lg">Зарегистрироваться</a>
            @else
                <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg">Перейти к новостям</a>
            @endguest
        </div>
    </div>
</div>
@endsection