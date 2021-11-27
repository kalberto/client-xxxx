<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 01/12/2017
 * Time: 14:32
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/04/2020
 */
?>

@extends('site/clientes/cliente-structure')
    @section('head')
    <title> Serviço | xxxx</title>
    <meta name="description" content="">
@endsection

@section('header')

	<script>
		let servicos = [];
		let currentServico = 'servico-1';
		@foreach($items as $index => $item)
            @if(isset($item['ativo']) && $item['ativo'] == true)
			    currentServico = 'servico-{{$index+1}}';
            @endif
			servicos['servico-{{$index+1}}'] = {id:'{{$item['id']}}','get_consumo':'{{($item['grafico_consumo'] != false) ? true : false}}','get_voz':'{{($item['grafico_voz'] != false) ? true : false}}','get_chamadas': '{{($item['chamadas'] != false) ? true : false}}','get_logins':'{{($item['logins'] != false) ? true : false}}'};
		@endforeach
	</script>
@endsection

@section('content')
    <div id="servico-detalhe" ng-controller="internaServicosController">
        <div class="servicos-tab">
            <div class="tab">
                @foreach($items as $index => $item)
                    <button class="servico-{{$index+1}}" ng-click="setServico('servico-{{$index+1}}')" ng-class="{ativo: currentServico == 'servico-{{$index+1}}'}">{{$item['nome']}}</button>
                @endforeach
            </div>
            @foreach($items as $index => $item)
                <section class="servico-{{$index+1}}" ng-class="{ativo: currentServico == 'servico-{{$index+1}}'}" ng-if="currentServico == 'servico-{{$index+1}}'">
                    <header class="main-header">
                        <div class="titulo">
                            <h1>{{$item['nome']}}</h1>
                            <div class="detalhes">
                                <dl>
                                    <dt>Empresa: </dt>
                                    <dd>{{$item['empresa']}}</dd>

                                    <dt>Endereço:</dt>
                                    <dd>{{$item['endereco']}}</dd>

                                    <dt>DOCUMENTO:</dt>
                                    <dd>{{$item['documento']}}</dd>

                                    <dt>Telefones:</dt>
                                    <dd>{{$item['telefones']}}</dd>
                                </dl>
                            </div>
                        </div>
                    </header>

                    @includeWhen($item['manuais'], 'site.clientes.servicos.detalhe.manuais',['manuais' => $item['manuais']])
                    @includeWhen($item['chamados'],'site.clientes.servicos.detalhe.chamados',['chamados' => $item['chamados']])
                    @includeWhen($item['dados_servicos'],'site.clientes.servicos.detalhe.dados',['servico' => $item['dados_servicos']])
                    @includeWhen($item['enderecamentos'], 'site.clientes.servicos.detalhe.enderecamento-ip',['enderecamentos' => $item['enderecamentos']])
                    @includeWhen($item['enderecamento_ipv6'], 'site.clientes.servicos.detalhe.enderecamento-ipv6',['enderecamento_ipv6' => $item['enderecamento_ipv6'] ])
                    @includeWhen($item['grafico_consumo'],'site.clientes.servicos.detalhe.grafico-consumo')
                    @includeWhen($item['dados_fibra'], 'site.clientes.servicos.detalhe.dados-fibra-call', ['dados' => $item['dados_fibra']])
                    @includeWhen($item['dados_media'], 'site.clientes.servicos.detalhe.dados-media-gateway', ['dados' => $item['dados_media']])
                    @includeWhen($item['grafico_voz'],'site.clientes.servicos.detalhe.grafico-voz',['servico' => 'servico-'.($index+1)])
                    @includeWhen($item['logins'], 'site.clientes.servicos.detalhe.logins')
                    @includeWhen($item['chamadas'],'site.clientes.servicos.detalhe.chamadas', ['periodos' => $item['periodos'],'servico' => 'servico-'.($index+1)])
                </section>
            @endforeach
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ url('js/amcharts/serial.js') }}"></script>
    <script src="{{ url('js/amcharts/plugins/responsive/responsive.min.js') }}"></script>
    <script src="{{ url('js/chamadas.js') }}"></script>
    <script src="{{ url('js/logins.js') }}"></script>
    <script src="{{ url('js/interna_servicos.js') }}"></script>
@endsection