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
        <h2>Ultimas Faturas</h2>
    </header>
    @include('site.shared.clientes-loader',['carregando' => 'carregando_faturas'])
    <table class="default" ng-cloak ng-class="{carregando_faturas: true}" style="animation-delay:@{{$index*70}}ms">
        <tr ng-repeat="item in faturas" ng-cloak ng-class="{carregando_faturas: true}" style="animation-delay:@{{$index*70}}ms">
            <td data-text="CONTRATO" class="texto-laranja">@{{ item.contrato }}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            <td data-text="FATURA">@{{ item.fatura }}</td>
            <td data-text="VENCIMENTO">@{{ item.vencimento }}</td>
            <td data-text="VALOR">R$ @{{ item.valor }}</td>
            <td data-text="STATUS" ng-class="item.status == 'Baixa' ? 'texto-pago' : 'texto-vencido'">@{{ item.status }}</td>
            <td class="botoes"><a class="botao" href="@{{ item.link }}" target="_blank">Ver Conta</a></td>
        </tr>
    </table>
</section>
