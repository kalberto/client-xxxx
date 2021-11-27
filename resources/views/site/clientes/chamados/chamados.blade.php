<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 01/12/2017
 * Time: 14:53
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/12/2017
 * Time: 14:53
 */
?>
@extends('site.clientes.cliente-structure')
@section('head')
    <title> Chamados | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="chamadosController">
        <header class="main-header">
            <div class="titulo">
                <h1>Meus Chamados</h1>
            </div>
        </header>
        <section class="chamados-ativos">
            <header>
                <h2>Chamados Ativos</h2>
            </header>
            @include('site/clientes/chamados/chamados-ativo-table', ['chamados' => 'chamados_ativos'])
            <div class="clear tabela-filtro"  ng-if="chamados_ativos.length != 0" ng-class="{'carregado': true, 'hide-block': carregando_ativos}">
                <div class="pag-2">
                    <label class="right" for="">
                        Página:
                        <div class="wrapper-select">
                            <select ng-model="page_ativos" ng-options="item for item in pages_ativos track by item" ng-change="setPageAtivos(null)"></select>
                        </div>
                    </label>
                    <span>@{{showing_ativos.primeiro}} - @{{showing_ativos.ultimo}} de @{{total_ativos}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: page_ativos == firstPage_ativos}" ng-click="setPageAtivos('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: page_ativos == lastPage_ativos}" ng-click="setPageAtivos('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
            <div class="align-right" ng-if="carregando_ativos == false" ng-class="{carregado: true}">
                <a class="botao" href="{{url('cliente/chamados/abrir-chamado')}}">Abrir Novo Chamado</a>
            </div>
        </section>
        <section class="chamados-historico">
            <header>
                <h2>Histórico de Atendimento</h2>
            </header>
            @include('site/clientes/chamados/chamados-table', ['chamados' => 'chamados'])
            <div class="clear tabela-filtro"  ng-if="chamados.length != 0" ng-class="{'carregado': true, 'hide-block': carregando_resolvidos}">
                <div class="pag-2">
                    <label class="right" for="">
                        Página:
                        <div class="wrapper-select">
                            <select ng-model="page_resolvidos" ng-options="item for item in pages_resolvidos track by item" ng-change="setPageResolvidos(null)"></select>
                        </div>
                    </label>
                    <span>@{{showing_resolvidos.primeiro}} - @{{showing_resolvidos.ultimo}} de @{{total_resolvidos}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: page_resolvidos == firstPage_resolvidos}" ng-click="setPageResolvidos('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: page_resolvidos == lastPage_resolvidos}" ng-click="setPageResolvidos('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/chamados.js') }}"></script>
@endsection