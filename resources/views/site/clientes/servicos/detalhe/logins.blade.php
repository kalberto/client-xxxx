<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:44
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 02/01/2018
 * Time: 17:30
 */
?>
<section class="servicos-logins">
    <header>
        <h2>Logins</h2>
    </header>
    <div class="clear tabela-filtro" ng-class="{'carregado': true, 'hide-block': logins.data.carregando}">
        <div class="filtros">
            <label class="left" for="">
                Mostrar
                <div class="wrapper-select">
                    <select name="" id="" ng-model="logins.data.pagination.limit" ng-change="logins.setLimit()">
                        <option value="10">10</option>
                        <option value="20">20</option>
                        <option value="30">30</option>
                    </select>
                </div>
                entradas
            </label>
            <label class="right busca" for="">
                Filtro
                <input type="search" ng-model="logins.data.pagination.query">
                <button ng-click="logins.search()"></button>
            </label>
        </div>

        <div class="table-overflow">
            <table class="data borda-laranja" id="table-logins">
                <thead>
                    <tr>
                        <th>Usuário</th>
                        <th>Domínio</th>
                        <th>E-mail</th>
                        <th>Nome</th>
                        <th>Conta Mãe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="item in logins.data.logins">
                        <td data-text="usuário">@{{item.usuario}}</td>
                        <td data-text="Domínio">@{{item.dominio}}</td>
                        <td data-text="E-mail">@{{item.email}}</td>
                        <td data-text="Nome">@{{item.nome}}</td>
                        <td data-text="Conta Mãe">@{{item.conta_mae}}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="pag-2">
            <label class="right" for="">
                Página: 
                <div class="wrapper-select">
                    <select ng-model="logins.data.pagination.page" ng-options="item for item in logins.data.pages track by item" ng-change="logins.setPage(null)"></select>
                </div>
            </label>
            <span>@{{logins.data.showing.primeiro}} - @{{logins.data.showing.ultimo}} de @{{logins.data.pagination.total}}</span>
            <div class="setas">
                <a class="ante" ng-class="{hide: logins.data.pagination.page == logins.data.pagination.firstPage}" ng-click="logins.setPage('ante')" title="Anterior"></a>
                <a class="prox" ng-class="{hide: logins.data.pagination.page == logins.data.pagination.lastPage}" ng-click="logins.setPage('prox')" title="Próximo"></a>
            </div>
        </div>
    </div>
    @include('site/shared/clientes-loader',['carregando' => 'logins.data.carregando'])
</section>