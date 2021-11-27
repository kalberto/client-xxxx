<?php
/**
 * Created by Yeshua Emanuel Braz.
 * Date: 17/01/2018
 * Time: 17:47
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/02/2017
 * Time: 15:20
 */
?>

@extends('site/clientes/cliente-structure')
@section('head')
    <title>Manuais | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="servicosController">
        <header class="main-header">
            <div class="titulo">
                <h1 class="destaque">Manuais</h1>
                <span class="subtitulo">{{$nome}}</span>
            </div>
        </header>
        <section>
            <table class="default manuais">
                <tbody>
                @foreach($manuais as $manual)
                    <tr>
                        <td>{{$manual['nome']}}</td>
                        <td class="botoes">
                            <a href="{{$manual['url']}}" class="botao" target="_blank">Download</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/servicos.js') }}"></script>
@endsection
