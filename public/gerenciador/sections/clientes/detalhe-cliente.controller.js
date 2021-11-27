(function() {
    'use strict';

    angular
        .module('painel-inteligencia')
        .controller('detalheClientesController', detalheClientesController);

    detalheClientesController.$inject = ['API', '$mdDialog', '$stateParams', '$mdToast', '$state', 'editorConfig'];

    function detalheClientesController(API, $mdDialog, $stateParams, $mdToast, $state, editorConfig){
        var vm = this;
        vm.cliente = {};
        function getClienteInfo(){
            API.detalhesCliente(documento)
                .then(
                    function successCallback (response) {
                        vm.cliente = response.data;
                    },
                    function errorCallback (response) {
                        //TODO ARRUMAR POSSIVEIS ERROS
                    }
                );
        }
        if($stateParams.documento){
            var documento = $stateParams.documento;
            getClienteInfo(documento);
        }

   
        function showToast(msg){
            $mdToast.show(
              $mdToast.simple()
                .parent(angular.element(document.querySelector('#mainContent')))
                .textContent(msg)
                .hideDelay(1500)
            );
        }
    }
})();
