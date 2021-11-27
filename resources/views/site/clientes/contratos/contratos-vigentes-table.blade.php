<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 08/12/2017
 * Time: 14:19
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/12/2017
 * Time: 14:19
 */
?>
@include('site/shared/clientes-loader',['carregando' => 'carregando_contratos_vigentes'])
<table class="default">
    <tbody>
        <tr ng-repeat="item in contratos_vigentes" ng-cloak ng-class="{carregado: !carregando_contratos_vigentes}" style="animation-delay:@{{$index*70}}ms">
            <td class="texto-laranja">@{{item.contrato}}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            <td data-text="VENCIMENTO">@{{ item.vencimento }}</td>
            <td class="texto-laranja" data-text="FATURAMENTO">@{{item.faturamento}}</td>
            <td data-text="MENSALIDADE">R$ @{{item.mensalidade}}</td>
            {{--<td data-text="Status">@{{item.status}}</td>--}}
            <td class="ver-contrato botoes"><!--<a class="botao" href="#">Ver Contrato</a>--></td>
        </tr>
    </tbody>
</table>
