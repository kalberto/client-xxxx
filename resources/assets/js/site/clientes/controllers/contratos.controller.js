(function () {
    'use strict';
    angular.module('app').controller('contratosController',contratos);
    contratos.$inject = ['$scope', 'API'];
    function contratos($scope, API) {
        // Scope vars
        $scope.carregando_contratos_vigentes = true;

        $scope.contratos_vigentes = [];

        //Vigentes
        $scope.pages_vigentes = [];
        $scope.page_vigentes = 1;
        $scope.firstPage_vigentes = 1;
        $scope.lastPage_vigentes = 1;
        $scope.limit_vigentes  = 10;
        $scope.total_vigentes = 0;
        $scope.showing_vigentes = {
            primeiro: 1,
            ultimo: 1
        };

	    $scope.getDocumento = getDocumento;

	    function getDocumento(documento) {
		    if(documento.length === 14)
			    return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
		    return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
	    }

        // Methods
        $scope.paginateVigentes = function() {
            let starPage, endPage;
            let x = 0;
            let number_pages = Math.ceil($scope.total_vigentes / $scope.limit_vigentes);
            while ($scope.pages_vigentes.length > 0) { $scope.pages_vigentes.pop(); }
            if(number_pages >= 1) {
                starPage = 1;
                endPage = number_pages;
                for (let i = starPage; i <= endPage; i++){
                    $scope.pages_vigentes[x] = i;
                    x++;
                }
                $scope.firstPage_vigentes = starPage;
                $scope.lastPage_vigentes = endPage;
            }
            if ($scope.page_vigentes == 1)
                $scope.showing_vigentes.primeiro = 1;
            else
                $scope.showing_vigentes.primeiro = ($scope.limit_vigentes * ($scope.page_vigentes-1)) + 1;
            $scope.showing_vigentes.ultimo = ($scope.page_vigentes == $scope.lastPage_vigentes) ? $scope.total_vigentes : $scope.limit_vigentes * $scope.page_vigentes;
        };

        $scope.setPageVigentes = function(navigate) {
            if(!$scope.carregando_contratos_vigentes) {
                $scope.carregando_contratos_vigentes = true;
                if (navigate != null) {
                    if (navigate == 'ante')
                        $scope.page_vigentes -= 1;
                    else if (navigate == 'prox')
                        $scope.page_vigentes += 1;
                }
                $scope.getContratosVigentes();
            }
        };

        $scope.getContratosVigentes = function () {
            $scope.carregando_contratos_vigentes = true;
            API.get('cliente/contratos/vigentes?page='+$scope.page_vigentes+'&limit='+$scope.limit_vigentes).then(
                function successCallback (response) {
                    $scope.contratos_vigentes = response.data.contratos_vigentes;
                    $scope.total_vigentes = response.data.total;
                    $scope.next_page = response.data.next_page_url;
                    $scope.limit = response.data.per_page;
                    $scope.carregando_contratos_vigentes = false;
                    $scope.paginateVigentes()
                },
                function errorCallback (response) {
                    $scope.carregando_contratos_vigentes = false;
                }
            );
        };
        $scope.getContratosVigentes();
    }
})();