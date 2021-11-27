<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 01/12/2017
 * Time: 14:28
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/12/2017
 * Time: 14:28
 */
?>

@extends('site/clientes/cliente-structure')
@section('head')
    <title>Meus Serviços | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="servicosController">
        <header class="main-header">
            <div class="titulo">
                <h1>Meus Serviços</h1>
            </div>
        </header>
        <section>
            <header>
                <h2>Serviços Contratados</h2>
            </header>
            @include('site/clientes/servicos/servicos-table')
            <div class="clear tabela-filtro"  ng-if="servicos.length != 0 && carregando == false" ng-class="{'carregado': true, 'hide-block': carregado}">
                <div class="pag-2">
                    <label class="right" for="">
                        Página:
                        <div class="wrapper-select">
                            <select ng-model="selected.page" ng-options="item for item in pages track by item" ng-change="setPage(null)"></select>
                        </div>
                    </label>
                    <span>@{{showing.primeiro}} - @{{showing.ultimo}} de @{{total}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: selected.page == firstPage}" ng-click="setPage('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: selected.page == lastPage}" ng-click="setPage('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/servicos.js') }}"></script>
@endsection
