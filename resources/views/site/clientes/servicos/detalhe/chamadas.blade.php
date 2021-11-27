<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:48
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 30/01/2017
 * Time: 15:30
 */
?>
<section class="servicos-detalhamentochamada" ng-if="currentServico == '{{$servico}}'">
    <header>
        <h2>Detalhamento de Chamada</h2>
    </header>
    <div class="clear tabela-filtro">
        <div class="filtros periodo">
            <a href="#" class="texto-laranja"><small ng-click="chamadas.popUp(1)">Visualizar Online - últimos 30 dias</small></a>
            <div class="right">
                <div class="wrapper-select">
                    <select ng-model="chamadas.data.date" ng-init="date='{{$periodos[0]["intervalo"]}}'; chamadas.setDate(date); chamadas.getList(); ">
                        @foreach($periodos as $periodo)
                            <option value="{{$periodo['intervalo']}}">{{$periodo['text']}}</option>
                        @endforeach
                    </select>
                </div>
                <button ng-click="chamadas.getList()">
                    @icon('ico-filtro-data')
                </button>
            </div>
        </div>
        <table class="data chamada borda-laranja">
            <tbody>
                    <tr ng-repeat="item in chamadas.data.lista" ng-class="{carregando_list_chamadas: true}">
                        <td>@{{item['text']}}</td>
                        <td class="texto-center">
                            <a href="@{{item['csv']}}" target="_blank" class="download csv" title="Download do Arquivo em CSV">Download do Arquivo em CSV</a>
                            <a href="@{{item['xls']}}" target="_blank" class="download xls" title="Download do Arquivo em XLS">Download do Arquivo em XLS</a>
                        </td>
                    </tr>
            </tbody>
        </table>
        @include('site/shared/clientes-loader',['carregando' => 'chamadas.data.carregandoList'])
    </div>

    <div id="popup-chamadas" class="popup">
        <div class="popup-content">
            <a href="" class="fechar" ng-click="chamadas.popUp(0)">×</a>
            <header>
                <h1>Detalhamento de Chamada</h1>
            </header>
            <div class="tabela-filtro">
                <div class="filtros">
                    <label class="right busca" for="">
                        Filtro
                        <input type="search" ng-model="chamadas.pagination.query">
                        <button ng-click="chamadas.search()"></button>
                    </label>
                </div>
                <table class="data borda-laranja">
                    <thead>
                        <tr>
                            <th>Horário</th>
                            <th>Duração</th>
                            <th>Origem</th>
                            <th>Destino</th>
                            <th>Tipo</th>
                            <th>Custo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="item in chamadas.data.chamadas" ng-class="{carregando_chamadas: true}">
                            <td>@{{item.call_start_time}}</td>
                            <td>@{{(item.duration > 60) ? (((item.duration-(item.duration%60))/60) + ' m ' + (item.duration % 60) + ' s') : item.duration + ' s'}}</td>
                            <td>@{{item.caller_id}}</td>
                            <td>@{{item.callee_id}}</td>
                            <td>@{{ item.call_type }}</td>
                            <td>R$ @{{ item.price }}</td>
                        </tr>
                    </tbody>
                </table>
                <div class="pag-2">
                    <label class="right" for="">
                        Página: 
                        <div class="wrapper-select">
                            <select ng-model="chamadas.data.pagination.page" ng-options="item for item in chamadas.data.pages track by item" ng-change="chamadas.setPage(null)"></select>
                        </div>
                    </label>
                    <span>@{{chamadas.data.showing.primeiro}} - @{{chamadas.data.showing.ultimo}} de @{{chamadas.data.pagination.total}}</span>
                    <div class="setas">
                        <a class="ante" ng-class="{hide: chamadas.data.pagination.page == chamadas.data.pagination.firstPage}" ng-click="chamadas.setPage('ante')" title="Anterior"></a>
                        <a class="prox" ng-class="{hide: chamadas.data.pagination.page == chamadas.data.pagination.lastPage}" ng-click="chamadas.setPage('prox')" title="Próximo"></a>
                    </div>
                </div>
            </div>
            @include('site/shared/clientes-loader',['carregando' => 'chamadas.data.carregando'])
        </div>
    </div>

</section>