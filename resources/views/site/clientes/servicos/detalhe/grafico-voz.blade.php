<?php
/**
 * Created by Alberto de Almeida Guilherme.
 * Date: 26/12/2017
 * Time: 13:34
 *
 * Last edited by Alberto de Almeida Guilherme
 * Date: 08/04/2020
 */
?>
@include('site/shared/clientes-loader',['carregando' => 'carregando_consumo_voz','id' => 'grafico-voz'])
<section class="servicos-grafico-voz" ng-if="!carregando_consumo_voz">
    <header>
        <h2>Gráfico de Voz</h2>
        <div class="filtros periodo right">
            <label class="abre-menu" for="abreMenu-{{$servico}}">
                Mais opções
            </label>
            <input id="abreMenu-{{$servico}}" class="abre-menu-input" type="checkbox" name="menu" value="" hidden>
            <div class="menu-filtro">
                <label>
                    Visão:
                    <div class="wrapper-select">
                        <select ng-model="visao">
                            <option value="minutos">Minutos</option>
                            <option value="price">Reais</option>
                        </select>
                    </div>
                </label>
                <label>
                    Incluir chamadas de custo zero:
                    <div class="wrapper-select">
                        <select ng-model="custozero">
                            <option value="sim">Sim</option>
                            <option value="nao">Não</option>
                        </select>
                    </div>
                </label>
                <label>
                    Consolidação:
                    <div class="wrapper-select">
                        <select ng-model="consolidacao">
                            <option value="hora">Por hora</option>
                            <option value="12horas">Por 12 horas</option>
                            <option value="24horas">Por 24 horas</option>
                        </select>
                    </div>
                </label>
                <label>
                    Período de
                    <input type="date" ng-model="start">
                    à
                    <input type="date" ng-model="end">
                </label>
                <button ng-click="getPeriodoVoz(start,end,visao,custozero,consolidacao)">
                    Aplicar Filtro @icon('ico-filtro-data')
                </button>
            </div>
        </div>
    </header>
    <am-chart options="grafico_voz" id="grafico-voz" ng-if="has_consumo_voz && hasGraficoVoz" ></am-chart>
</section>
