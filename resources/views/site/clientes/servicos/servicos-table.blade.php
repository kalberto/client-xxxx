<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 04/12/2017
 * Time: 11:53
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 20/01/2020
 */
?>
@include('site/shared/clientes-loader',['carregando' => 'carregando'])
<table id="table-servicos" class="default" ng-if="carregando == false">
    <tbody>
        <tr ng-repeat="item in servicos" ng-cloak ng-class="{carregado: true}" style="animation-delay:@{{$index*70}}ms">
            <td>@{{item.nome}}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            <td data-text="DESIGNADOR">@{{item.id}}</td>
            <td data-text="ENDEREÇO">@{{item.endereco}}</td>
            <td class="botoes"><a class="botao" href="@{{item.link}}">Detalhes</a> <a class="botao" href="{{url('cliente/chamados/abrir-chamado')}}">Abrir Chamado</a></td>
        </tr>
    </tbody>
</table>
<div class="nenhum-conteudo" ng-if="servicos.length == 0 && carregando == false" ng-cloak ng-class="{carregado: true}">
    <span>Nenhum chamado encontrado</span>
</div>