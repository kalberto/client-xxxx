<?php
/**
 * Created by Yeshua Emanuel Braz.
 * Date: 17/01/2018
 * Time: 17:47
 *
 * Last edited by Alberto de Almeida Guilherme.
 * Date: 29/01/2018
 * Time: 15:08
 */
?>

@extends('site/clientes/cliente-structure')
@section('head')
    <title>Meu Perfil | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="perfilController">
        <header class="main-header">
            <div class="titulo">
                <h1>Meu Perfil</h1>
            </div>
        </header>
        <section>
            <header>
                <h2>Dados Pessoais</h2>
            </header>
            <form class="w525" ng-submit="editarEndereco()">
                <ul class="clearfix">
                    <li class="grid6 foto">
                        <label>
                            <span class="texto">
                                <span>Clique para trocar sua foto</span>
                            </span>
                            @if($usuario['media'] == null)
                                <img src="{{url('assets/images/cliente/ico-usuario-semfoto-grande.jpg')}}" alt="Foto do Usuário" ng-if="!hasTempMedia">
                            @else
                                <img src="{{url($usuario['media']->media_root->path.$usuario['media']->nome )}}" alt="Foto do Usuário" ng-if="!hasTempMedia">
                            @endif
                            <img src="@{{tempMedia.src}}" alt="Foto do Usuário" ng-if="hasTempMedia">
                            <input type="file" name="fileT" accept="image/*" ngf-select="enviarArquivo($file)" hidden>
                        </label>
                    </li>
                    <li class="grid2">
                        <label>
                            <span>Nome</span>
                            <input type="text" placeholder="Seu nome" ng-model="usuario.nome" ng-init="usuario.nome ='{{$usuario["nome"]}}'" required>
                        </label>
                    </li>
                    <li class="grid4">
                        <label>
                            <span>Sobrenome</span>
                            <input type="text" placeholder="Seu sobrenome" ng-model="usuario.sobrenome" ng-init="usuario.sobrenome ='{{$usuario["sobrenome"]}}'" required>
                        </label>
                    </li>
                    <li class="grid2">
                        <label>
                            <span>Telefone</span>
                            <input type="tel" placeholder="(41) 99999-9999" ng-model="usuario.telefone" ng-init="usuario.telefone='{{$usuario["telefone"]}}'">
                        </label>
                    </li>
                    <li class="grid4">
                        <label>
                            <span>E-mail</span>
                            <input type="email" placeholder="seu@email.com.br" value="{{$usuario['email']}}" disabled>
                        </label>
                    </li>
                    <li class="grid3">
                        <label>
                            <span>Cep</span>
                            <input type="text" placeholder="000000000" ng-model="usuario.endereco.cep" ng-init="usuario.endereco.cep='{{$usuario["endereco"]["cep"]}}'" ng-change="autocompletePorCep()" required>
                        </label>
                    </li>
                    <li class="grid1">
                        <label>
                            <span>UF</span>
                            <span class="select-wrapper">
                                <select name="uf" id="" ng-model="usuario.endereco.uf">
                                    @foreach($ufs as $uf)
                                        <option value="{{$uf->uf}}" @if($usuario['endereco']['uf_id'] == $uf->id) ng-init="usuario.endereco.uf='{{$uf->uf}}'" @endif>{{$uf->uf}}</option>
                                    @endforeach
                                </select>
                            </span>
                        </label>
                    </li>
                    <li class="grid2">
                        <label>
                            <span>Cidade</span>
                            <input type="text" ng-model="usuario.endereco.cidade" ng-init="usuario.endereco.cidade='{{$usuario["endereco"]["cidade"]}}'">
                        </label>
                    </li>
                    <li class="grid4">
                        <label>
                            <span>Endereço</span>
                            <input type="text" ng-model="usuario.endereco.endereco" ng-init="usuario.endereco.endereco='{{$usuario["endereco"]["endereco"]}}'">
                        </label>
                    </li>
                    <li class="grid2">
                        <label>
                            <span>Número</span>
                            <input type="text" ng-model="usuario.endereco.numero" ng-init="usuario.endereco.numero='{{$usuario["endereco"]["numero"]}}'">
                        </label>
                    </li>
                    <li class="grid2">
                        <label>
                            <span>Complemento</span>
                            <input type="text" ng-model="usuario.endereco.complemento" ng-init="usuario.endereco.complemento='{{$usuario["endereco"]["complemento"]}}'">
                        </label>
                    </li>
                    <li class="grid4">
                        <label>
                            <span>Bairro</span>
                            <input type="text" ng-model="usuario.endereco.bairro" ng-init="usuario.endereco.bairro='{{$usuario["endereco"]["bairro"]}}'">
                        </label>
                    </li>
                    <li class="right">
                        <button id="botao_editar" class="botao mw150">Enviar</button>
                    </li>
                </ul>
            </form>
        </section>

        <section>
            <header>
                <h2>Dados de acesso</h2>
            </header>
            <form class="w525" ng-submit="editarSenha()">
                <ul class="clearfix">
                    <li class="grid6">
                        <label>
                            <span>Login</span>
                            <input type="text" value="{{$usuario['login']}}" disabled>
                        </label>
                    </li>
                    <li class="grid6">
                        <label>
                            <span>Senha Atual</span>
                            <input type="password" ng-model="auth.password" placeholder="Digite sua senha atual">
	                        <span ng-repeat="item in errors.password" ng-cloak style="color: red;font-size: 1.2rem;text-align: right;">@{{item}}</span>
                        </label>
                    </li>
                    <li class="grid6">
                        <label>
                            <span>Nova Senha</span>
                            <input type="password" ng-model="auth.new_password" placeholder="Digite a nova senha">
	                        <span ng-repeat="item in errors.new_password" ng-cloak style="color: red;font-size: 1.2rem;text-align: right;">@{{item}}</span>
                        </label>
                    </li>
                    <li class="grid6">
                        <label>
                            <span>Confirme a nova senha</span>
                            <input type="password" ng-model="auth.new_password_confirmation" placeholder="Digite a nova senha novamente">
	                        <span ng-repeat="item in errors.new_password_confirmation" ng-cloak style="color: red;font-size: 1.2rem;text-align: right;">@{{item}}</span>
                        </label>
                    </li>
                    <li class="right">
                        <button id="botao_senha" class="botao mw150">Enviar</button>
                    </li>
                </ul>
            </form>
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/perfil.js') }}"></script>
@endsection
