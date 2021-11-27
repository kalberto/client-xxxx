<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 29/11/2017
 * Time: 15:34
 *
 * Last edited by Marco Andrey Chmielewski Nunes
 * Date: 04/12/2017
 * Time: 17:00
 */
?>
@extends('site/structure')

@section('head')
    <title>xxxx | Cliente</title>
    <meta name="description" content="">
    <link rel="stylesheet" href="{{ url('css/area-cliente.css') }}">
@endsection

@section('header')
    <picture class="picture-mob">
        <source srcset="{{url('assets/images/cliente/bg-cliente-login-479x400.jpg')}}" media="(max-width: 479px)">
        <source srcset="{{url('assets/images/cliente/bg-cliente-login-667x550.jpg')}}" media="(max-width: 667px)">
        <source srcset="{{url('assets/images/cliente/bg-cliente-login-768x550.jpg')}}" media="(max-width: 768px)">
        <source srcset="{{url('assets/images/cliente/bg-cliente-login.jpg')}}">
        <img src="{{url('assets/images/cliente/bg-cliente-login.jpg')}}" alt="Área do Cliente"/>
    </picture>
@endsection

@section('content')
    <main class="wrapper conteudo-auth" ng-controller="authController">
        <div class="formulario formulario-alteracao">
            <h3>Nova Senha</h3>
            <form method="post" ng-submit="alterarSenha()">
                {{ csrf_field() }}
                <input id="token" ng-model="auth._token" hidden value="{{csrf_token()}}">
                <input id="password" ng-model="auth.password" placeholder="Senha" type="password" required>
                <input id="password_confirm" ng-model="auth.password_confirmation" placeholder="Confirme sua nova senha" type="password" required>
                <button class="laranja">Entrar</button>
            </form>
        </div>
        
        <div class="popup pop-auth" ng-class="{'ativo': popup.ativo}">
            <div class="pelicula"></div>
            <div class="popup-content">
                <p class="titulo-pop">@{{popup.titulo}}</p>
                <p>@{{popup.mensagem}}</p>
                <button class="botao pop" ng-click="fecharPopup()">@{{popup.botao}}</button>
            </div>
        </div>
    </main>
@endsection

@section('script')
    <script> var token_usr ='{{$token}}';</script>
    <script src="{{ url('js/vendor.js') }}"></script>
    <script src="{{ url('js/amChartsDirective.js') }}"></script>
    <script src="{{ url('js/app.js') }}"></script>
    <script src="{{ url('js/services.js') }}"></script>
    <script src="{{ url('js/auth.js') }}"></script>
@endsection