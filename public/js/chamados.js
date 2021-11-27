(function () {
    'use strict';

    angular.module('app').controller('chamadosController', chamados);
    chamados.$inject = ['$scope', 'API'];
    function chamados($scope, API) {
        $scope.carregando_ativos = true;
        $scope.carregando_resolvidos = true;
        $scope.error = false;
        $scope.chamados_ativos = [];
        $scope.chamados = [];

        $scope.search = function (q) {
            $scope.carregando = true;
            API.get('cliente/chamados/load?search=' + q).then(function successCallback(response) {
                $scope.chamados = response.data.chamados;
                $scope.chamados_ativos = response.data.chamados_ativos;
                $scope.carregando = false;
            }, function errorCallback(response) {
                $scope.carregando = false;
                $scope.error = true;
            });
        };

        $scope.getDocumento = getDocumento;

        function getDocumento(documento) {
            if (documento.length === 14) return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g, "\$1.\$2.\$3\/\$4\-\$5");
            return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g, "\$1.\$2.\$3\-\$4");
        }

        //ATIVOS
        $scope.pages_ativos = [];
        $scope.page_ativos = 1;
        $scope.firstPage_ativos = 1;
        $scope.lastPage_ativos = 1;
        $scope.limit_ativos = 10;
        $scope.total_ativos = 0;
        $scope.showing_ativos = {
            primeiro: 1,
            ultimo: 1
        };
        $scope.getChamados = function () {
            $scope.carregando_ativos = true;
            API.get('cliente/chamados/load?page=' + $scope.page_ativos + '&limit=' + $scope.limit_ativos).then(function successCallback(response) {
                $scope.chamados_ativos = response.data.data;
                $scope.carregando_ativos = false;
                $scope.total_ativos = response.data.total;
                $scope.next_page_ativos = response.data.next_page_url;
                $scope.limit_ativos = '' + response.data.per_page;
                $scope.paginateAtivos();
            }, function errorCallback(response) {
                $scope.carregando_ativos = false;
                $scope.error = true;
            });
        };
        $scope.paginateAtivos = function () {
            var starPage = void 0,
                endPage = void 0;
            var x = 0;
            var number_pages = Math.ceil($scope.total_ativos / $scope.limit_ativos);
            while ($scope.pages_ativos.length > 0) {
                $scope.pages_ativos.pop();
            }
            if (number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (var i = starPage; i <= endPage; i++) {
                    $scope.pages_ativos[x] = i;
                    x++;
                }
                $scope.firstPage_ativos = starPage;
                $scope.lastPage_ativos = endPage;
            }
            if ($scope.page_ativos == 1) $scope.showing_ativos.primeiro = 1;else $scope.showing_ativos.primeiro = $scope.limit_ativos * ($scope.page_ativos - 1) + 1;
            $scope.showing_ativos.ultimo = $scope.page_ativos == $scope.lastPage_ativos ? $scope.total_ativos : $scope.limit_ativos * $scope.page_ativos;
        };
        $scope.setPageAtivos = function (navigate) {
            if (!$scope.carregando_ativos) {
                $scope.carregando_ativos = true;
                if (navigate != null) {
                    if (navigate == 'ante') $scope.page_ativos -= 1;else if (navigate == 'prox') $scope.page_ativos += 1;
                }
                $scope.getChamados();
            }
        };
        $scope.getChamados();

        //RESOLVIDOS
        $scope.pages_resolvidos = [];
        $scope.page_resolvidos = 1;
        $scope.firstPage_resolvidos = 1;
        $scope.lastPage_resolvidos = 1;
        $scope.limit_resolvidos = 10;
        $scope.total_resolvidos = 0;
        $scope.showing_resolvidos = {
            primeiro: 1,
            ultimo: 1
        };
        $scope.getChamadosResolvidos = function () {
            $scope.chamados = [];
            $scope.carregando_resolvidos = true;
            API.get('cliente/chamados/resolved?page=' + $scope.page_resolvidos + '&limit=' + $scope.limit_resolvidos).then(function successCallback(response) {
                $scope.chamados = response.data.data;
                $scope.carregando_resolvidos = false;
                $scope.total_resolvidos = response.data.total;
                $scope.next_page_resolvidos = response.data.next_page_url;
                $scope.limit_resolvidos = '' + response.data.per_page;
                $scope.paginateResolvidos();
            }, function errorCallback(response) {
                $scope.carregando_resolvidos = false;
                $scope.error_resolvidos = true;
            });
        };
        $scope.paginateResolvidos = function () {
            var starPage = void 0,
                endPage = void 0;
            var x = 0;
            var number_pages = Math.ceil($scope.total_resolvidos / $scope.limit_resolvidos);
            while ($scope.pages_resolvidos.length > 0) {
                $scope.pages_resolvidos.pop();
            }
            if (number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (var i = starPage; i <= endPage; i++) {
                    $scope.pages_resolvidos[x] = i;
                    x++;
                }
                $scope.firstPage_resolvidos = starPage;
                $scope.lastPage_resolvidos = endPage;
            }
            if ($scope.page_resolvidos == 1) $scope.showing_resolvidos.primeiro = 1;else $scope.showing_resolvidos.primeiro = $scope.limit_resolvidos * ($scope.page_resolvidos - 1) + 1;
            $scope.showing_resolvidos.ultimo = $scope.page_resolvidos == $scope.lastPage_resolvidos ? $scope.total_resolvidos : $scope.limit_resolvidos * $scope.page_resolvidos;
        };
        $scope.setPageResolvidos = function (navigate) {
            if (!$scope.carregando_resolvidos) {
                $scope.carregando_resolvidos = true;
                if (navigate != null) {
                    if (navigate == 'ante') $scope.page_resolvidos -= 1;else if (navigate == 'prox') $scope.page_resolvidos += 1;
                }
                $scope.getChamadosResolvidos();
            }
        };
        $scope.getChamadosResolvidos();
    }
})();