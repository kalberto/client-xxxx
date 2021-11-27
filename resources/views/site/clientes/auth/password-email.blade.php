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
    <link rel="stylesheet" href="{{ url('css/clientes/area_cliente.css') }}">
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
    <main class="conteudo conteudo-login">
        
    </main>
@endsection