<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 04/12/2017
 * Time: 11:59
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/12/2017
 * Time: 14:11
 */
?>
@include('site/shared/clientes-loader',['carregando' => $carregando])
<table ng-if="{{$faturas}}.length != 0 && {{$carregando}} == false" class="default">
    <tbody>
        <tr ng-repeat="item in {{$faturas}}" ng-cloak ng-class="{carregado: !{{$carregando}}}" style="animation-delay:@{{$index*70}}ms">
            <td data-text="CONTRATO" class="texto-laranja">@{{item.contrato}}</td>
            <td data-text="FATURA">@{{item.fatura}}</td>
            <td data-text="VENCIMENTO">@{{item.vencimento}}</td>
            <td data-text="VALOR">R$ @{{item.valor}}</td>
            <td data-text="STATUS" ng-class="item.status == 'Baixa' ? 'texto-pago' : 'texto-vencido'">@{{item.status}}</td>
            <td class="botoes">
                <a class="botao" target="_blank" href="@{{item.link}}">Ver Conta</a>
                <button ng-if="item.status == 'vencido' || item.status == 'pendente'" class="emitir-via botao disabled" ng-click="return" ng-mouseenter="btn_download_via = 'Indisponível'" ng-mouseleave="btn_download_via = 'Emitir 2ª Via'">@{{btn_download_via}}</button>
                {{-- <button class="botao" ng-class="item.solicitado ? 'download-via' : 'emitir-via'" ng-click="solicitarDownload(item.fatura)" ng-disabled="feature_naoImplementada">@{{item.solicitado ? 'Download' : 'Emitir'}} 2<sup>a</sup> Via</button> --}}
            </td>
        </tr>
    </tbody>
</table>
<div class="nenhum-conteudo" ng-if="{{$faturas}}.length == 0 && carregando_faturas == false" ng-cloak ng-class="{carregado: !{{$carregando}}}">
    <span>Nenhuma fatura encontrada</span>
</div>
