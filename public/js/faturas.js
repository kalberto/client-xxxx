(function () {
    'use strict';

    angular.module('app').controller('faturasController', faturas);
    faturas.$inject = ['$scope', 'API'];
    function faturas($scope, API) {
        // Scope vars
        $scope.carregando_faturas = true;
        $scope.carregando_ultimas_faturas = true;
        $scope.ultimas_faturas = [];
        $scope.faturas_anteriores = [];
        $scope.popup_NaoImplementado = false;
        $scope.feature_naoImplementada = true;
        $scope.btn_download_via = 'Emitir 2ª Via';
        $scope.pages = [];
        $scope.pagesUlt = [];

        $scope.selected = {
            page: 1,
            pageUlt: 1
        };
        $scope.firstPage = 1;
        $scope.lastPage = 1;
        $scope.firstPageUlt = 1;
        $scope.lastPageUlt = 1;
        $scope.limit = 10;
        $scope.limitUlt = 10;
        $scope.total = 0;
        $scope.totalUlt = 0;
        $scope.showing = {
            primeiro: 1,
            ultimo: 1
        };
        $scope.showingUlt = {
            primeiro: 1,
            ultimo: 1
        };
        $scope.start = null;
        $scope.end = null;
        // Methods
        $scope.getUltimasFaturas = function () {
            API.get('cliente/faturas/last?page=' + $scope.selected.pageUlt + '&limit=' + $scope.limitUlt).then(function successCallback(response) {
                $scope.ultimas_faturas = response.data.faturas;
                $scope.totalUlt = response.data.total;
                $scope.limitUlt = '' + response.data.per_page;
                $scope.carregando_ultimas_faturas = false;
                $scope.paginateUlt();
            }, function errorCallback(response) {
                $scope.carregando_ultimas_faturas = false;
            });
        };

        $scope.getFaturasAnteriores = function () {
            $scope.faturas_anteriores = [];
            var query = '?page=' + $scope.selected.page + '&limit=' + $scope.limit;
            if ($scope.start && typeof $scope.start.toISOString === 'function') query += '&start=' + $scope.start.toISOString();
            if ($scope.end && typeof $scope.end.toISOString === 'function') query += '&end=' + $scope.end.toISOString();
            API.get('cliente/faturas/list' + query).then(function successCallback(response) {
                $scope.faturas_anteriores = response.data.faturas;
                $scope.total = response.data.total;
                $scope.next_page = response.data.next_page_url;
                $scope.limit = '' + response.data.per_page;
                $scope.carregando_faturas = false;
                $scope.paginate();
            }, function errorCallback(response) {
                $scope.carregando_faturas = false;
            });
        };

        $scope.paginate = function () {
            var starPage = void 0,
                endPage = void 0;
            var x = 0;
            var number_pages = Math.ceil($scope.total / $scope.limit);
            while ($scope.pages.length > 0) {
                $scope.pages.pop();
            }
            if (number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (var i = starPage; i <= endPage; i++) {
                    $scope.pages[x] = i;
                    x++;
                }
                $scope.firstPage = starPage;
                $scope.lastPage = endPage;
            }
            if ($scope.selected.page == 1) $scope.showing.primeiro = 1;else $scope.showing.primeiro = $scope.limit * ($scope.selected.page - 1) + 1;
            $scope.showing.ultimo = $scope.selected.page == $scope.lastPage ? $scope.total : $scope.limit * $scope.selected.page;
        };

        $scope.paginateUlt = function () {
            var starPage = void 0,
                endPage = void 0;
            var x = 0;
            var number_pages = Math.ceil($scope.totalUlt / $scope.limitUlt);
            while ($scope.pagesUlt.length > 0) {
                $scope.pagesUlt.pop();
            }
            if (number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (var i = starPage; i <= endPage; i++) {
                    $scope.pagesUlt[x] = i;
                    x++;
                }
                $scope.firstPageUlt = starPage;
                $scope.lastPageUlt = endPage;
            }
            if ($scope.selected.pageUlt == 1) $scope.showingUlt.primeiro = 1;else $scope.showingUlt.primeiro = $scope.limitUlt * ($scope.selected.pageUlt - 1) + 1;
            $scope.showingUlt.ultimo = $scope.selected.pageUlt == $scope.lastPageUlt ? $scope.totalUlt : $scope.limitUlt * $scope.selected.pageUlt;
        };

        $scope.setPage = function (navigate) {
            if (!$scope.carregando_faturas) {
                $scope.carregando_faturas = true;
                if (navigate != null) {
                    if (navigate == 'ante') $scope.selected.page -= 1;else if (navigate == 'prox') $scope.selected.page += 1;
                }
                $scope.getFaturasAnteriores();
            }
        };

        $scope.setPageUlt = function (navigate) {
            if (!$scope.carregando_ultimas_faturas) {
                $scope.carregando_ultimas_faturas = true;
                if (navigate != null) {
                    if (navigate == 'ante') $scope.selected.pageUlt -= 1;else if (navigate == 'prox') $scope.selected.pageUlt += 1;
                }
                $scope.getUltimasFaturas();
            }
        };

        $scope.solicitarDownload = function (codFatura) {
            $scope.popup_NaoImplementado = true;
        };

        $scope.fecharPopup = function (id) {
            switch (id) {
                case 'nao_implementado':
                    $scope.popup_NaoImplementado = false;break;
            }
        };

        $scope.getPeriodo = function (start, end) {
            if (!$scope.carregando_faturas) {
                $scope.carregando_faturas = true;
                $scope.selected.page = 1;
                $scope.getFaturasAnteriores();
            }
            /*
               $scope.faturas_anteriores = [];
               $scope.carregando_faturas= true;
               API.get('cliente/faturas/list?page='+1+'&limit='+$scope.limit+'&start='+(start && typeof start.toISOString==='function'?start.toISOString():'')+'&end='+(end && typeof end.toISOString==='function'?end.toISOString():'')).then(
                   function successCallback (response) {
                       $scope.faturas_anteriores = response.data.faturas;
                       $scope.total = response.data.total;
                       $scope.next_page = response.data.next_page_url;
                       $scope.limit = '' + response.data.per_page;
                       $scope.carregando_faturas= false;
                       $scope.paginate();
                   },
                   function errorCallback (response) {
                       $scope.carregando_faturas= false;
                   }
               );
               */
        };

        $scope.getUltimasFaturas();
        $scope.getFaturasAnteriores();
    }
})();