<?php
/**
 * Created by Yeshua Emanuel Braz.
 * Date: 17/01/2018
 * Time: 17:47
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 16/02/2017
 * Time: 15:24
 */
?>

@extends('site/clientes/cliente-structure')
@section('head')
    <title>FAQ | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="servicosController">
        <header class="main-header">
            <div class="titulo">
                <h1 class="destaque">FAQ</h1>
                <span class="subtitulo">{{$nome}}</span>
            </div>
        </header>
        <section>
            <div class="faq">
                <dl>
                    @foreach($faqs as $key => $faq)
                        <dt>
                            <label for="resposta-{{$key}}">
                                <span>{{$faq['pergunta']}}</span>
                            </label>
                        </dt>
                        <dd>
                            <input id="resposta-{{$key}}" type="checkbox" hidden>
                            <div class="wrapper">
                                <div>
                                    {!! $faq['resposta']!!}
                                </div>
                            </div>
                        </dd>
                    @endforeach
                </dl>
            </div>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/servicos.js') }}"></script>
@endsection
