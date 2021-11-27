@extends('site.clientes.cliente-structure')
@section('head')
    <title> Chamados | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="internaChamadosController">
        <header class="main-header">
            <div class="titulo">
                <h1>Detalhes do Chamado</h1>
            </div>
        </header>
        <section class="chamados-detalhes-relato">
            <header>
                <h2 class="texto-laranja">{{$chamado['num_protocolo']}}</h2>
                <span class="data">Data de Abertura: <strong>{{$chamado['data_abertura']}}</strong></span>
            </header>
            @include('site.clientes.chamados.detalhe.relato-chamado')
        </section>
        <section class="chamados-detalhes-historico">
            <header>
                <h2>Histórico de Atendimento</h2>
            </header>
            @include('site.clientes.chamados.detalhe.historico')
        </section>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/interna_chamados.js') }}"></script>
    <script>
        const id = '{{$chamado['id']}}';
    </script>
@endsection