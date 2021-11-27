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
        <h2>Contratos Vigentes</h2>
    </header>
    @include('site.shared.clientes-loader',['carregando' => 'carregando_contratos'])
    <table class="default" ng-cloak ng-class="{carregando_contratos: true}" style="animation-delay:@{{$index*70}}ms">
        <tr ng-repeat="item in contratos" ng-cloak ng-class="{carregando_contratos: true}" style="animation-delay:@{{$index*70}}ms">
            <td data-text="CONTRATO" class="texto-laranja">@{{ item.contrato }}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            <td data-text="VENCIMENTO">@{{ item.vencimento }}</td>
            <td class="texto-laranja" data-text="FATURAMENTO">@{{item.faturamento}}</td>
            <td data-text="MENSALIDADE">R$ @{{ item.mensalidade }}</td>
            <td class="botoes"></td>
        </tr>
    </table>
</section>
