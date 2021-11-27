<?php
/**
 * Created by Yeshua Emanuel Braz.
 * Date: 17/01/2018
 * Time: 17:47
 *
 * Last edited by Yeshua Emanuel Braz.
 * Date: 17/01/2018
 * Time: 17:47
 */
?>

@extends('site/clientes/cliente-structure')
@section('head')
    <title>Primeiro Acesso | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div class="primeiro-acesso">
        <header class="main-header">
            <div class="titulo">
                <h1>Oi, (nome)</h1>
            </div>
        </header>
        <section>
            <p><em>Seja bem-vindo!</em> Esse é o seu primeiro acesso na Área do Cliente xxxx.</p>
            <p>Durante seu cadastro, o sistema definiu uma senha padrão e aleatória para você. Aproveite essa oportunidade para personalizar sua senha e definir algo que você possa lembrar com facilidade no futuro.</p>
            <form action="">
                <ul class="clearfix">
                    <li class="grid6">
                        <input type="password" name="senha" placeholder="Senha">
                    </li>
                    <li class="grid6">
                        <input type="password" name="senha" placeholder="Repetir Senha">
                    </li>
                    <li class="text-center">
                        <a href="#" class="botao">Alterar Senha</a>
                    </li>
                </ul>
            </form>
        </section>

        <div class="popup">
            <div class="popup-content">
                <h3>Sucesso!</h3>
                <p>Sua nova senha foi cadastrada</p>
                <a href="#" class="botao">OK</a>
            </div>
        </div>

        <div class="popup">
            <div class="popup-content">
                <h3>Falha!</h3>
                <p>Alguma deu errado.</p>
                <a href="#" class="botao">Tentar Novamente</a>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script src="{{ url('js/servicos.js') }}"></script>
@endsection
