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
@include('site/shared/clientes-loader',['carregando' => 'carregando_contratos'])
<table class="default">
    <tbody>
        <tr ng-repeat="item in contratos_antigos" ng-cloak ng-class="{carregado: !carregando_contratos}" style="animation-delay:@{{$index*70}}ms">
            <td class="texto-laranja">@{{item.contrato}}</td>
            <td data-text="Dia de Início">@{{ item.inicio }}</td>
            <td data-text="Saldo Atual">@{{item.saldo_atual}}</td>
            {{--<td data-text="Status">@{{item.status}}</td>--}}
            <td class="ver-contrato botoes"><!--<a class="botao" href="#">Ver Contrato</a>--></td>
        </tr>
    </tbody>
</table>
