<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:13
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/12/2017
 * Time: 14:13
 */
?>
@extends('site/clientes/cliente-structure')
@section('head')
    <title>Minhas Faturas | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="faturasController">
        <header class="main-header">
            <div class="titulo">
                <h1>Pagamentos</h1>
            </div>
        </header>
        <section class="faturas-ultimas">
            <header>
                <h2>Faturas Pendentes</h2>
            </header>
            @include('site.clientes.faturas.faturas-table', ['faturas' => 'ultimas_faturas','carregando' => 'carregando_ultimas_faturas'])
            <div class="clear tabela-filtro"  ng-if="ultimas_faturas.length != 0" ng-class="{'carregado': true, 'hide-block': carregando_ultimas_faturas}">
                <div class="pag-2">
                    <label class="right" for="">
                        Página:
                        <div class="wrapper-select">
                            <select ng-model="selected.pageUlt" ng-options="item for item in pagesUlt track by item" ng-change="setPageUlt(null)"></select>
                        </div>
                    </label>
                    <span>@{{showingUlt.primeiro}} - @{{showingUlt.ultimo}} de @{{totalUlt}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: selected.pageUlt == firstPageUlt}" ng-click="setPageUlt('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: selected.pageUlt == lastPageUlt}" ng-click="setPageUlt('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
        </section>
        <section class="faturas-anteriores">
            <header>
                <h2 class="class_bot">Faturas Anteriores</h2>
                <div class="filtros periodo" >
                    <label class="right" for="">
                        Período de
                        <input type="date" ng-model="start">
                        à
                        <input type="date" ng-model="end">
                        <button ng-click="getPeriodo(start,end)">
                            @icon('ico-filtro-data')
                        </button>
                    </label>
                </div>
            </header>
            @include('site.clientes.faturas.faturas-table', ['faturas' => 'faturas_anteriores','carregando' => 'carregando_faturas'])
            <div class="clear tabela-filtro"  ng-if="faturas_anteriores.length != 0" ng-class="{'carregado': true, 'hide-block': carregando_faturas}">
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
        <div id="nao_implementado" class="popup" ng-if="popup_NaoImplementado" ng-class="{ativo: popup_NaoImplementado}">
            <div class="popup-content">
                <p>Este recurso ainda não está disponível!</p>
                <button class="botao" ng-click="fecharPopup('nao_implementado')">Fechar</button>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/faturas.js') }}"></script>
@endsection
