(function () {
    'use strict';
    angular.module('app').controller('internaChamadosController',internaChamados);
    internaChamados.$inject = ['$scope', 'API', '$timeout','$sce'];
    function internaChamados($scope, API, $timeout,$sce) {
        // Scope vars
        $scope.carregando_historico = true;
        $scope.relatoAtivo = null;
        $scope.ordenacao = 'most_recent';

        // Methods
        $scope.getHistorico = function() {
            API.get('cliente/chamados/' + id + '/nodes').then(
                function successCallback(response) {
                    $scope.incident_nodes = response.data.incident_nodes;
                    for(var i =0; i < $scope.incident_nodes.length;i++){
                        $scope.incident_nodes[i].text = $sce.trustAsHtml($scope.incident_nodes[i].text);
                    }
                    $scope.carregando_historico = false;
                },
                function errorCallback(response) {
                    $scope.carregando_historico = false;
                    $scope.error = true;
                }
            );
        };

        $scope.setRelatoAtivo = function(relato) {
            if ($scope.relatoAtivo == relato) 
                $scope.relatoAtivo = null;
            else
                $scope.relatoAtivo = relato;

            return false;
        };

        /*$scope.setOrdenacao = function() {
            console.log("Ordenado por "+ $scope.ordenacao);
        };*/
        
        $timeout(function () {
            $scope.getHistorico();
        }, 2000);
    }
})();
