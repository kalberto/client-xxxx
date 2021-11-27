<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:18
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/12/2017
 * Time: 14:18
 */
?>
@extends('site/clientes/cliente-structure')
@section('head')
    <title>Contato | xxxx</title>
    <meta name="description" content="">
@endsection

@section('header')

@endsection

@section('content')
    <header class="main-header">
        <div class="titulo">
            <h1>Entre em Contato</h1>
        </div>
    </header>

    <section ng-controller="contatoController">
        <form class="contato" action="">
            <ul class="clearfix w525">
                <li class="grid6">
                    <label ng-cloak ng-if="!carregando">
                        <span>Serviço</span>
                        <span class="select-wrapper">
                            <select name="servico" id="servico" ng-model="form.servico">
                                <option ng-repeat="item in servicos" value="@{{item}}">@{{item}}</option>
                            </select>
                        </span>
                    </label>
                </li>
                <li class="grid6">
                    @include('site/shared/clientes-loader',['carregando' => 'carregando'])
                    <label ng-cloak ng-if="!carregando">
                        <span>Assunto</span>
                        <span class="select-wrapper">
                            <select name="assunto" id="assunto" ng-model="form.assunto" ng-change="getDescriptions(form.assunto)">
                                <option ng-repeat="item in assuntos" value="@{{item}}">@{{item}}</option>
                            </select>
                        </span>
                    </label>
                </li>
                <li class="grid6">
                    <label ng-cloak ng-if="!carregando">
                        <span>Descrição</span>
                        <span class="select-wrapper">
                            <select name="assunto" id="assunto" ng-model="form.description">
                                <option ng-repeat="item in descriptions" value="@{{item.id}}">@{{item.name}}</option>
                            </select>
                        </span>
                    </label>
                </li>
                <li class="grid6">
                    <label ng-cloak ng-if="!carregando">
                        <span>Nota</span>
                        <textarea name="nota" id="nota" ng-model="form.nota" rows="6"></textarea>
                    </label>
                </li>
                <li>
                    <a class="botao mw150" ng-click="postContato()">Enviar</a>
                </li>
            </ul>

            <div class="dib telefones">
                <p class="destaque">0800 604 3939</p>
                <p>(41) 3318-7777 - Curitiba</p>
                <p>(11) 3318-7777 - São Paulo</p>
                <p>(12) 3308-9008 - São José dos Campos</p>
            </div>

        </form>
        <div class="popup pop-auth" ng-class="{'ativo': popup.ativo}">
            <div class="pelicula"></div>
            <div class="popup-content">
                <p class="titulo-pop">@{{popup.titulo}}</p>
                <p>@{{popup.mensagem}}</p>
                <button class="botao pop" ng-click="fecharPopup()">@{{popup.botao}}</button>
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script src="{{ url('js/contato.js') }}"></script>
@endsection
