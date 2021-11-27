<?php
/**
 * Created by Marco Andrey Chmielewski Nunes
 * Date: 04/12/2017
 * Time: 16:45
 *
 * Last edited by Marco Andrey Chmielewski Nunes
 * Date: 04/12/2017
 * Time: 17:39
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
        <h3>Recupere Sua Senha</h3>
        <p>Preencha o campo abaixo para que possamos enviar o link para a recuperação da sua senha</p>
        <div class="formulario formulario-request">
            <form method="post" ng-submit="recuperar()">
                {{ csrf_field() }}
                <input id="token" ng-model="auth._token" hidden value="{{csrf_token()}}">
                <input id="login" name="login" placeholder="Login" ng-model="auth.login" required>
                <button class="laranja">Enviar</button>
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
    <script src="{{ url('js/vendor.js') }}"></script>
    <script src="{{ url('js/amChartsDirective.js') }}"></script>
    <script src="{{ url('js/app.js') }}"></script>
    <script src="{{ url('js/services.js') }}"></script>
    <script src="{{ url('js/auth.js') }}"></script>
@endsection