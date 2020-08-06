@extends('layouts.app')

@section('main')
    <link rel="stylesheet" href="{{asset('css/auth.css')}}">
    <h3><b>Авторизация</b></h3>
    <div class="auth">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div>
                <input type="text" name="login" id="inputLogin" placeholder="Логин" value="{{ old('login') }}" required autofocus>
                <i class="icon-user-8"></i>
            </div>
            <div>
                <input type="password" name="password" id="inputPassword" placeholder="Пароль">
                <i class="icon-lock-filled"></i>
            </div>
            <button id="login">Войти</button>
        </form>
    </div>
@endsection
