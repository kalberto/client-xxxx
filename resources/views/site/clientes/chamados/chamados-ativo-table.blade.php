@include('site/shared/clientes-loader',['carregando' => 'carregando_ativos'])
<table ng-if="{{$chamados}}.length != 0 && carregando_ativos == false" class="default">
    <tbody>
        <tr ng-repeat="item in {{$chamados}}" ng-cloak ng-class="{carregado: true}" style="animation-delay:@{{$index*70}}ms">
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
<div class="nenhum-conteudo" ng-if="{{$chamados}}.length == 0 && carregando_ativos == false" ng-cloak ng-class="{carregado: true}">
    <span>Nenhum chamado encontrado</span>
</div>