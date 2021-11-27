(function () {
    'use strict';
    angular.module('app').controller('dashboardController',dashboard);
    dashboard.$inject = ['$scope', 'API'];
    function dashboard($scope, API) {
        $scope.carregando_servicos = true;
        $scope.carregando_chamados = true;
        $scope.carregando_faturas = true;
        $scope.carregando_contratos = true;
        $scope.error = false;
        $scope.servicos = [];
        $scope.chamados = [];
        $scope.faturas = [];
        $scope.contratos = [];

        function getServicos() {
            API.get('cliente/dashboard/servicos').then(
                function successCallback(response) {
                    $scope.servicos = response.data.data;
                    $scope.carregando_servicos = false;
                },function errorCallback(response) {
                    $scope.carregando_servicos = false;
                    $scope.error = true;
                }
            );
        }
        $scope.getDocumento = getDocumento;

        function getDocumento(documento) {
	        if(documento.length === 14)
		        return documento.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/g,"\$1.\$2.\$3\/\$4\-\$5");
	        return documento.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/g,"\$1.\$2.\$3\-\$4");
        }
        function getChamados() {
            API.get('cliente/dashboard/chamados').then(
                function successCallback(response){
                    $scope.chamados = response.data.chamados;
                    $scope.carregando_chamados = false;
                },
                function errorCallback(response) {
                    $scope.carregando_chamados = false;
                    $scope.error = true;
                }
            )
        }
        function getFaturas() {
            API.get('cliente/dashboard/faturas').then(
                function successCallback(response){
                    $scope.faturas = response.data.faturas;
                    $scope.carregando_faturas = false;
                },
                function errorCallback(response) {
                    $scope.carregando_faturas = false;
                    $scope.error = true;
                }
            )
        }

        function getContratos() {
            API.get('cliente/dashboard/contratos').then(
                function successCallback(response){
                    $scope.contratos = response.data.contratos;
                    $scope.carregando_contratos = false;
                },
                function errorCallback(response) {
                    $scope.carregando_contratos = false;
                    $scope.error = true;
                }
            )
        }
        getServicos();
        getChamados();
        getFaturas();
        getContratos();
    }
})();
