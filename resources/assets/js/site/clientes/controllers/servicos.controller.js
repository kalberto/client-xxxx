(function () {
    'use strict';
    angular.module('app').controller('servicosController',servicos);
    servicos.$inject = ['$scope', 'API'];
    function servicos($scope, API) {
        $scope.carregando = true;
        $scope.error = false;
        $scope.servicos = [];

        $scope.selected = {
          page :1
        };

        //Paginação
        $scope.pages = [];
        $scope.firstPage = 1;
        $scope.lastPage = 1;
        $scope.limit = 10;
        $scope.total = 0;
        $scope.showing = {
            primeiro: 1,
            ultimo: 1
        };

        $scope.getServicos = function () {
            $scope.carregando = true;
            API.get('cliente/servicos/load?page='+$scope.selected.page + '&limit=' + $scope.limit).then(
                function successCallback(response) {
                    $scope.servicos = response.data.data;
                    $scope.carregando = false;
                    $scope.total = response.data.total;
                    $scope.next_page = response.data.next_page_url;
                    $scope.limit = '' + response.data.per_page;
                    $scope.paginate();
                },function errorCallback(response) {
                    $scope.carregando = false;
                    $scope.error = true;
                }
            );
        };
        $scope.paginate = function() {
            let starPage, endPage;
            let x = 0;
            let number_pages = Math.ceil($scope.total / $scope.limit);
            while ($scope.pages.length > 0) { $scope.pages.pop(); }
            if(number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (let i = starPage; i <= endPage; i++){
                    $scope.pages[x] = i;
                    x++;
                }
                $scope.firstPage = starPage;
                $scope.lastPage = endPage;
            }
            if ($scope.selected.page == 1)
                $scope.showing.primeiro = 1;
            else
                $scope.showing.primeiro = ($scope.limit * ($scope.selected.page-1)) + 1;
            $scope.showing.ultimo = ($scope.selected.page == $scope.lastPage) ? $scope.total : $scope.limit * $scope.selected.page;
        };
        $scope.setPage = function(navigate) {
            if(!$scope.carregando) {
                $scope.carregando = true;
                if (navigate != null) {
                    if (navigate == 'ante')
                        $scope.selected.page -= 1;
                    else if (navigate == 'prox')
                        $scope.selected.page += 1;
                }
                $scope.getServicos();
            }
        };
        $scope.getServicos();

        $scope.getDocumento = getDocumento;

	    function getDocumento(documento) {
		    if(documento.length === 14)
			    return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
		    return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
	    }
    }
})();

