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
        <h2>Serviços Contratados</h2>
    </header>
    @include('site/shared/clientes-loader',['carregando' => 'carregando_servicos'])
    <table class="default">
        <tr ng-repeat="item in servicos" ng-cloak ng-class="{carregando_servicos: true}" style="animation-delay:@{{$index*70}}ms">
            <td>@{{item.nome}}</td>
            <td data-text="DOCUMENTO">@{{getDocumento(item.DOCUMENTO)}}</td>
            {{--<td data-text="DATA DE CONTRATAÇÃO">@{{item.data_contratacao}}</td>--}}
            <td data-text="DESIGNADOR">@{{item.id}}</td>
            <td data-text="ENDEREÇO">@{{item.endereco}}</td>
            <td class="botoes"><a class="botao" href="@{{item.link}}">Detalhes</a> <a class="botao" href="{{url('cliente/chamados/abrir-chamado')}}">Abrir Chamado</a></td>
        </tr>
    </table>
</section>
