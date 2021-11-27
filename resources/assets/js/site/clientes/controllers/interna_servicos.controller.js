(function () {
    'use strict';
    angular.module('app').controller('internaServicosController', internaServicos);
    internaServicos.$inject = ['$scope', 'API', 'CHAMADAS','LOGINS'];

    function internaServicos($scope, API, CHAMADAS,LOGINS) {
    	//servicos
	    $scope.chamadas = CHAMADAS;
	    $scope.logins = LOGINS;

        // Scope Vars
        $scope.carregando = true;
        $scope.carregando_login = true;
        $scope.carregando_consumo = false;
        $scope.carregando_consumo_voz = false;
        $scope.error = false;
        $scope.currentServico = 'servico-1';
        $scope.servico = null;
        $scope.grafico_consumo = null;
        $scope.grafico_voz = null;
        $scope.start = '';
        $scope.end = '';
        $scope.visao = 'minutos';
        $scope.custozero = 'sim';
        $scope.consolidacao = 'hora';

        $scope.short_unidade_grafico_voz = 'min';
        $scope.unidade_grafico_voz = 'Minutos';

        $scope.has_consumo_voz = false;

        $scope.hasGraficoConsumo = false;
        $scope.hasGraficoVoz = false;

        // AmCharts Vars
        var amcharts_graphs = {
            consumo: [],
            voz: []
        };
        var amcharts_balloon = {
            consumo: {},
            voz: {}
        };
        var amcharts_balloonText = {
            consumo: "",
            voz: ""
        };
        var amcharts_categoryAxis = {
            consumo: {},
            voz: {}
        };
        var amcharts_chartCursor = {
            consumo: {},
            voz: {}
        };
        var amcharts_chartScrollbar = {
            consumo: {},
            voz: {}
        };
        var amcharts_valueAxes = {
            consumo: [],
            voz: []
        };
        var amcharts_valueScrollbar = {
            consumo: {},
            voz: {}
        };
        var amcharts_legend = {
            consumo: {},
            voz: {}
        };
        var amcharts_prefixesBigNumbers = {
            consumo: [],
            voz: []
        };
        var amcharts_listeners = {
            consumo: [],
            voz: []
        };
        var amcharts_responsive = {
            consumo: {
                "enabled": true
            },
            voz: {
                "enabled": true
            }
        };
        var amcharts_export = {
            consumo: {
                "enabled": false
            },
            voz: {
                "enabled": false
            }
        };
        var amcharts_dateFormat = "DD/MM/YYYY HH:NN";

        // Private Methods
        function initAmChartsConsumo(response) {
            var graphs_values = [{
                    column: "Downlink",
                    fillAlpha: 0.74,
                    fillColor: "#E75629",
                    markerType: "triangleDown",
                    markerColor: "#E75629"
                },
                {
                    column: "Uplink",
                    fillAlpha: 0,
                    fillColor: "#000000",
                    markerType: "triangleUp",
                    markerColor: "#FF8000"
                }
            ];

            amcharts_valueAxes.consumo = [{
                "id": "TrafegoColumn",
                "labelFunction": function (value, valueText, valueAxis) {
                    return valueText = valueText + "bps";
                },
                "title": "Tráfego"
            }];

            amcharts_categoryAxis.consumo = {
                "gridThickness": 1,
                "gridAlpha": 0,
                "minorGridAlpha": 0,
                "autoRotateCount": 0,
                "classNameField": "",
                "forceShowField": "",
                "labelColorField": "",
                "minPeriod": "mm",
                "parseDates": true,
                "color": "#000000",
                "fontSize": 11,
                "labelFrequency": 6,
                "labelOffset": 2,
                "minVerticalGap": 0,
                "title": ""
            };

            amcharts_chartCursor.consumo = {
                "enabled": true,
                "bulletsEnabled": true,
                "categoryBalloonDateFormat": amcharts_dateFormat
            };

            amcharts_legend.consumo = {
                "enabled": true,
                "align": "center",
                "labelWidth": 350,
                "maxColumns": 2,
                "periodValueText": "[[value.sum]]bps",
                "valueText": "[[value]]bps",
                "valueWidth": 100
            };

            amcharts_balloon.consumo = {
                "borderColor": "#EE8163",
                "color": "#000",
                "cornerRadius": 1,
                "fillAlpha": 1,
                "fontSize": 11,
                "maxWidth": 250,
                "textAlign": "left"
            };

            amcharts_prefixesBigNumbers.consumo = [{
                    "number": 1e+3,
                    "prefix": "K"
                },
                {
                    "number": 1e+6,
                    "prefix": "M"
                },
                {
                    "number": 1e+9,
                    "prefix": "G"
                },
                {
                    "number": 1e+12,
                    "prefix": "T"
                },
                {
                    "number": 1e+15,
                    "prefix": "P"
                },
                {
                    "number": 1e+18,
                    "prefix": "E"
                },
                {
                    "number": 1e+21,
                    "prefix": "Z"
                },
                {
                    "number": 1e+24,
                    "prefix": "Y"
                }
            ];

            amcharts_listeners.consumo = [{
                event: "init",
                method: function (e) {
                    // Por default, são exibidos dados correspondentes a 24hrs, com espaçamento de 05min.
                    // Default: (response.length-288 > 0 ? response.length-288 : 0)
                    e.chart.zoomToIndexes((response.length - 288 > 0 ? response.length - 288 : 0), response.length);
                }
            }];

            amcharts_chartScrollbar.consumo = {
                "enabled": true
            };
            amcharts_balloonText.consumo = "<b>[[title]]</b><br>[[date]] - [[value]]bps";

            var graphs_atributes = {};
            for (var i = 0; i < 2; i++) {
                graphs_atributes = {
                    "balloonText": amcharts_balloonText.consumo,
                    "dateFormat": amcharts_dateFormat,
                    "fillColors": graphs_values[i].fillColor,
                    "fillAlphas": graphs_values[i].fillAlpha,
                    "id": graphs_values[i].column,
                    "legendAlpha": 1,
                    "legendColor": graphs_values[i].markerColor,
                    "lineAlpha": 0.45,
                    "lineColor": graphs_values[i].fillColor,
                    "lineThickness": 2,
                    "markerType": graphs_values[i].markerType,
                    "title": graphs_values[i].column,
                    "valueField": graphs_values[i].column
                };
                amcharts_graphs.consumo.push(graphs_atributes);
            }
        }

        let colors = ["#008000", "#0000FF", "#9400D3", "#FF8000", "#CC0000", "#4B0082", "#800000", "#00B7A8", "#00491A", "#EA00FF", "#9AAD75", "#5A4706"];

        function initAmChartsVoz(response, categories) {

            let maxSize = colors.length;
            let graphs_values = [];
            let text = "<b>[[DATE]]</b><br><br><ul>";
            for (let i = 0; i < categories.length; i++) {
                if (maxSize >= i) {
                    graphs_values.push({
                        column: categories[i],
                        fillColors: colors[i]
                    });
                } else {
                    //TODO
                }
                text = text + "<li>" + categories[i] + ": <b>[[" + categories[i] + "]] " + $scope.short_unidade_grafico_voz + "</b> ([[" + (categories[i] + "_percent") + "]]%)</li>";
            }
            text = text + "</ul><br><b>Total: [[total]] " + $scope.short_unidade_grafico_voz + "</b>";
            amcharts_balloon.voz = {
                "borderColor": "#EE8163",
                "color": "#000",
                "cornerRadius": 1,
                "fillAlpha": 1,
                "fontSize": 11,
                "maxWidth": 250,
                "textAlign": "left"
            };

            amcharts_categoryAxis.voz = {
                "gridPosition": "start",
                "gridThickness": 1,
                "gridAlpha": 0,
                "minorGridAlpha": 0,
                "minPeriod": "hh",
                "parseDates": true,
                "equalSpacing" : true
            };

            amcharts_chartCursor.voz = {
                "enabled": true,
                "categoryBalloonDateFormat": amcharts_dateFormat,
                "cursorAlpha": 0.43,
                "cursorColor": "#CCC",
                "fullWidth": true,
                "oneBalloonOnly": true,
                "showNextAvailable": true,
                "tabIndex": 1,
                "zoomable": true,
                "valueZoomable": true,
            };

            amcharts_valueAxes.voz = [{
                "id": "MinutosColumn",
                "stackType": "regular",
                "labelFunction": function (value, valueText, valueAxis) {
                    return valueText = valueText + " " + $scope.short_unidade_grafico_voz;
                },
                "title": "Minutos"
            }];

            amcharts_valueScrollbar.voz = {
                "enabled": true,
                "dragIcon": "dragIconRectSmall",
                "dragIconHeight": 25
            };

            amcharts_legend.voz = {
                "enabled": true,
                "align": "center",
                "maxColumns": 3,
                "useGraphSettings": true,
                "valueAlign": "left"
            };

            amcharts_chartScrollbar.voz = {
                "enabled": true
            }
            amcharts_listeners.voz = [{
                event: "init",
                method: function (e) {
                    // Por default, são exibidos dados correspondentes a 24hrs, com espaçamento de 05min.
                    // Default: (response.length-24 > 0 ? response.length-24 : 0)
                    e.chart.zoomToIndexes((response.length - 24 > 0 ? response.length - 24 : 0), response.length);
                }
            }];

            amcharts_balloonText.voz = text;

            var graphs_atributes = {};
            for (var i = 0; i < graphs_values.length; i++) {
                graphs_atributes = {
                    "type": "column",
                    "tabIndex": 1,
                    "balloonText": amcharts_balloonText.voz,
                    "id": graphs_values[i].column,
                    "title": graphs_values[i].column,
                    "valueField": graphs_values[i].column,

                    "fillColors": graphs_values[i].fillColors,
                    "fillAlphas": 0.77,
                    "lineColor": "#FFFFFF",
                    "lineThickness": 1,

                    "dateFormat": amcharts_dateFormat,
                    "legendPeriodValueText": "00 " + $scope.short_unidade_grafico_voz,
                    "legendValueText": "[[value]] " + $scope.short_unidade_grafico_voz
                };
                amcharts_graphs.voz.push(graphs_atributes);
            }
        }

        function fixAmChartsVoz(response, categories) {
            var tempSum, tempPercent;
            for (var i = 0; i < response.length; i++) {
                tempSum = 0;
                tempPercent = 0;
                for (let e = 0; e < categories.length; e++) {
                    tempSum += parseFloat(response[i][categories[e]]);
                }
                for (let e = 0; e < categories.length; e++) {
                    if (tempSum !== 0)
                        response[i][categories[e] + '_percent'] = ((parseFloat(response[i][categories[e]]) / tempSum) * 100).toFixed(2);
                    else
                        response[i][categories[e] + '_percent'] = 0.00;
                }
            }
            return response;
        }

        function getGraficoConsumo() {
            $scope.carregando_consumo = true;
	        $scope.hasGraficoConsumo = true;
            API.get('cliente/servicos/' + $scope.servico.id + '/service-traffic').then(
                function successCallback(response) {
                    initAmChartsConsumo(response.data);
                    $scope.grafico_consumo = {
                        "data": response.data,
                        "type": "serial",
                        "categoryField": "date",
                        "columnWidth": 0,
                        "dataDateFormat": amcharts_dateFormat,
                        "maxSelectedSeries": 0,
                        "mouseWheelScrollEnabled": true,
                        "autoMarginOffset": 20,
                        "marginTop": 20,
                        "marginRight": 45,
                        "sequencedAnimation": false,
                        "startDuration": 0.25,
                        "startEffect": "bounce",
                        "zoomOutButtonTabIndex": 1,
                        "zoomOutText": "Exibir todos",
                        "creditsPosition": "top-left",
                        "decimalSeparator": ",",
                        "thousandsSeparator": ".",
                        "prefixesOfBigNumbers": amcharts_prefixesBigNumbers.consumo,
                        "usePrefixes": true,
                        "precision": 2,
                        "categoryAxis": amcharts_categoryAxis.consumo,
                        "chartCursor": amcharts_chartCursor.consumo,
                        "chartScrollbar": amcharts_chartScrollbar.consumo,
                        "graphs": amcharts_graphs.consumo,
                        "valueAxes": amcharts_valueAxes.consumo,
                        "responsive": amcharts_responsive.consumo,
                        "legend": amcharts_legend.consumo,
                        "export": amcharts_export.consumo,
                        "listeners": amcharts_listeners.consumo
                    };
                    $scope.carregando_consumo = false;
                },
                function errorCallback(response) {
                    $scope.carregando_consumo = false;
                }
            );
        }

        function getGraficoVoz() {
            var dados = [];
            var categories = [];
            $scope.carregando_consumo_voz = true;
	        $scope.hasGraficoVoz = true;
            API.get('cliente/servicos/' + $scope.servico.id + '/called-traffic').then(
                function successCallback(response) {
                    dados = response.data.data;
                    categories = response.data.categories;
                    $scope.short_unidade_grafico_voz = response.data.short_unidade;
                    $scope.unidade_grafico_voz = response.data.unidade;
                    let total = [];
                    if (dados !== undefined && dados !== null && dados.length > 0) {
                        for (let i = 0; i < dados.length; i++) {
                            total.push(0);
                            for (let e = 0; e < categories.length; e++) {
                                total[i] += dados[i][categories[e]];
                            }
                        }
                        dados = fixAmChartsVoz(dados, categories);
                        initAmChartsVoz(dados, categories);
                        $scope.grafico_voz = {
                            "data": dados,
                            "type": "serial",
                            "categoryField": "DATE",
                            "columnSpacing": 0,
                            "zoomOutText": "Exibir todos",
                            "sequencedAnimation": false,
                            "startDuration": 0.5,
                            "creditsPosition": "top-left",
                            "mouseWheelScrollEnabled": false,
                            "decimalSeparator": ",",
                            "thousandsSeparator": ".",
                            "autoMarginOffset": 20,
                            "marginTop": 20,
                            "marginRight": 30,
                            "dataDateFormat": amcharts_dateFormat,
                            "categoryAxis": amcharts_categoryAxis.voz,
                            "valueAxes": amcharts_valueAxes.voz,
                            "chartScrollbar": amcharts_valueScrollbar.voz,
                            "chartCursor": amcharts_chartCursor.voz,
                            "graphs": amcharts_graphs.voz,
                            "balloon": amcharts_balloon.voz,
                            "balloonDateFormat": amcharts_dateFormat,
                            "legend": amcharts_legend.voz,
                            "responsive": amcharts_responsive.voz,
                            "export": amcharts_export.voz,
                            "listeners": amcharts_listeners.voz
                        };
                        $scope.has_consumo_voz = true;
                    }else{
	                    $scope.has_consumo_voz = false;
                    }
                    $scope.carregando_consumo_voz = false;
                },
                function errorCallback(response) {
                    $scope.carregando_consumo_voz = false;
	                $scope.has_consumo_voz = false;
                }
            );
            // fix response
        }

        $scope.setServico = function (section_id) {
            $scope.currentServico = section_id;
	        $scope.servico = servicos[section_id];
	        if($scope.servico.get_consumo){
		        getGraficoConsumo();
	        }
	        if($scope.servico.get_voz){
		        getGraficoVoz();
	        }
	        if($scope.servico.get_chamadas){
	        	CHAMADAS.setServico($scope.servico.id);
	        }
	        if($scope.servico.get_logins){
	            LOGINS.setServico($scope.servico.id);
		        LOGINS.getLogins();
            }
        };

        $scope.setDateChamadas = CHAMADAS.setDate;

        $scope.getPeriodo = function (start, end) {
            $scope.carregando_consumo = true;
            $scope.start = start;
            $scope.end = end;
            API.get('cliente/servicos/' + $scope.servico.id + '/service-traffic?start=' + (typeof start.toISOString === 'function' ? start.toISOString() : '') + '&end=' + (typeof end.toISOString === 'function' ? end.toISOString() : '')).then(
                function successCallback(response) {
                    amcharts_listeners.consumo = [{
                        event: "init",
                        method: function (e) {
                            // Por default, são exibidos dados correspondentes a 24hrs, com espaçamento de 05min.
                            // Default: (response.length-288 > 0 ? response.length-288 : 0)
                            e.chart.zoomToIndexes((response.data.length - 288 > 0 ? response.data.length - 288 : 0), response.data.length);
                        }
                    }];
                    $scope.grafico_consumo = {
                        "data": response.data,
                        "type": "serial",
                        "categoryField": "date",
                        "columnWidth": 0,
                        "dataDateFormat": amcharts_dateFormat,
                        "maxSelectedSeries": 0,
                        "mouseWheelScrollEnabled": true,
                        "autoMarginOffset": 20,
                        "marginTop": 20,
                        "marginRight": 45,
                        "sequencedAnimation": false,
                        "startDuration": 0.25,
                        "startEffect": "bounce",
                        "zoomOutButtonTabIndex": 1,
                        "zoomOutText": "Exibir todos",
                        "creditsPosition": "top-left",
                        "decimalSeparator": ",",
                        "thousandsSeparator": ".",
                        "prefixesOfBigNumbers": amcharts_prefixesBigNumbers.consumo,
                        "usePrefixes": true,
                        "precision": 2,
                        "categoryAxis": amcharts_categoryAxis.consumo,
                        "chartCursor": amcharts_chartCursor.consumo,
                        "chartScrollbar": amcharts_chartScrollbar.consumo,
                        "graphs": amcharts_graphs.consumo,
                        "valueAxes": amcharts_valueAxes.consumo,
                        "responsive": amcharts_responsive.consumo,
                        "legend": amcharts_legend.consumo,
                        "export": amcharts_export.consumo,
                        "listeners": amcharts_listeners.consumo
                    };
                    
                    $scope.carregando_consumo = false;
                },
                function errorCallback(response) {
                    $scope.carregando_consumo = false;
                }
            );
        };

        $scope.getPeriodoVoz = function (start, end, visao, custozero, consolidacao) {
            $scope.carregando_consumo_voz = true;
            $scope.start = start;
            $scope.end = end;
            $scope.visao = visao;
            $scope.custozero = custozero;
            $scope.consolidacao = consolidacao;
            API.get('cliente/servicos/' + $scope.servico.id + '/called-traffic?start=' + (typeof start.toISOString === 'function' ? start.toISOString() : '') + '&end=' + (typeof end.toISOString == 'function' ? end.toISOString() : '') + '&visao=' + $scope.visao + '&custozero=' + $scope.custozero + '&consolidacao=' + $scope.consolidacao).then(
                function successCallback(response) {
                    var dados = response.data.data;
                    $scope.has_consumo_voz = false;
                    if (dados !== undefined && dados !== null && dados.length > 0) {
                        $scope.short_unidade_grafico_voz = response.data.short_unidade;
                        $scope.unidade_grafico_voz = response.data.unidade;
                        amcharts_listeners.consumo = [{
                            event: "init",
                            method: function (e) {
                                // Por default, são exibidos dados correspondentes a 24hrs, com espaçamento de 05min.
                                // Default: (response.length-288 > 0 ? response.length-288 : 0)
                                e.chart.zoomToIndexes((response.data.data.length - 288 > 0 ? response.data.data.length - 288 : 0), response.data.data.length);
                            }
                        }];
                        let text = '';
                        let maxSize = colors.length;
                        for (let i = 0; i < response.data.categories.length; i++) {

                            text = text + "<li>" + response.data.categories[i] + ": <b>[[" + response.data.categories[i] + "]] " + $scope.short_unidade_grafico_voz + "</b> ([[" + (response.data.categories[i] + "_percent") + "]]%)</li>";
                        }
                        text = text + "</ul><br><b>Total: [[total]] " + $scope.short_unidade_grafico_voz + "</b>";
                        amcharts_graphs.voz.forEach(function (c, i) {
                            amcharts_graphs.voz[i].balloonText = text;
                            amcharts_graphs.voz[i].legendPeriodValueText = "00 " + $scope.short_unidade_grafico_voz;
                            amcharts_graphs.voz[i].legendValueText = "00 " + $scope.short_unidade_grafico_voz;
                        });
                        //amcharts_valueAxes.voz[0].labelFunction = function(value, valueText, valueAxis) { return valueText = valueText+" "+ $scope.short_unidade_grafico_voz; }
                        let dados = fixAmChartsVoz(response.data.data, response.data.categories);
                        if(amcharts_valueAxes.voz.length <= 0)
	                        initAmChartsVoz(dados, response.data.categories);
	                    amcharts_valueAxes.voz[0].title = $scope.unidade_grafico_voz;
                        $scope.grafico_voz = {
                            "data": dados,
                            "type": "serial",
                            "categoryField": "DATE",
                            "zoomOutText": "Exibir todos",
                            "sequencedAnimation": false,
                            "startDuration": 0.5,
                            "creditsPosition": "top-left",
                            "mouseWheelScrollEnabled": true,
                            "decimalSeparator": ",",
                            "thousandsSeparator": ".",
                            "autoMarginOffset": 20,
                            "marginTop": 20,
                            "marginRight": 30,
                            "dataDateFormat": amcharts_dateFormat,
                            "categoryAxis": amcharts_categoryAxis.voz,
                            "valueAxes": amcharts_valueAxes.voz,
                            "valueScrollbar": amcharts_valueScrollbar.voz,
                            "chartCursor": amcharts_chartCursor.voz,
                            "graphs": amcharts_graphs.voz,
                            "balloon": amcharts_balloon.voz,
                            "balloonDateFormat": amcharts_dateFormat,
                            "legend": amcharts_legend.voz,
                            "responsive": amcharts_responsive.voz,
                            "export": amcharts_export.voz,
                        };
                        $scope.has_consumo_voz = true;
                    }
                    $scope.carregando_consumo_voz = false;
                },
                function errorCallback(response) {
                    $scope.carregando_consumo = false;
                }
            );
        };

        // Init
        if(currentServico){
            $scope.currentServico = currentServico;
        }
        $scope.servico = servicos[$scope.currentServico];
        if($scope.servico.get_consumo){
	        getGraficoConsumo();
        }
        if ($scope.servico.get_voz) {
            getGraficoVoz();
        }
	    if($scope.servico.get_chamadas){
		    CHAMADAS.setServico($scope.servico.id);
	    }
	    if($scope.servico.get_logins){
		    LOGINS.setServico($scope.servico.id);
		    LOGINS.getLogins();
	    }
    }
})();