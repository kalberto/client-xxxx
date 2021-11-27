<?php
/**
 * Created by Marco Andrey Chmielewski Nunes.
 * Date: 11/01/2018
 * Time: 11:04
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/04/2020
 */
?>
@include('site/shared/clientes-loader',['carregando' => 'carregando_consumo', 'id' => 'grafico-consumo'])
<section class="servicos-grafico-consumo" ng-if="!carregando_consumo && hasGraficoConsumo">
    <header>
        <h2>Gráfico de Consumo</h2>
        <div class="filtros periodo">
            <label class="right" for="">
                Período de
                <input type="date" ng-model="start">
                à
                <input type="date" ng-model="end">
                <button ng-click="getPeriodo(start,end)">
                    @icon('ico-filtro-data')
                </button>
            </label>
        </div>
    </header>
    <am-chart id="grafico-consumo" options="grafico_consumo"></am-chart>
</section>
