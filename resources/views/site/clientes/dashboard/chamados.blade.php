<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 05/01/2018
 * Time: 09:01
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */
?>
<section>
    <header>
        <h2>Chamados Ativos</h2>
    </header>
    @include('site.shared.clientes-loader',['carregando' => 'carregando_chamados'])
    <table class="default" ng-cloak ng-class="{carregando_chamados: true}" style="animation-delay:@{{$index*70}}ms">
        <tr ng-repeat="item in chamados" ng-cloak ng-class="{carregando_chamados: true}" style="animation-delay:@{{$index*70}}ms">
            <td>@{{ item.nome }}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            <td data-text="NÚMERO DE PROTOCOLO">@{{ item.protocolo }}</td>
            <td data-text="DATA DE ABERTURA">@{{ item.data_abertura }}</td>
            <td data-text="CATEGORIA">@{{ item.categoria }}</td>
            <td data-text="STATUS">@{{ item.status }}</td>
            <td class="botoes"><a class="botao" href="@{{item.link}}">Detalhes</a></td>
        </tr>
    </table>
    <div class="nenhum-conteudo" ng-if="chamados == null && carregando_chamados == false" ng-cloak ng-class="{carregado: true}">
        <span>Nenhum chamado ativo encontrado</span>
    </div>
</section>
