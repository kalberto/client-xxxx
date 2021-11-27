<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:19
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */
?>
@extends('site/clientes/cliente-structure')
@section('head')
    <title>Meus Contratos | xxxx</title>
    <meta name="description" content="">
@endsection

@section('header')

@endsection

@section('content')
    <div ng-controller="contratosController">
        <header class="main-header">
            <div class="titulo">
                <h1>Meus Contratos</h1>
            </div>
        </header>
        <section>
            <header>
                <h2>Contratos Vigentes</h2>
            </header>
            @include('site.clientes.contratos.contratos-vigentes-table')
            <div class="clear tabela-filtro" ng-class="{'carregado': true, 'hide-block': carregando_contratos_vigentes}">
                <div class="pag-2">
                    <label class="right" for="">
                        Página:
                        <div class="wrapper-select">
                            <select ng-model="page_vigentes" ng-options="item for item in pages_vigentes track by item" ng-change="setPage(null)"></select>
                        </div>
                    </label>
                    <span>@{{showing_vigentes.primeiro}} - @{{showing_vigentes.ultimo}} de @{{total_vigentes}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: page_vigentes == firstPage_vigentes}" ng-click="setPageVigentes('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: page_vigentes == lastPage_vigentes}" ng-click="setPageVigentes('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/contratos.js') }}"></script>
@endsection
