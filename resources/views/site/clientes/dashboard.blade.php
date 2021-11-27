<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 01/12/2017
 * Time: 14:08
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 01/12/2017
 * Time: 14:08
 */
?>
@extends('site/clientes/cliente-structure')
@section('head')
    <title>xxxx | Área do Cliente</title>
    <meta name="description" content="">
@endsection

@section('header')

@endsection

@section('content')
    <div ng-controller="dashboardController">
        <header class="main-header">
            <div class="titulo">
                <h1>Área do Cliente</h1>
            </div>
        </header>
        @includeWhen($servicos, 'site.clientes.dashboard.servicos')

        @includeWhen($chamados,'site.clientes.dashboard.chamados')

        @includeWhen($faturas,'site.clientes.dashboard.faturas')

        @includeWhen($contratos,'site.clientes.dashboard.contratos')

    </div>
@endsection

@section('script')
    <script src="{{ url('js/dashboard.js') }}"></script>
@endsection