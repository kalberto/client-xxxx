@extends('site.clientes.cliente-structure')
@section('head')
    <title> Novo Chamado | xxxx</title>
    <meta name="description" content="">
@endsection

@section('content')
    <div ng-controller="abrirChamadosController">
        <header class="main-header">
            <div class="titulo">
                <h1>Novo Chamado</h1>
            </div>
        </header>
        <section ng-if="!chamado_aberto">
            <header>
                <h2>Qual seu produto?</h2>
            </header>
            <div>
                <ul class="select-lista-produtos">
                    @foreach ($produtos as $item)
                        <li>
                            <input id="{{$item['id']}}" type="radio" name="produto" ng-model="nome_do_produto" ng-value="'{{$item['nome_do_produto']}}'" hidden>
                            <label for="{{$item['id']}}" ng-click="popUpManual('{{$item['nome_do_produto']}}','{{$item['url']}}')">
                                @if( file_exists(base_path('resources\assets\svg\\'.$item['icone'].'.svg')))
                                    @icon($item['icone'])
                                @else
                                    @icon('icone-default')
                                @endif


                                <span>{{$item['nome_do_produto']}}</span>
                            </label>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>

        {{-- Abrir Chamado --}}
        @include('site/shared/clientes-loader',['carregando' => 'carregando_novo'])
        <section ng-if="falha_manual == true && chamado_aberto == false && carregando_novo == false">
            <header>
                <h2>Abrir Chamado</h2>
            </header>
            <form action="">
                <ul class="clearfix">
                    @include('site/shared/clientes-loader',['carregando' => 'carregando_chamados'])
                    <li class="grid6">
                        <label ng-cloak ng-if="!carregando_chamados">
                            <span>Designador</span>
                            <span class="select-wrapper">
                                <select name="servico" id="servico" ng-model="form.servico">
                                    <option ng-repeat="item in servicos" value="@{{item.id}}">@{{item.nome}}</option>
                                </select>
                            </span>
                        </label>
                    </li>
                    <li class="grid6">
                        <label ng-cloak ng-if="!carregando_chamados">
                            <span>Assunto</span>
                            <span class="select-wrapper">
                                <select name="assunto" id="assunto" ng-model="form.assunto" ng-change="getDescriptions(form.assunto)">
                                    <option value="reparo">Reparo</option>
                                    <option ng-repeat="item in assuntos" value="@{{item}}">@{{item}}</option>
                                </select>
                            </span>
                        </label>
                    </li>
                    <li class="grid6">
                        <label ng-cloak ng-if="!carregando_chamados">
                            <span>Descrição</span>
                            <span class="select-wrapper">
                                <select name="description" id="description" ng-model="form.description">
                                    <option ng-repeat="item in descriptions" value="@{{item.id}}">@{{item.name}}</option>
                                </select>
                            </span>
                        </label>
                    </li>
                    <li class="grid6">
                        <label ng-cloak ng-if="!carregando_chamados">
                            <span>Nota</span>
                            <textarea name="nota" id="nota" ng-model="form.nota" rows="6"></textarea>
                        </label>
                    </li>
                    <li>
                        <a ng-click="postContato()" class="botao mw150">Enviar</a>
                    </li>
                </ul>
            </form>
        </section>

        {{-- Chamado Criado --}}
        <section class="chamado-aberto" ng-if="chamado_aberto">
            <header>
                <h2>@{{ mensagem_chamado_aberto }}</h2>
            </header>
            <div class="border-bottom detalhes-chamada">
                <div>
                    <p><strong>Fibra Connect | Número</strong></p>
                    <p class="numero"><strong>@{{novo_chamado}}</strong></p>
                </div>
                <div>
                    <a href="@{{chamado_aberto_url}}" class="botao">Detalhes</a>
                </div>
            </div>
            <div>
                <p>@{{ resumo_chamado_aberto }}</p>
                <a ng-click="fecharChamado() " class="botao">Voltar ao ínicio</a>
            </div>
        </section>

        <section>
            <header>
                <h2>Últimos Chamados</h2>
            </header>
            @include('site/shared/clientes-loader',['carregando' => 'carregando'])
            <table ng-if="chamados.length != 0 && carregando == false" class="default">
                <tbody>
                <tr ng-repeat="item in chamados" ng-cloak ng-class="{carregado: true}" style="animation-delay:@{{$index*70}}ms">
                    <td>@{{item.nome}}</td>
                    <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
                    <td class="texto-laranja" data-text="Número de Protocolo">@{{item.protocolo}}</td>
                    <td data-text="Data de Abertura">@{{ item.data_abertura }}</td>
                    <td data-text="Categoria">@{{ item.categoria }}</td>
                    <td data-text="Status">@{{ item.status }}</td>
                    <td class="botoes"><a class="botao" href="@{{item.link}}">Detalhes</a></td>
                </tr>
                </tbody>
            </table>
            <div class="nenhum-conteudo" ng-if="chamados.length == 0 && carregando == false" ng-cloak ng-class="{carregado: true}">
                <span>Nenhum chamado encontrado</span>
            </div>
        </section>

        {{-- Popup Manuais --}}
        <div class="popup pop-chamado" ng-class="{ativo: popup_manual}">
            <div class="pelicula" ng-click="popup_manual = false; falha_manual = false; falha_faq = false;"></div>
            <div class="popup-content">
                <p>Você já consultou os manuais e/ou a página de FAQ do <strong>@{{ produto.nome }}</strong> para tentar resolver seu problema?</p>
                <a class="botao" ng-click="abrirChamado(produto.url)">Sim, mas não consegui resolver meu problema.</a>
                <a href="@{{ produto.url_faqs }}" class="botao">Quero consultar a página de FAQ.</a>
                <a href="@{{ produto.url_manuais }}" class="botao">Quero consultar os manuais.</a>
            </div>
        </div>

        {{-- Popup Reparo --}}
        <div class="popup pop-chamado" ng-class="{ativo: popup_reparo}">
            <div class="pelicula" ng-click="popup_reparo = false;"></div>
            <div class="popup-content">
                <p>Para abertura de chamado(s) de “Reparo”, entre em contato com o <strong>0800 604 3939</strong>.</p>
                <a class="botao" ng-click="popup_reparo = false;">Ok</a>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ url('js/abrir_chamados.js') }}"></script>
@endsection