@include('site/shared/clientes-loader',['carregando' => 'carregando_historico'])
<div ng-repeat="node in incident_nodes" class="historico-atendimento" ng-if="!carregando_historico">
    <div id="@{{node.id}}" ng-class="{ativo: relatoAtivo == node.id, carregado: !carregando_historico}" style="animation-delay:@{{$index*70}}ms">
        <div class="mensagem-parcial">
            <div class="usuario">
                <div class="foto"><img src="{{url('assets/images/cliente/ico-usuario-semfoto.png')}}" alt="@{{ node.usuario.nome}}" width="40" height="40"></div>
                <span>@{{node.usuario.nome}}</span>
            </div>
            <div class="mensagem">
                Leia Mais ...
            </div>
            <div class="timestamp">
                <div class="data">@{{node.date}}</div>
                <div class="hora">@{{ node.time }}</div>
            </div>
            <div class="ver-mais"><button ng-click="setRelatoAtivo(node.id)">V</button></div>
        </div>
        <div class="mensagem-completa">
            <h4>Mensagem</h4>
            <p>
                <div ng-bind-html="node.text"></div>
            </p>
        </div>
    </div>
</div>